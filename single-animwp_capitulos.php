<?php 
get_header(); 
$ep_id = get_the_ID();

// جلب سيرفرات المشاهدة وروابط التحميل
$watch_servers = get_post_meta($ep_id, 'servidores', true);
if (!is_array($watch_servers)) $watch_servers = array();

$downloads = get_post_meta($ep_id, 'downloads', true);
if (!is_array($downloads)) $downloads = array();

// تصفية وحذف أي رابط فارغ حتى لا يظهر للزائر
$filtered_servers = array_filter($watch_servers, function($url) {
    return !empty($url);
});

$filtered_downloads = array_filter($downloads, function($url) {
    return !empty($url);
});

$default_server = !empty($filtered_servers) ? reset($filtered_servers) : '';
?>

<main class="contenedor">
    <article class="episode-view">
        <h1 class="episode-main-title"><?php the_title(); ?></h1>

        <!-- مشغل وسيرفرات المشاهدة -->
        <?php if (!empty($filtered_servers)): ?>
            <div class="watch-container">
                <div class="servers-nav-wrap">
                    <span class="lbl">اختر سيرفر المشاهدة:</span>
                    <div class="server-buttons">
                        <?php 
                        $first = true;
                        foreach ($filtered_servers as $s_name => $s_link): 
                        ?>
                            <button type="button" class="btn-server-item <?php echo $first ? 'active' : ''; ?>" data-src="<?php echo esc_url($s_link); ?>">
                                <?php echo esc_html($s_name); ?>
                            </button>
                        <?php 
                            $first = false;
                        endforeach; 
                        ?>
                    </div>
                </div>

                <div class="player-box">
                    <iframe id="main-player" src="<?php echo esc_url($default_server); ?>" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>
        <?php else: ?>
            <p class="sr-alert sr-warning">لم يتم إضافة سيرفرات مشاهدة لهذه الحلقة بعد.</p>
        <?php endif; ?>

        <!-- قسم التحميل (يختفي تماماً إن لم تكن هناك روابط) -->
        <?php if (!empty($filtered_downloads)): ?>
            <div class="download-section-box">
                <h3 class="dl-heading">📥 روابط التحميل المتاحة:</h3>
                <div class="download-links-grid">
                    <?php foreach ($filtered_downloads as $d_name => $d_link): ?>
                        <a href="<?php echo esc_url($d_link); ?>" target="_blank" rel="noopener noreferrer" class="btn-dl-badge">
                            تحميل عبر <strong><?php echo esc_html($d_name); ?></strong> ⬇
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- رابط الرجوع للأنمي -->
        <div class="back-to-anime-wrap" style="margin-top: 25px;">
            <?php
                $parent_id = get_post_meta($ep_id, 'parent_anime_id', true);
                if ($parent_id):
            ?>
                <a href="<?php echo esc_url(get_permalink($parent_id)); ?>" class="btn-back-anime">
                    ← العودة لصفحة الأنمي وقائمة جميع الحلقات
                </a>
            <?php endif; ?>
        </div>

    </article>
</main>

<script>
document.querySelectorAll('.btn-server-item').forEach(button => {
    button.addEventListener('click', function() {
        document.querySelectorAll('.btn-server-item').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        document.getElementById('main-player').src = this.getAttribute('data-src');
    });
});
</script>

<?php get_footer(); ?>
