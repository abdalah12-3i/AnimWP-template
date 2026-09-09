<?php
/*
 * Template Name: بروفايل العضو - النقابة
 */

if (!is_user_logged_in()) {
    wp_redirect(home_url('/login/'));
    exit;
}

$current_user = wp_get_current_user();
$user_id      = $current_user->ID;
$update_msg   = '';

// معالجة تحديث بيانات الحساب والصورة الشخصية
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sr_update_profile'])) {
    if (!isset($_POST['sr_profile_nonce']) || !wp_verify_nonce($_POST['sr_profile_nonce'], 'sr_profile_action')) {
        $update_msg = '<div class="sr-alert sr-error">خطأ في التحقق الأمني.</div>';
    } else {
        $display_name = sanitize_text_field($_POST['display_name']);
        $new_pass     = $_POST['new_password'];

        $userdata = array('ID' => $user_id);
        if (!empty($display_name)) {
            $userdata['display_name'] = $display_name;
        }
        if (!empty($new_pass)) {
            if (strlen($new_pass) >= 6) {
                $userdata['user_pass'] = $new_pass;
            } else {
                $update_msg = '<div class="sr-alert sr-error">كلمة المرور يجب أن لا تقل عن 6 أحرف.</div>';
            }
        }

        // معالجة رفع الصورة الشخصية (Avatar)
        if (!empty($_FILES['user_avatar']['name'])) {
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');

            $avatar_id = media_handle_upload('user_avatar', 0);
            if (!is_wp_error($avatar_id)) {
                update_user_meta($user_id, 'sr_custom_avatar', $avatar_id);
            } else {
                $update_msg = '<div class="sr-alert sr-error">خطأ في رفع الصورة: ' . $avatar_id->get_error_message() . '</div>';
            }
        }

        if (empty($update_msg)) {
            wp_update_user($userdata);
            $current_user = wp_get_current_user();
            $update_msg = '<div class="sr-alert sr-success" style="background: rgba(0, 180, 216, 0.15); border: 1px solid var(--Aura-Blue); color: var(--Aura-Blue); padding: 12px; border-radius: 6px; margin-bottom: 20px;">✓ تم حفظ وتحديث بيانات حسابك وصورتك بنجاح!</div>';
        }
    }
}

// تحديد الرتبة
$role_name = 'عضو في النقابة';
if (in_array('administrator', $current_user->roles)) {
    $role_name = '👑 قائد النقابة (مدير)';
} elseif (in_array('guild_publisher', $current_user->roles)) {
    $role_name = '⚔️ ناشر ومترجم معتمد';
}

$animes_count   = count_user_posts($user_id, 'animwp_serie');
$episodes_count = count_user_posts($user_id, 'animwp_capitulos');

get_header();
?>

