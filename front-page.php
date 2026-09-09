<?php 
/**
 * الصفحة الرئيسية - نقابة Soul Reapers
 */
get_header(); 
?>

<main class="contenedor seccion">
    
    <!-- قسم أحدث الحلقات المضافة -->
    <section class="home-section-block">
        <div class="section-head-title" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem; border-bottom: 2px solid var(--gris-claro); padding-bottom: 1rem;">
            <h3 style="color: var(--Primario); margin: 0; font-size: 2.4rem;">
                🔥 أحدث الحلقات المضافة
            </h3>
        </div>

        <div class="episodes-grid">
            <?php
                $args_episodes = array(
                    'post_type'      => 'animwp_capitulos',
                    'posts_per_page' => 12,
                    'post_status'    => 'publish'
                );
                $query_episodes = new WP_Query($args_episodes);

                if ($query_episodes->have_posts()):
                    while ($query_episodes->have_posts()): $query_episodes->the_post();
                        $cover = function_exists('get_field') ? get_field('cover') : '';
                        if(empty($cover) && has_post_thumbnail()) {
                            $cover = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                        }
            ?>
                <a href="<?php the_permalink(); ?>" class="episode-card">
                    <div class="episode-thumb">
                        <?php if(!empty($cover)): ?>
                            <img src="<?php echo esc_url($cover); ?>" alt="<?php the_title_attribute(); ?>">
                        <?php else: ?>
                            <div class="no-thumb" style="display:flex;align-items:center;justify-content:center;height:100%;color:#777;font-size:1.2rem;">حلقة</div>
                        <?php endif; ?>
                    </div>
                    <h4 class="episode-name"><?php the_title(); ?></h4>
                </a>
            <?php 
                    endwhile;
                    wp_reset_postdata();
                else:
            ?>
                <p class="sr-alert sr-warning">لا توجد حلقات مضافة بعد. يمكنك البدء بنشر الحلقات من زر النشر بالأعلى!</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- قسم أحدث الأنميات المترجمة في النقابة -->
    <section class="home-section-block" style="margin-top: 5rem;">
        <div class="section-head-title" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem; border-bottom: 2px solid var(--gris-claro); padding-bottom: 1rem;">
            <h3 style="color: #fff; margin: 0; font-size: 2.4rem;">
                ✨ أعمال النقابة (الأنميات)
            </h3>
        </div>

        <div class="episodes-grid">
            <?php
                $args_series = array(
                    'post_type'      => 'animwp_serie',
                    'posts_per_page' => 6,
                    'post_status'    => 'publish'
                );
                $query_series = new WP_Query($args_series);

                if ($query_series->have_posts()):
                    while ($query_series->have_posts()): $query_series->the_post();
                        $poster = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'medium') : '';
            ?>
                <a href="<?php the_permalink(); ?>" class="episode-card anime-card-item">
                    <div class="episode-thumb" style="height: 22rem;">
                        <?php if(!empty($poster)): ?>
                            <img src="<?php echo esc_url($poster); ?>" alt="<?php the_title_attribute(); ?>">
                        <?php else: ?>
                            <div class="no-thumb" style="display:flex;align-items:center;justify-content:center;height:100%;color:#777;font-size:1.2rem;">أنمي</div>
                        <?php endif; ?>
                    </div>
                    <h4 class="episode-name" style="font-weight: bold; color: var(--Primario);"><?php the_title(); ?></h4>
                </a>
            <?php 
                    endwhile;
                    wp_reset_postdata();
                else:
            ?>
                <p class="sr-alert sr-warning">لم يتم إضافة أي عمل حتى الآن.</p>
            <?php endif; ?>
        </div>
    </section>

</main>

<?php get_footer(); ?>
