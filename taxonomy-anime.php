<?php
/**
 * صفحة أرشيف تصنيف الأنمي - نقابة Soul Reapers
 */
get_header();

$current_term = get_queried_object();
$anime_name   = !empty($current_term->name) ? $current_term->name : 'الأنمي';
?>

<main class="contenedor seccion">
    <header class="taxonomy-header" style="margin-bottom: 3rem; border-right: 4px solid var(--Primario); padding-right: 1.5rem;">
        <h1 style="color: var(--Primario); margin-bottom: 0.5rem;"><?php echo esc_html($anime_name); ?></h1>
        <p style="color: #aaa; margin: 0; font-size: 1.4rem;">جميع الحلقات المتوفرة التابعة لـ (<?php echo esc_html($anime_name); ?>)</p>
    </header>

    <div class="episodes-grid">
        <?php if (have_posts()) : while (have_posts()) : the_post(); 
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
        <?php endwhile; else: ?>
            <p class="sr-alert sr-warning">لم يتم نشر أي حلقات تابعة لهذا العمل بعد.</p>
        <?php endif; ?>
    </div>

    <!-- ترقيم الصفحات إن زادت الحلقات -->
    <div class="pagination" style="margin-top: 4rem; text-align: center;">
        <?php the_posts_pagination(array('prev_text' => '← السابق', 'next_text' => 'التالي →')); ?>
    </div>
</main>

<?php get_footer(); ?>
