<?php
/*
 * Template Name: نشر حلقة - النقابة
 */

if (!sr_can_user_publish()) {
    wp_die('عذراً، يجب أن تملك صلاحية ناشر لنشر الحلقات.');
}

$user_id = get_current_user_id();
$notice = '';

// جلب الأعمال المضافة بواسطة هذا الناشر فقط
$user_animes = get_posts(array(
    'post_type'      => 'animwp_serie',
    'post_status'    => 'publish',
    'author'         => $user_id,
    'posts_per_page' => -1
));

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sr_submit_episode'])) {
    if (!isset($_POST['sr_ep_nonce']) || !wp_verify_nonce($_POST['sr_ep_nonce'], 'sr_ep_action')) {
        $notice = '<div class="sr-alert sr-error">خطأ أمني، يرجى التحديث والمحاولة.</div>';
    } else {
        $selected_anime_id = intval($_POST['anime_id']);
        $ep_number         = sanitize_text_field($_POST['ep_number']);
        $ep_title          = sanitize_text_field($_POST['ep_title']);

        if (empty($selected_anime_id) || empty($ep_number)) {
            $notice = '<div class="sr-alert sr-error">يرجى اختيار العمل وتحديد رقم الحلقة.</div>';
        } else {
            $anime_name = get_the_title($selected_anime_id);
            $full_title = $anime_name . ' - الحلقة ' . $ep_number . (!empty($ep_title) ? ' (' . $ep_title . ')' : '');

            // 1. سيرفرات المشاهدة المعتمدة فقط (إخفاء الفارغ تلقائياً)
            $watch_servers = array();
            if (!empty($_POST['watch_mp4']))      $watch_servers['سيرفر النقابة (MP4)'] = esc_url_raw($_POST['watch_mp4']);
            if (!empty($_POST['watch_telegram'])) $watch_servers['Telegram']            = esc_url_raw($_POST['watch_telegram']);
            if (!empty($_POST['watch_mega']))     $watch_servers['MEGA']                = esc_url_raw($_POST['watch_mega']);

            // 2. مراكز التحميل
            $downloads = array();
            if (!empty($_POST['dl_mega']))    $downloads['MEGA']    = esc_url_raw($_POST['dl_mega']);
            if (!empty($_POST['dl_ddl']))     $downloads['DDL']     = esc_url_raw($_POST['dl_ddl']);
            if (!empty($_POST['dl_torrent'])) $downloads['Torrent'] = esc_url_raw($_POST['dl_torrent']);

            if (!empty($_POST['dl_custom_name']) && is_array($_POST['dl_custom_name'])) {
                foreach ($_POST['dl_custom_name'] as $idx => $d_name) {
                    $d_url = $_POST['dl_custom_url'][$idx] ?? '';
                    if (!empty($d_name) && !empty($d_url)) {
                        $downloads[sanitize_text_field($d_name)] = esc_url_raw($d_url);
                    }
                }
            }

            // 3. نشر الحلقة
            $ep_id = wp_insert_post(array(
                'post_title'   => $full_title,
                'post_type'    => 'animwp_capitulos',
                'post_status'  => 'publish',
                'post_author'  => $user_id
            ));

            if (!is_wp_error($ep_id)) {
                $terms = wp_get_post_terms($selected_anime_id, 'anime');
                if (!empty($terms) && !is_wp_error($terms)) {
                    wp_set_object_terms($ep_id, array((int)$terms[0]->term_id), 'anime');
                }

                update_post_meta($ep_id, 'servidores', $watch_servers);
                update_post_meta($ep_id, 'downloads', $downloads);
                update_post_meta($ep_id, 'episode_number', $ep_number);
                update_post_meta($ep_id, 'parent_anime_id', $selected_anime_id);

                $notice = '<div class="sr-alert sr-success">تم نشر الحلقة بنجاح! <a href="' . esc_url(get_permalink($ep_id)) . '">مشاهدة الحلقة</a></div>';
            }
        }
    }
}

get_header();
?>

