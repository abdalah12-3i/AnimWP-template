<?php
/**
 * قائمة الحلقات والبطاقات - نقابة Soul Reapers
 */
?>
<ul class="cap_list_grid">
    <?php
        $args = array(
            'post_type'      => 'animwp_capitulos',
            'posts_per_page' => 16,
            'post_status'    => 'publish'
        );

        $capitulos = new WP_Query($args);

        if ($capitulos->have_posts()):
            while($capitulos->have_posts()): $capitulos->the_post();
                // فحص الصورة سواء كانت بارزة أو عبر حقل cover
                $thumb_url = '';
                if (has_post_thumbnail()) {
                    $thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                } elseif (function_exists('get_field') && get_field('cover')) {
                    $thumb_url = get_field('cover');
                }
    ?>
        <li>
            <a class="card" href="<?php the_permalink(); ?>">
                <?php if (!empty($thumb_url)): ?>
                    <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php the_title_attribute(); ?>">
                <?php else: ?>
                    <div class="no-thumb" style="width: 100%; height: 25rem; background: #202020; display: flex; align-items: center; justify-content: center; color: #777; border-radius: 0.5rem;">
                        حلقة
                    </div>
                <?php endif; ?>

                <div class="contenido">
                    <?php the_title('<p class="card_title">', '</p>'); ?>
                </div>
            </a>
        </li>
    <?php
            endwhile;
            wp_reset_postdata();
        else:
    ?>
        <p class="sr-alert sr-warning" style="grid-column: 1 / -1; width: 100%;">
            لا توجد حلقات منشورة حالياً في هذه القائمة.
        </p>
    <?php endif; ?>
</ul>