<main class="contenedor" style="padding: 40px 0;">
    <div class="sr-profile-wrapper" style="max-width: 750px; margin: 0 auto;">
        
        <!-- بطاقة العضو -->
        <div class="sr-profile-card" style="display: flex; flex-wrap: wrap; align-items: center; gap: 25px; background: var(--gris-medio); border: 1px solid var(--gris-claro); border-radius: 12px; padding: 25px; margin-bottom: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.5);">
            <div class="profile-avatar" style="position: relative;">
                <?php echo get_avatar($user_id, 100); ?>
            </div>
            <div class="profile-info" style="flex: 1;">
                <h2 style="margin: 0 0 10px 0; font-size: 24px; color: #fff;"><?php echo esc_html($current_user->display_name); ?></h2>
                <span class="user-role-badge" style="background: linear-gradient(135deg, #7b2cbf, #5a189a); border: 1px solid rgba(0, 180, 216, 0.4); color: #fff; padding: 4px 12px; border-radius: 4px; font-size: 12px; font-weight: bold; box-shadow: 0 0 10px rgba(123, 44, 191, 0.4);">
                    <?php echo esc_html($role_name); ?>
                </span>
                <p class="user-email" style="color: #888; font-size: 13px; margin: 10px 0 0 0;"><?php echo esc_html($current_user->user_email); ?></p>
            </div>
            
            <div class="profile-stats" style="display: flex; gap: 15px;">
                <div class="stat-box" style="background: #08090d; border: 1px solid var(--gris-claro); padding: 12px 20px; border-radius: 8px; text-align: center;">
                    <span class="num" style="display: block; font-size: 22px; font-weight: 900; color: var(--Aura-Blue);"><?php echo $animes_count; ?></span>
                    <span class="lbl" style="font-size: 12px; color: #aaa;">أعمال مضافة</span>
                </div>
                <div class="stat-box" style="background: #08090d; border: 1px solid var(--gris-claro); padding: 12px 20px; border-radius: 8px; text-align: center;">
                    <span class="num" style="display: block; font-size: 22px; font-weight: 900; color: #c77dff;"><?php echo $episodes_count; ?></span>
                    <span class="lbl" style="font-size: 12px; color: #aaa;">حلقات منشورة</span>
                </div>
            </div>
        </div>

        <?php echo $update_msg; ?>

        <!-- نموذج تعديل الملف الشخصي ورفع الصورة -->
        <div class="sr-box" style="background: var(--gris-medio); border: 1px solid var(--gris-claro); border-radius: 10px; padding: 25px;">
            <h3 style="color: var(--Aura-Blue); margin-bottom: 20px; font-size: 20px;">⚙️ تعديل بيانات الحساب والصورة الشخصية:</h3>
            
            <!-- مهم جداً: enctype="multipart/form-data" لرفع الملفات -->
            <form method="POST" enctype="multipart/form-data">
                <?php wp_nonce_field('sr_profile_action', 'sr_profile_nonce'); ?>

                <!-- حقل رفع الصورة الشخصية -->
                <div class="sr-row" style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 8px; color: #eee;">📷 تغيير الصورة الشخصية (Avatar):</label>
                    <input type="file" name="user_avatar" accept="image/*" class="sr-input" style="padding: 8px; background: #08090d; border: 1px dashed var(--Aura-Blue); cursor: pointer;">
                    <small style="color: #888; display: block; margin-top: 5px;">اختر صورة مربعة (JPG أو PNG) لتظهر كشعار لحسابك في الموقع والهيدر.</small>
                </div>

                <div class="sr-row" style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 8px; color: #eee;">الاسم الظاهر في الموقع:</label>
                    <input type="text" name="display_name" class="sr-input" value="<?php echo esc_attr($current_user->display_name); ?>" required style="width: 100%; padding: 10px; background: #08090d; border: 1px solid var(--gris-claro); border-radius: 6px; color: #fff;">
                </div>

                <div class="sr-row" style="margin-bottom: 25px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 8px; color: #eee;">تغيير كلمة المرور (اتركه فارغاً إن لم ترغب في التغيير):</label>
                    <input type="password" name="new_password" class="sr-input" placeholder="كلمة مرور جديدة" style="width: 100%; padding: 10px; background: #08090d; border: 1px solid var(--gris-claro); border-radius: 6px; color: #fff;">
                </div>

                <button type="submit" name="sr_update_profile" class="btn-primary-submit" style="background: linear-gradient(135deg, #7b2cbf, #5a189a); color: #fff; border: 1px solid rgba(0, 180, 216, 0.4); padding: 12px; width: 100%; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 15px;">
                    حفظ التعديلات والصورة
                </button>
            </form>
        </div>

        <!-- أزرار الإجراءات السريعة -->
        <div class="profile-actions" style="display: flex; gap: 15px; margin-top: 25px; flex-wrap: wrap;">
            <?php if (sr_can_user_publish()): ?>
                <a href="<?php echo esc_url(home_url('/add-episode/')); ?>" style="background: #1c1e2d; color: #fff; padding: 10px 20px; border-radius: 6px; font-weight: bold; text-decoration: none;">🎬 نشر حلقة الآن</a>
                <a href="<?php echo esc_url(home_url('/add-anime/')); ?>" style="background: #1c1e2d; color: #fff; padding: 10px 20px; border-radius: 6px; font-weight: bold; text-decoration: none;">✨ إضافة عمل جديد</a>
            <?php endif; ?>
            <a href="<?php echo wp_logout_url(home_url()); ?>" style="background: #c0392b; color: #fff; padding: 10px 20px; border-radius: 6px; font-weight: bold; margin-right: auto; text-decoration: none;">🚪 تسجيل الخروج</a>
        </div>

    </div>
</main>

<?php get_footer(); ?>
