<?php get_header(); ?>

<main class="contenedor con-sidebar">
    <section class="seccion seccion-capitulo">
        
        <!-- تفاصيل الحلقة ورقمها -->
        <?php get_template_part('template-parts/capitulo'); ?>

        <div class="player-container">
            <?php 
                $servers = function_exists('get_fields') ? get_fields() : array();
                $servidores = isset($servers['servidores']) && is_array($servers['servidores']) ? $servers['servidores'] : array();
                
                // جلب أول سيرفر افتراضياً
                $default = !empty($servidores) ? reset($servidores) : '';
            ?>

            <!-- أزرار تبديل السيرفرات -->
            <?php if(!empty($servidores)): ?>
                <div class="server-header">
                    <span class="server-title">اختر سيرفر المشاهدة:</span>
                    <nav class="server-nav">
                        <?php 
                            $i = 1;
                            foreach($servidores as $nombre => $link): 
                                if(empty($link)) continue;
                                $active_class = ($i === 1) ? 'active' : '';
                        ?>
                            <button type="button" class="btn btn-server <?php echo $active_class; ?>" data-enlace="<?php echo esc_url($link); ?>">
                                <?php echo is_string($nombre) && !is_numeric($nombre) ? esc_html($nombre) : 'سيرفر ' . $i; ?>
                            </button>
                        <?php 
                            $i++;
                            endforeach; 
                        ?>
                    </nav>
                </div>
            <?php endif; ?>

            <!-- مشغل الفيديو -->
            <div class="video-responsive">
                <?php if(!empty($default)): ?>
                    <iframe id="iframe" class="iframe" src="<?php echo esc_url($default); ?>" frameborder="0" allowfullscreen></iframe>
                <?php else: ?>
                    <div class="no-video">
                        <p>⚠️ لم يتم إضافة سيرفرات مشاهدة لهذه الحلقة بعد.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- أزرار التنقل السريع -->
            <div class="nav-capitulos">
                <?php
                    $terms = get_the_terms(get_the_ID(), 'anime');
                    if($terms && !is_wp_error($terms)):
                        $anime_term = reset($terms);
                ?>
                    <a href="<?php echo esc_url(get_term_link($anime_term)); ?>" class="btn btn-anime">
                        قائمة جميع حلقات (<?php echo esc_html($anime_term->name); ?>)
                    </a>
                <?php endif; ?>
            </div>
        </div>

    </section>
</main>

<?php get_footer(); ?>
