<?php 
/**
 * الصفحة الرئيسية - نقابة Soul Reapers
 * الهوية: بنفسجي حاصد الأرواح الداكن + توهج أزرق داكن
 */
get_header(); 
?>

<main class="contenedor seccion" style="padding: 4rem 0;">
    
    <!-- قسم أحدث الحلقات المضافة -->
    <section class="home-section-block">
        <div class="section-head-title" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem; border-bottom: 2px solid var(--gris-claro); border-right: 4px solid var(--Aura-Blue); padding: 0.5rem 1.5rem 1rem 0;">
            <h3 style="color: #c77dff; margin: 0; font-size: 2.4rem; text-shadow: 0 0 12px rgba(138, 43, 226, 0.45); display: flex; align-items: center; gap: 8px;">
                <span>⚡ أحدث الحلقات المضافة</span>
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
                <a href="<?php the_permalink(); ?>" class="episode-card" style="position: relative; text-decoration: none;">
                    <div class="episode-thumb" style="height: 14rem; background: #0c0d14; overflow: hidden; position: relative;">
                        <?php if(!empty($cover)): ?>
                            <img src="<?php echo esc_url($cover); ?>" alt="<?php the_title_attribute(); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <div class="no-thumb" style="display:flex;align-items:center;justify-content:center;height:100%;color:#666;font-size:1.3rem;">حلقة</div>
                        <?php endif; ?>

                        <!-- شارة زرقاء متوهجة للحلقة -->
                        <span style="position: absolute; top: 8px; right: 8px; background: var(--Aura-Blue); color: #000; font-size: 1.1rem; font-weight: 800; padding: 2px 8px; border-radius: 4px; box-shadow: 0 0 8px rgba(0, 180, 216, 0.6);">
                            حلقة جديدة
                        </span>
                    </div>

                    <h4 class="episode-name" style="color: #eee; font-size: 1.4rem; padding: 1.2rem; margin: 0; text-align: center; line-height: 1.4;">
                        <?php the_title(); ?>
                    </h4>
                </a>
            <?php 
                    endwhile;
                    wp_reset_postdata();
                else:
            ?>
                <div style="grid-column: 1 / -1; background: rgba(138, 43, 226, 0.1); border: 1px solid var(--Primario); color: #c77dff; padding: 2rem; border-radius: 8px; font-weight: 600;">
                    💡 لم يتم نشر أي حلقات بعد. يمكنك البدء بإضافة أول حلقة من زر <strong>نشر ▾</strong> في الأعلى!
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- قسم أعمال نقابة حاصد الأرواح (الأنميات) -->
    <section class="home-section-block" style="margin-top: 6rem;">
        <div class="section-head-title" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem; border-bottom: 2px solid var(--gris-claro); border-right: 4px solid var(--Primario); padding: 0.5rem 1.5rem 1rem 0;">
            <h3 style="color: var(--Aura-Blue); margin: 0; font-size: 2.4rem; text-shadow: 0 0 12px rgba(0, 180, 216, 0.45); display: flex; align-items: center; gap: 8px;">
                <span>⚔️ أعمال نقابة حاصد الأرواح</span>
            </h3>
        </div>

        <div class="episodes-grid" style="grid-template-columns: repeat(auto-fill, minmax(20rem, 1fr));">
            <?php
                $args_series = array(
                    'post_type'      => 'animwp_serie',
                    'posts_per_page' => 8,
                    'post_status'    => 'publish'
                );
                $query_series = new WP_Query($args_series);

                if ($query_series->have_posts()):
                    while ($query_series->have_posts()): $query_series->the_post();
                        $poster = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'medium') : '';
                        $status = get_post_meta(get_the_ID(), 'anime_status', true);
                        if (empty($status)) $status = 'مترجم';
            ?>
                <a href="<?php the_permalink(); ?>" class="episode-card anime-card-item" style="position: relative; text-decoration: none;">
                    <div class="episode-thumb" style="height: 28rem; background: #0c0d14; position: relative; overflow: hidden;">
                        <?php if(!empty($poster)): ?>
                            <img src="<?php echo esc_url($poster); ?>" alt="<?php the_title_attribute(); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <div class="no-thumb" style="display:flex;align-items:center;justify-content:center;height:100%;color:#666;font-size:1.3rem;">بوستر الأنمي</div>
                        <?php endif; ?>

                        <!-- شارة حالة العمل بنفسجية متوهجة -->
                        <span style="position: absolute; top: 10px; right: 10px; background: linear-gradient(135deg, #7b2cbf, #5a189a); color: #fff; font-size: 1.1rem; font-weight: 700; padding: 3px 10px; border-radius: 4px; border: 1px solid rgba(0, 180, 216, 0.4); box-shadow: 0 0 10px rgba(123, 44, 191, 0.5);">
                            <?php echo esc_html($status); ?>
                        </span>
                    </div>

                    <h4 class="episode-name" style="font-weight: 800; color: #c77dff; font-size: 1.5rem; padding: 1.4rem; margin: 0; text-align: center;">
                        <?php the_title(); ?>
                    </h4>
                </a>
            <?php 
                    endwhile;
                    wp_reset_postdata();
                else:
            ?>
                <div style="grid-column: 1 / -1; background: rgba(0, 180, 216, 0.08); border: 1px solid var(--Aura-Blue); color: var(--Aura-Blue); padding: 2rem; border-radius: 8px; font-weight: 600;">
                    ⚔️ لم يتم إضافة أي عمل في النقابة حتى الآن. اضغط على <strong>نشر ▾ -> إضافة عمل جديد</strong> للبدء!
                </div>
            <?php endif; ?>
        </div>
    </section>

</main>

<?php get_footer(); ?>