<main class="contenedor">
    <div class="sr-form-wrapper">
        <h2>🎬 نشر حلقة جديدة</h2>

        <?php echo $notice; ?>

        <?php if (empty($user_animes)): ?>
            <div class="sr-alert sr-warning">
                لم تقم بإضافة أي عمل خاص بك بعد! يجب <a href="<?php echo esc_url(home_url('/add-anime/')); ?>">إضافة عمل أولاً</a> لتتمكن من نشر حلقاته.
            </div>
        <?php else: ?>
            <form method="POST">
                <?php wp_nonce_field('sr_ep_action', 'sr_ep_nonce'); ?>

                <div class="sr-box">
                    <!-- اختيار العمل -->
                    <div class="sr-row">
                        <label>العمل التابع له (من أعمالك المضافة):</label>
                        <?php if (count($user_animes) === 1): ?>
                            <!-- اختيار تلقائي فوري إن كان يملك عملاً واحداً -->
                            <input type="text" class="sr-input" value="<?php echo esc_attr($user_animes[0]->post_title); ?>" readonly>
                            <input type="hidden" name="anime_id" value="<?php echo esc_attr($user_animes[0]->ID); ?>">
                        <?php else: ?>
                            <select name="anime_id" class="sr-input" required>
                                <option value="">-- اختر العمل من قائمتك --</option>
                                <?php foreach ($user_animes as $anime): ?>
                                    <option value="<?php echo esc_attr($anime->ID); ?>"><?php echo esc_html($anime->post_title); ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    </div>

                    <div class="sr-grid-2">
                        <div class="sr-row">
                            <label>رقم الحلقة *</label>
                            <input type="text" name="ep_number" class="sr-input" placeholder="مثال: 02" required>
                        </div>
                        <div class="sr-row">
                            <label>عنوان الحلقة (إن وجد)</label>
                            <input type="text" name="ep_title" class="sr-input" placeholder="اختياري">
                        </div>
                    </div>

                    <!-- سيرفرات المشاهدة المعتمدة فقط -->
                    <div class="sr-sub-box">
                        <h4>📺 سيرفرات المشاهدة المباشرة:</h4>
                        
                        <div class="sr-row">
                            <label>سيرفر النقابة المباشر (رابط MP4 المجاني):</label>
                            <input type="url" name="watch_mp4" class="sr-input" placeholder="https://cdn.myserver.com/episode.mp4">
                        </div>

                        <div class="sr-row">
                            <label>رابط Telegram:</label>
                            <input type="url" name="watch_telegram" class="sr-input" placeholder="https://t.me/...">
                        </div>

                        <div class="sr-row">
                            <label>رابط MEGA:</label>
                            <input type="url" name="watch_mega" class="sr-input" placeholder="https://mega.nz/embed/...">
                        </div>
                    </div>

                    <!-- مراكز التحميل -->
                    <div class="sr-sub-box">
                        <h4>📥 مراكز التحميل:</h4>
                        
                        <div class="sr-row">
                            <label>تحميل MEGA:</label>
                            <input type="url" name="dl_mega" class="sr-input" placeholder="https://mega.nz/file/...">
                        </div>

                        <div class="sr-row">
                            <label>تحميل مباشر DDL:</label>
                            <input type="url" name="dl_ddl" class="sr-input" placeholder="https://...">
                        </div>

                        <div class="sr-row">
                            <label>ملف تورنت Torrent:</label>
                            <input type="url" name="dl_torrent" class="sr-input" placeholder="https://...">
                        </div>

                        <div id="ep-dl-others"></div>
                        <button type="button" class="btn-add-other" onclick="addOtherEpisodeDl()">+ غيرها (إضافة مركز تحميل إضافي)</button>
                    </div>

                    <button type="submit" name="sr_submit_episode" class="btn-primary-submit">نشر الحلقة الآن</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</main>

<script>
function addOtherEpisodeDl() {
    const container = document.getElementById('ep-dl-others');
    const div = document.createElement('div');
    div.className = 'sr-custom-row';
    div.innerHTML = `
        <input type="text" name="dl_custom_name[]" class="sr-input" placeholder="اسم المركز (مثلاً: Mediafire / Google Drive)" style="flex: 1;">
        <input type="url" name="dl_custom_url[]" class="sr-input" placeholder="رابط التحميل" style="flex: 2;">
        <button type="button" class="btn-remove-row" onclick="this.parentElement.remove()">✕</button>
    `;
    container.appendChild(div);
}
</script>

<?php get_footer(); ?>
