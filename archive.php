<?php 
/**
 * الأرشيف - نقابة Soul Reapers
 */
get_header(); 
?>

<main class="contenedor seccion" style="padding: 40px 0;">
    <header style="margin-bottom: 30px; border-right: 4px solid #8a2be2; padding-right: 15px;">
        <h1 style="color: #8a2be2; margin-bottom: 5px; font-size: 26px;">أرشيف الحلقات والمنشورات</h1>
    </header>

    <div class="episodes-grid">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <a href="<?php the_permalink(); ?>" class="episode-card">
                <div class="episode-thumb">
                    <?php if (has_post_thumbnail()): ?>
                        <?php the_post_thumbnail('medium'); ?>
                    <?php else: ?>
                        <div style="display:flex;align-items:center;justify-content:center;height:120px;background:#151620;color:#666;">حلقة</div>
                    <?php endif; ?>
                </div>
                <h4 class="episode-name"><?php the_title(); ?></h4>
            </a>
        <?php endwhile; else: ?>
            <p style="background: rgba(138,43,226,0.1); border: 1px solid #8a2be2; color: #c77dff; padding: 15px; border-radius: 6px;">
                لم يتم العثور على أي نتائج في هذا الأرشيف.
            </p>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
