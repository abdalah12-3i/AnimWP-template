<?php
/**
 * Theme Name: Soul Reapers - النقابة
 * Description: قالب مخصص لموقع الأنمي والترجمة Soul Reapers Guild
 */

// تضمين الودجات
if (file_exists(get_template_directory() . '/include/widgets.php')) {
    require get_template_directory() . '/include/widgets.php';
}

// 1. إعدادات القالب الأساسية
function soulreapers_setup() {
    // دعم الصور البارزة
    add_theme_support('post-thumbnails');

    // دعم العناوين التلقائية للسيو (SEO)
    add_theme_support('title-tag');

    // دعم اللوغو من التخصيص
    add_theme_support('custom-logo');
}
add_action('after_setup_theme', 'soulreapers_setup');

// 2. تسجيل القوائم
function soulreapers_menus() {
    register_nav_menus(array(
        'main-menu' => __('القائمة الرئيسية (Main Menu)', 'soulreapers')
    ));
}
add_action('init', 'soulreapers_menus');

// 3. تضمين الملفات والمكتبات (CSS & JS)
function soulreapers_scripts() {
    // Swiper Slider CSS
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css', array(), '10.1.0');
    // Normalize CSS
    wp_enqueue_style('normalize', 'https://necolas.github.io/normalize.css/8.0.1/normalize.css', array(), '8.0.1');
    // القالب الأساسي
    wp_enqueue_style('soulreapers-style', get_stylesheet_uri(), array('normalize'), '1.0.0');

    // Scripts
    wp_enqueue_script('jquery');
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', array(), '10.1.0', true);
    wp_enqueue_script('soulreapers-script', get_template_directory_uri() . '/js/script.js', array('jquery', 'swiper-js'), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'soulreapers_scripts');

// 4. تسجيل الشريط الجانبي (تم إصلاح أخطاء الكلمات)
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

// 5. تسجيل نوع منشور مخصص: (الأعمال والأنميات)
function soulreapers_register_series() {
    $labels = array(
        'name'               => 'الأنميات والأعمال',
        'singular_name'      => 'أنمي',
        'menu_name'          => 'قائمة الأنمي',
        'add_new'            => 'إضافة عمل جديد',
        'add_new_item'       => 'إضافة أنمي جديد',
        'edit_item'          => 'تعديل الأنمي',
        'new_item'           => 'أنمي جديد',
        'view_item'          => 'عرض صفحة الأنمي',
        'search_items'       => 'بحث في الأنميات',
        'not_found'          => 'لم يتم العثور على أي عمل',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'menu_icon'          => 'dashicons-video-alt3',
        'supports'           => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'rewrite'            => array('slug' => 'series'),
        'show_in_rest'       => true,
    );

    register_post_type('animwp_serie', $args);
}
add_action('init', 'soulreapers_register_series');

// 6. تسجيل نوع منشور مخصص: (الحلقات)
function soulreapers_register_episodes() {
    $labels = array(
        'name'               => 'الحلقات',
        'singular_name'      => 'حلقة',
        'menu_name'          => 'الحلقات',
        'add_new'            => 'إضافة حلقة جديدة',
        'add_new_item'       => 'إضافة حلقة جديدة',
        'edit_item'          => 'تعديل الحلقة',
        'new_item'           => 'حلقة جديدة',
        'view_item'          => 'مشاهدة الحلقة',
        'search_items'       => 'بحث في الحلقات',
        'not_found'          => 'لا توجد حلقات مضافة',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'menu_icon'          => 'dashicons-media-video',
        'supports'           => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'rewrite'            => array('slug' => 'episodes'),
        'show_in_rest'       => true,
    );

    register_post_type('animwp_capitulos', $args);
}
add_action('init', 'soulreapers_register_episodes');

// 7. تصنيف الأنمي المربوط بالحلقات
function soulreapers_anime_taxonomy() {
    $labels = array(
        'name'              => 'اسم الأنمي',
        'singular_name'     => 'الأنمي',
        'search_items'      => 'بحث عن أنمي',
        'all_items'         => 'جميع الأنميات',
        'edit_item'         => 'تعديل الأنمي',
        'update_item'       => 'تحديث الأنمي',
        'add_new_item'      => 'إضافة أنمي جديد',
        'new_item_name'     => 'اسم الأنمي الجديد',
        'menu_name'         => 'تصنيف الأنميات',
    );

    register_taxonomy('anime', array('animwp_capitulos', 'animwp_serie'), array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'anime'),
        'show_in_rest'      => true,
    ));
}
add_action('init', 'soulreapers_anime_taxonomy');
