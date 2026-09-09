<!DOCTYPE html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title('|', true, 'right'); ?> <?php bloginfo('name'); ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Changa:wght@700;900&family=Cairo:wght@400;600;700&family=Montserrat:wght@800&display=swap" rel="stylesheet">

    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

    <header class="site-navbar">
        <div class="contenedor navegacion">
            
            <!-- لوغو النقابة الناري -->
            <div class="logo">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo" title="<?php bloginfo('name'); ?>">
                    <span class="logo-ar">النّقـابـة</span>
                    <span class="logo-en">SOUL REAPERS</span>
                </a>
            </div>

            <!-- القائمة وزر النشر -->
            <div class="enlaces">
                <?php
                    wp_nav_menu(array(
                        'theme_location' => 'main-menu',
                        'container'      => 'nav',
                        'container_class'=> 'menu'
                    ));
                ?>

                <!-- قائمة النشر المنسدلة للناشرين فقط -->
                <?php if (sr_can_user_publish()): ?>
                    <div class="publish-dropdown">
                        <button type="button" class="btn-publish-trigger">
                            <span>نشر ▾</span>
                        </button>
                        <div class="publish-menu">
                            <a href="<?php echo esc_url(home_url('/add-episode/')); ?>">
                                🎬 نشر حلقة جديدة
                            </a>
                            <a href="<?php echo esc_url(home_url('/add-anime/')); ?>">
                                ✨ إضافة عمل جديد
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </header>

    <!-- سلايدر أحدث الحلقات في الرئيسية -->
    <?php if(is_front_page()): ?>
    <section class="header-slider swiper">
        <div class="swiper-wrapper recents-slider">
            <?php
                $args = array(
                    'post_type'      => 'animwp_capitulos',
                    'posts_per_page' => 6,
                    'post_status'    => 'publish'
                );
                $capitulos = new WP_Query($args);
                if ($capitulos->have_posts()):
                    while ($capitulos->have_posts()): $capitulos->the_post();
                        $cover = function_exists('get_field') ? get_field('cover') : '';
                        if(empty($cover) && has_post_thumbnail()) {
                            $cover = get_the_post_thumbnail_url(get_the_ID(), 'full');
                        }
            ?>
                <div class="swiper-slide">
                    <a href="<?php the_permalink(); ?>" class="slide-item">
                        <div class="slide-thumb">
                            <?php if(!empty($cover)): ?>
                                <img src="<?php echo esc_url($cover); ?>" alt="<?php the_title_attribute(); ?>">
                            <?php else: ?>
                                <div class="no-thumb">حلقة جديدة</div>
                            <?php endif; ?>
                        </div>
                        <div class="slide-overlay">
                            <h4 class="slide-title"><?php the_title(); ?></h4>
                        </div>
                    </a>
                </div>
            <?php 
                    endwhile;
                    wp_reset_postdata();
                endif; 
            ?>
        </div>
    </section>
    <?php endif; ?>
