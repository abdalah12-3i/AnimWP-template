<?php
/**
 * قالب تفاصيل الحلقة - نقابة Soul Reapers
 */
while(have_posts()): the_post();
?>
    <div class="capitulo-header-details" style="margin-bottom: 2rem;">
        <h2 class="text-white" style="margin-bottom: 1rem; font-size: 2.6rem; color: var(--blanco);">
            <?php the_title(); ?>
        </h2>

        <div class="capitulo-meta-tags" style="display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap; margin-bottom: 1.5rem;">
            <!-- جلب تصنيف الأنمي المخصص الصحيح بدلاً من the_category القديمة -->
            <div class="anime-tax-badge">
                <?php 
                    $anime_terms = get_the_term_list(get_the_ID(), 'anime', '<span class="badge" style="background: var(--Primario); color: #fff; padding: 4px 10px; border-radius: 4px; font-weight: bold;">📺 الأنمي: ', ', ', '</span>');
                    if (!empty($anime_terms) && !is_wp_error($anime_terms)) {
                        echo $anime_terms;
                    }
                ?>
            </div>

            <span style="color: #888; font-size: 1.3rem;">
                📅 تم النشر: <?php echo get_the_date(); ?>
            </span>
        </div>

        <?php if (get_the_content()): ?>
            <div class="capitulo-description" style="color: #bbb; font-size: 1.4rem; line-height: 1.8; background: var(--gris-oscuro); padding: 1.5rem; border-radius: 6px; border: 1px solid var(--gris-claro); margin-bottom: 2rem;">
                <?php the_content(); ?>
            </div>
        <?php endif; ?>
    </div>
<?php
endwhile;
?>
