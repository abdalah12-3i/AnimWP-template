<?php
/**
 * Theme Name: Soul Reapers - النقابة
 * Description: قالب مخصص لموقع الأنمي والترجمة Soul Reapers Guild
 */

// 1. تضمين الودجات
if (file_exists(get_template_directory() . '/include/widgets.php')) {
    require get_template_directory() . '/include/widgets.php';
}

// 2. إعدادات القالب الأساسية
function soulreapers_setup() {
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
}
add_action('after_setup_theme', 'soulreapers_setup');

// 3. تسجيل القوائم
function soulreapers_menus() {
    register_nav_menus(array(
        'main-menu' => __('القائمة الرئيسية (Main Menu)', 'soulreapers')
    ));
}
add_action('init', 'soulreapers_menus');

// 4. تضمين الملفات والمكتبات
function soulreapers_scripts() {
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css', array(), '10.1.0');
    wp_enqueue_style('normalize', 'https://necolas.github.io/normalize.css/8.0.1/normalize.css', array(), '8.0.1');
    wp_enqueue_style('soulreapers-style', get_stylesheet_uri(), array('normalize'), '1.0.0');

    wp_enqueue_script('jquery');
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', array(), '10.1.0', true);
    wp_enqueue_script('soulreapers-script', get_template_directory_uri() . '/js/script.js', array('jquery', 'swiper-js'), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'soulreapers_scripts');

// 5. تسجيل الشريط الجانبي (Sidebar)
function soulreapers_widgets() {
    register_sidebar(array(
        'name'          => 'الشريط الجانبي (Sidebar)',
        'id'            => 'sidebar_1',
        'before_widget' => '<div class="widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title text-center">',
        'after_title'   => '</h3>'
    ));
}
add_action('widgets_init', 'soulreapers_widgets');

// 6. تسجيل نوع المنشور: الأعمال والأنميات
function soulreapers_register_series() {
    $labels = array(
        'name'               => 'الأنميات والأعمال',
        'singular_name'      => 'أنمي',
        'menu_name'          => 'قائمة الأنمي',
        'add_new'            => 'إضافة عمل جديد',
        'add_new_item'       => 'إضافة أنمي جديد',
        'edit_item'          => 'تعديل الأنمي',
        'all_items'          => 'جميع الأنميات',
    );
    register_post_type('animwp_serie', array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'menu_icon'          => 'dashicons-video-alt3',
        'supports'           => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'rewrite'            => array('slug' => 'series'),
        'show_in_rest'       => true,
    ));
}
add_action('init', 'soulreapers_register_series');

// 7. تسجيل نوع المنشور: الحلقات
function soulreapers_register_episodes() {
    $labels = array(
        'name'               => 'الحلقات',
        'singular_name'      => 'حلقة',
        'menu_name'          => 'الحلقات',
        'add_new'            => 'إضافة حلقة جديدة',
        'add_new_item'       => 'إضافة حلقة جديدة',
        'edit_item'          => 'تعديل الحلقة',
        'all_items'          => 'جميع الحلقات',
    );
    register_post_type('animwp_capitulos', array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'menu_icon'          => 'dashicons-media-video',
        'supports'           => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'rewrite'            => array('slug' => 'episodes'),
        'show_in_rest'       => true,
    ));
}
add_action('init', 'soulreapers_register_episodes');

// 8. تسجيل تصنيف الأنمي
function soulreapers_anime_taxonomy() {
    register_taxonomy('anime', array('animwp_capitulos', 'animwp_serie'), array(
        'hierarchical'      => true,
        'labels'            => array(
            'name'          => 'اسم الأنمي',
            'singular_name' => 'الأنمي',
            'menu_name'     => 'تصنيف الأنميات',
        ),
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'anime'),
        'show_in_rest'      => true,
    ));
}
add_action('init', 'soulreapers_anime_taxonomy');

// 9. تسجيل رتبة "ناشر النقابة" وصلاحية النشر
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

// فحص صلاحية النشر للمستخدم الحالي
function sr_can_user_publish() {
    return is_user_logged_in() && (current_user_can('publish_guild_content') || current_user_can('administrator'));
}
