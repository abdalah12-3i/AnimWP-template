<?php 
/**
 * الأرشيف والقالب الاحتياطي - نقابة Soul Reapers
 */
get_header(); 
?>

<main class="contenedor seccion">
    <header style="margin-bottom: 3rem; border-right: 4px solid var(--Primario); padding-right: 1.5rem;">
        <h1 style="color: var(--Primario); margin-bottom: 0.5rem;"><?php the_archive_title(); ?></h1>
    </header>

    <div class="episodes-grid">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <a href="<?php the_permalink(); ?>" class="episode-card">
                <div class="episode-thumb">
                    <?php if (has_post_thumbnail()): ?>
                        <?php the_post_thumbnail('medium'); ?>
                    <?php else: ?>
                        <div class="no-thumb" style="display:flex;align-items:center;justify-content:center;height:100%;color:#777;">منشور</div>
                    <?php endif; ?>
                </div>
                <h4 class="episode-name"><?php the_title(); ?></h4>
            </a>
        <?php endwhile; else: ?>
            <p class="sr-alert sr-warning">لم يتم العثور على أي نتائج.</p>
        <?php endif; ?>
    </div>

    <div style="margin-top: 3rem; text-align: center;">
        <?php the_posts_pagination(array('prev_text' => '← السابق', 'next_text' => 'التالي →')); ?>
    </div>
</main>

<?php get_footer(); ?>
