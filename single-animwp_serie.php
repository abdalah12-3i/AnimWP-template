<?php 
    get_header(); 
    $serie_id = get_the_ID();
    $anime_title = get_the_title();
?>

<main class="contenedor">
    <article class="anime-single-container">
        
        <!-- معلومات وبوستر الأنمي -->
        <div class="anime-header">
            <div class="anime-poster">
                <?php if(has_post_thumbnail()): ?>
                    <?php the_post_thumbnail('medium_large'); ?>
                <?php else: ?>
                    <div class="no-poster">لا يوجد بوستر</div>
                <?php endif; ?>
            </div>

            <div class="anime-details">
                <h1 class="anime-title"><?php echo esc_html($anime_title); ?></h1>
                
                <div class="anime-meta">
                    <span class="badge status">حالة العمل: مترجم</span>
                    <span class="badge team">فريق الترجمة: Soul Reapers</span>
                </div>

                <div class="anime-story">
                    <h3>القصة:</h3>
                    <div class="story-content">
                        <?php the_content(); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- قائمة حلقات الأنمي -->
        <div class="anime-episodes-section">
            <h2 class="section-title">حلقات <?php echo esc_html($anime_title); ?></h2>

            <div class="episodes-grid">
                <?php
                    // جلب الحلقات التابعة لنفس الأنمي عبر التصنيف
                    $args = array(
                        'post_type'      => 'animwp_capitulos',
                        'posts_per_page' => -1,
                        'tax_query'      => array(
                            array(
                                'taxonomy' => 'anime',
                                'field'    => 'name',
                                'terms'    => $anime_title,
                            ),
                        ),
                    );

                    $episodes = new WP_Query($args);

                    if($episodes->have_posts()):
                        while($episodes->have_posts()): $episodes->the_post();
                            $cap_cover = function_exists('get_field') ? get_field('cover') : '';
                            if(empty($cap_cover) && has_post_thumbnail()) {
                                $cap_cover = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                            }
                ?>
                    <a href="<?php the_permalink(); ?>" class="episode-card">
                        <div class="episode-thumb">
                            <?php if(!empty($cap_cover)): ?>
                                <img src="<?php echo esc_url($cap_cover); ?>" alt="<?php the_title_attribute(); ?>">
                            <?php else: ?>
                                <div class="no-thumb">حلقة</div>
                            <?php endif; ?>
                        </div>
                        <h4 class="episode-name"><?php the_title(); ?></h4>
                    </a>
                <?php 
                        endwhile;
                        wp_reset_postdata();
                    else:
                ?>
                    <p class="no-episodes">لم يتم إضافة حلقات لهذا العمل حتى الآن.</p>
                <?php endif; ?>
            </div>
        </div>

    </article>
</main>

<?php get_footer(); ?>
