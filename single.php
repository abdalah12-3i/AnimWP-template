<?php 
/**
 * صفحة المنشور الفردي - نقابة Soul Reapers
 */
get_header(); 
?>

<main class="contenedor seccion">
    <?php while (have_posts()) : the_post(); ?>
        <article class="single-post-box" style="background: var(--gris-medio); border: 1px solid var(--gris-claro); padding: 3rem; border-radius: 0.8rem;">
            <h1 style="color: var(--Primario); margin-bottom: 1.5rem;"><?php the_title(); ?></h1>
            
            <div style="color: #888; font-size: 1.3rem; margin-bottom: 2rem;">
                <span>📅 نشر في: <?php echo get_the_date(); ?></span>
            </div>

            <?php if (has_post_thumbnail()): ?>
                <div style="margin-bottom: 2rem; border-radius: 0.6rem; overflow: hidden;">
                    <?php the_post_thumbnail('full'); ?>
                </div>
            <?php endif; ?>

            <div class="entry-content" style="color: #eee; font-size: 1.6rem; line-height: 2;">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
