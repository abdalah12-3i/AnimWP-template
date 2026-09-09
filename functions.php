<?php
/**
 * Theme Name: Soul Reapers - النقابة
 * الهوية: نقابة حاصد الأرواح (بنفسجي وأزرق متوهج)
 */

if (file_exists(get_template_directory() . '/include/widgets.php')) {
    require get_template_directory() . '/include/widgets.php';
}

function soulreapers_setup() {
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
}
add_action('after_setup_theme', 'soulreapers_setup');

function soulreapers_menus() {
    register_nav_menus(array(
        'main-menu' => __('القائمة الرئيسية', 'soulreapers')
    ));
}
add_action('init', 'soulreapers_menus');

function soulreapers_scripts() {
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css', array(), '10.1.0');
    wp_enqueue_style('normalize', 'https://necolas.github.io/normalize.css/8.0.1/normalize.css', array(), '8.0.1');
    wp_enqueue_style('soulreapers-style', get_stylesheet_uri(), array('normalize'), '2.1.0');

    wp_enqueue_script('jquery');
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', array(), '10.1.0', true);
    wp_enqueue_script('soulreapers-script', get_template_directory_uri() . '/js/script.js', array('jquery', 'swiper-js'), '2.1.0', true);
}
add_action('wp_enqueue_scripts', 'soulreapers_scripts');

function soulreapers_widgets() {
    register_sidebar(array(
        'name'          => 'الشريط الجانبي',
        'id'            => 'sidebar_1',
        'before_widget' => '<div class="widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>'
    ));
}
add_action('widgets_init', 'soulreapers_widgets');

// تسجيل المنشورات: الأعمال والحلقات
function soulreapers_register_cpts() {
    register_post_type('animwp_serie', array(
        'labels'        => array('name' => 'الأعمال والأنميات', 'singular_name' => 'أنمي'),
        'public'        => true,
        'has_archive'   => true,
        'menu_icon'     => 'dashicons-video-alt3',
        'supports'      => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'rewrite'       => array('slug' => 'series'),
    ));

    register_post_type('animwp_capitulos', array(
        'labels'        => array('name' => 'الحلقات', 'singular_name' => 'حلقة'),
        'public'        => true,
        'has_archive'   => true,
        'menu_icon'     => 'dashicons-media-video',
        'supports'      => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'rewrite'       => array('slug' => 'episodes'),
    ));
}
add_action('init', 'soulreapers_register_cpts');

// تصنيف الأنمي
function soulreapers_anime_taxonomy() {
    register_taxonomy('anime', array('animwp_capitulos', 'animwp_serie'), array(
        'hierarchical' => true,
        'labels'       => array('name' => 'تصنيف الأنمي'),
        'show_ui'      => true,
        'rewrite'      => array('slug' => 'anime'),
    ));
}
add_action('init', 'soulreapers_anime_taxonomy');

// رتبة ناشر النقابة
function soulreapers_register_publisher_role() {
    add_role('guild_publisher', 'ناشر النقابة', array(
        'read'                  => true,
        'edit_posts'            => true,
        'upload_files'          => true,
        'publish_guild_content' => true,
    ));
    $admin = get_role('administrator');
    if ($admin && !$admin->has_cap('publish_guild_content')) {
        $admin->add_cap('publish_guild_content');
    }
}
add_action('init', 'soulreapers_register_publisher_role');

function sr_can_user_publish() {
    return is_user_logged_in() && (current_user_can('publish_guild_content') || current_user_can('administrator'));
}

// 1. حصر البحث في الأنميات والحلقات فقط (منع ظهور Sample Page وملفات الـ zip)
function sr_filter_search_queries($query) {
    if (!is_admin() && $query->is_main_query() && $query->is_search()) {
        $query->set('post_type', array('animwp_serie', 'animwp_capitulos'));
        $query->set('post_status', 'publish');
    }
}
add_action('pre_get_posts', 'sr_filter_search_queries');

// 2. إنشاء الصفحات الأربع المطلوبة تلقائياً عند تفعيل القالب
function sr_auto_create_theme_pages() {
    $pages = array(
        'profile'     => array('title' => 'الملف الشخصي', 'template' => 'template-profile.php'),
        'login'       => array('title' => 'تسجيل الدخول', 'template' => 'template-auth.php'),
        'add-episode' => array('title' => 'نشر حلقة',    'template' => 'template-add-episode.php'),
        'add-anime'   => array('title' => 'إضافة عمل',   'template' => 'template-add-anime.php'),
    );

    foreach ($pages as $slug => $data) {
        $existing = get_page_by_path($slug);
        if (!$existing) {
            $page_id = wp_insert_post(array(
                'post_title'   => $data['title'],
                'post_name'    => $slug,
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_author'  => 1
            ));
            if ($page_id && !is_wp_error($page_id)) {
                update_post_meta($page_id, '_wp_page_template', $data['template']);
            }
        }
        // استخدام الصورة المرفوعة من العضو بدلاً من جرافاتار الافتراضي
function sr_custom_user_avatar($avatar, $id_or_email, $size, $default, $alt) {
    $user_id = 0;
    if (is_numeric($id_or_email)) {
        $user_id = (int) $id_or_email;
    } elseif (is_object($id_or_email) && !empty($id_or_email->user_id)) {
        $user_id = (int) $id_or_email->user_id;
    } elseif (is_string($id_or_email)) {
        $user = get_user_by('email', $id_or_email);
        if ($user) $user_id = $user->ID;
    }

    if ($user_id > 0) {
        $custom_avatar_id = get_user_meta($user_id, 'sr_custom_avatar', true);
        if ($custom_avatar_id) {
            $img_url = wp_get_attachment_image_url($custom_avatar_id, array($size, $size));
            if ($img_url) {
                $avatar = "<img alt='{$alt}' src='{$img_url}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' style='border-radius: 50%; object-fit: cover; border: 2px solid var(--Aura-Blue);' />";
            }
        }
    }
    return $avatar;
}
add_filter('get_avatar', 'sr_custom_user_avatar', 10, 5);
    }
}
add_action('after_switch_theme', 'sr_auto_create_theme_pages');
add_action('init', 'sr_auto_create_theme_pages'); // تشغيل فوري للتأكد
