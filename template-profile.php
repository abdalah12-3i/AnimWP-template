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

// معالجة تحديث بيانات الحساب
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

        if (empty($update_msg)) {
            wp_update_user($userdata);
            $current_user = wp_get_current_user(); // تحديث المتغير
            $update_msg = '<div class="sr-alert sr-success">تم حفظ وتحديث بيانات حسابك بنجاح!</div>';
        }
    }
}

// تحديد رتبة العضو في النقابة بالعربي
$role_name = 'عضو في النقابة';
if (in_array('administrator', $current_user->roles)) {
    $role_name = '👑 قائد النقابة (مدير)';
} elseif (in_array('guild_publisher', $current_user->roles)) {
    $role_name = '⚔️ ناشر ومترجم معتمد';
}

// إحصائيات الناشر (كم عمل وحلقة نشر)
$animes_count = count_user_posts($user_id, 'animwp_serie');
$episodes_count = count_user_posts($user_id, 'animwp_capitulos');

get_header();
?>

<main class="contenedor">
    <div class="sr-profile-wrapper">
        
        <!-- بطاقة بيانات العضو -->
        <div class="sr-profile-card">
            <div class="profile-avatar">
                <?php echo get_avatar($user_id, 100); ?>
            </div>
            <div class="profile-info">
                <h2><?php echo esc_html($current_user->display_name); ?></h2>
                <span class="user-role-badge"><?php echo esc_html($role_name); ?></span>
                <p class="user-email"><?php echo esc_html($current_user->user_email); ?></p>
            </div>
            
            <div class="profile-stats">
                <div class="stat-box">
                    <span class="num"><?php echo $animes_count; ?></span>
                    <span class="lbl">أعمال مضافة</span>
                </div>
                <div class="stat-box">
                    <span class="num"><?php echo $episodes_count; ?></span>
                    <span class="lbl">حلقات منشورة</span>
                </div>
            </div>
        </div>

        <?php echo $update_msg; ?>

        <!-- نموذج تعديل الملف الشخصي -->
        <div class="sr-box">
            <h3>⚙️ تعديل بيانات الحساب:</h3>
            <form method="POST">
                <?php wp_nonce_field('sr_profile_action', 'sr_profile_nonce'); ?>

                <div class="sr-row">
                    <label>الاسم الظاهر في الموقع:</label>
                    <input type="text" name="display_name" class="sr-input" value="<?php echo esc_attr($current_user->display_name); ?>" required>
                </div>

                <div class="sr-row">
                    <label>تغيير كلمة المرور (اتركه فارغاً إن لم ترغب في التغيير):</label>
                    <input type="password" name="new_password" class="sr-input" placeholder="كلمة مرور جديدة">
                </div>

                <button type="submit" name="sr_update_profile" class="btn-primary-submit">حفظ التعديلات</button>
            </form>
        </div>

        <!-- أزرار الإجراءات السريعة -->
        <div class="profile-actions">
            <?php if (sr_can_user_publish()): ?>
                <a href="<?php echo esc_url(home_url('/add-episode/')); ?>" class="btn-action-pub">🎬 نشر حلقة الآن</a>
                <a href="<?php echo esc_url(home_url('/add-anime/')); ?>" class="btn-action-pub">✨ إضافة عمل جديد</a>
            <?php endif; ?>
            <a href="<?php echo wp_logout_url(home_url()); ?>" class="btn-action-logout">🚪 تسجيل الخروج</a>
        </div>

    </div>
</main>

<?php get_footer(); ?>
