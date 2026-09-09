<?php
/*
 * Template Name: إضافة عمل جديد - النقابة
 */

if (!sr_can_user_publish()) {
    wp_die('عذراً، يجب أن تملك صلاحية ناشر للوصول لهذه الصفحة.');
}

$notice = '';
$user_id = get_current_user_id();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sr_add_anime_submit'])) {
    if (!isset($_POST['sr_anime_nonce']) || !wp_verify_nonce($_POST['sr_anime_nonce'], 'sr_add_anime_action')) {
        $notice = '<div class="sr-alert sr-error">خطأ في التحقق الأمني.</div>';
    } else {
        $anime_title  = sanitize_text_field($_POST['anime_title']);
        $anime_story  = sanitize_textarea_field($_POST['anime_story']);
        $anime_status = sanitize_text_field($_POST['anime_status']);
        
        $ep_number    = sanitize_text_field($_POST['ep_number']);
        $ep_title     = sanitize_text_field($_POST['ep_title']);

        // شرط إلزامي: لا يضاف العمل بدون الحلقة الأولى
        if (empty($anime_title) || empty($ep_number)) {
            $notice = '<div class="sr-alert sr-error">شرط إلزامي: يجب إدخال اسم العمل وبيانات الحلقة الأولى معاً لحفظ العمل!</div>';
        } else {
            // 1. إنشاء العمل
            $anime_post_id = wp_insert_post(array(
                'post_title'   => $anime_title,
                'post_content' => $anime_story,
                'post_type'    => 'animwp_serie',
                'post_status'  => 'publish',
                'post_author'  => $user_id
            ));

            if (!is_wp_error($anime_post_id)) {
                update_post_meta($anime_post_id, 'anime_status', $anime_status);

                // إنشاء تصنيف الأنمي
                $term = wp_insert_term($anime_title, 'anime');
                $term_id = !is_wp_error($term) ? $term['term_id'] : (isset($term->error_data['term_exists']) ? $term->error_data['term_exists'] : 0);
                if ($term_id) {
                    wp_set_object_terms($anime_post_id, array((int)$term_id), 'anime');
                }

                // رفع بوستر العمل
                if (!empty($_FILES['anime_poster']['name'])) {
                    require_once(ABSPATH . 'wp-admin/includes/image.php');
                    require_once(ABSPATH . 'wp-admin/includes/file.php');
                    require_once(ABSPATH . 'wp-admin/includes/media.php');
                    $poster_id = media_handle_upload('anime_poster', $anime_post_id);
                    if (!is_wp_error($poster_id)) {
                        set_post_thumbnail($anime_post_id, $poster_id);
                    }
                }

                // 2. تجميع سيرفرات المشاهدة المعتمدة
                $watch_servers = array();
                if (!empty($_POST['watch_mp4']))      $watch_servers['سيرفر النقابة (MP4)'] = esc_url_raw($_POST['watch_mp4']);
                if (!empty($_POST['watch_telegram'])) $watch_servers['Telegram']            = esc_url_raw($_POST['watch_telegram']);
                if (!empty($_POST['watch_mega']))     $watch_servers['MEGA']                = esc_url_raw($_POST['watch_mega']);

                // 3. مراكز التحميل
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

                // 4. إنشاء ونشر الحلقة الأولى
                $ep_full_title = $anime_title . ' - الحلقة ' . $ep_number . (!empty($ep_title) ? ' (' . $ep_title . ')' : '');
                $episode_id = wp_insert_post(array(
                    'post_title'   => $ep_full_title,
                    'post_type'    => 'animwp_capitulos',
                    'post_status'  => 'publish',
                    'post_author'  => $user_id
                ));

                if (!is_wp_error($episode_id)) {
                    if ($term_id) wp_set_object_terms($episode_id, array((int)$term_id), 'anime');
                    update_post_meta($episode_id, 'servidores', $watch_servers);
                    update_post_meta($episode_id, 'downloads', $downloads);
                    update_post_meta($episode_id, 'episode_number', $ep_number);
                    update_post_meta($episode_id, 'parent_anime_id', $anime_post_id);

                    $notice = '<div class="sr-alert sr-success">تمت إضافة العمل والحلقة الأولى بنجاح! <a href="' . esc_url(get_permalink($anime_post_id)) . '">عرض العمل</a> | <a href="' . esc_url(get_permalink($episode_id)) . '">مشاهدة الحلقة</a></div>';
                }
            }
        }
    }
}

get_header();
?>

<main class="contenedor">
    <div class="sr-form-wrapper">
        <h2>✨ إضافة عمل جديد إلى النقابة</h2>
        <p class="sr-subtitle">ملاحظة: يجب ملء بيانات العمل بالإضافة إلى بيانات الحلقة الأولى معاً لنشر العمل.</p>

        <?php echo $notice; ?>

        <form method="POST" enctype="multipart/form-data">
            <?php wp_nonce_field('sr_add_anime_action', 'sr_anime_nonce'); ?>

            <!-- معطيات العمل -->
            <fieldset class="sr-box">
                <legend>1. معطيات العمل الأساسية</legend>
                
                <div class="sr-row">
                    <label>اسم العمل (الأنمي) *</label>
                    <input type="text" name="anime_title" class="sr-input" required placeholder="مثال: Solo Leveling">
                </div>

                <div class="sr-row">
                    <label>حالة العمل</label>
                    <select name="anime_status" class="sr-input">
                        <option value="مستمر">مستمر</option>
                        <option value="مكتمل">مكتمل</option>
                        <option value="قادم قريباً">قادم قريباً</option>
                    </select>
                </div>

                <div class="sr-row">
                    <label>بوستر العمل (صورة الغلاف)</label>
                    <input type="file" name="anime_poster" accept="image/*" class="sr-input">
                </div>

                <div class="sr-row">
                    <label>قصة العمل / النبذة</label>
                    <textarea name="anime_story" class="sr-textarea" rows="4" placeholder="اكتب نبذة عن القصة..."></textarea>
                </div>
            </fieldset>

            <!-- الحلقة الأولى الإلزامية -->
            <fieldset class="sr-box highlight-box">
                <legend>2. بيانات الحلقة الأولى (إلزامية)</legend>

                <div class="sr-grid-2">
                    <div class="sr-row">
                        <label>رقم الحلقة *</label>
                        <input type="text" name="ep_number" class="sr-input" value="1" required>
                    </div>
                    <div class="sr-row">
                        <label>عنوان الحلقة (إن وجد)</label>
                        <input type="text" name="ep_title" class="sr-input" placeholder="اختياري">
                    </div>
                </div>

                <!-- سيرفرات المشاهدة المعتمدة فقط -->
                <div class="sr-sub-box">
                    <h4>📺 المشاهدة المباشرة:</h4>
                    <div class="sr-row">
                        <label>سيرفر النقابة المباشر (رابط MP4 المجاني):</label>
                        <input type="url" name="watch_mp4" class="sr-input" placeholder="https://cdn.myserver.com/free/ep1.mp4">
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

                    <div id="anime-dl-others"></div>
                    <button type="button" class="btn-add-other" onclick="addOtherAnimeDl()">+ غيرها (إضافة مركز تحميل إضافي)</button>
                </div>
            </fieldset>

            <button type="submit" name="sr_add_anime_submit" class="btn-primary-submit">حفظ ونشر العمل مع الحلقة الأولى الآن</button>
        </form>
    </div>
</main>

<script>
function addOtherAnimeDl() {
    const container = document.getElementById('anime-dl-others');
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
