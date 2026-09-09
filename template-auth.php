<?php
/*
 * Template Name: تسجيل الدخول والتسجيل - النقابة
 */

// إذا كان مسجلاً بالفعل، يتم تحويله لصفحة البروفايل
if (is_user_logged_in()) {
    wp_redirect(home_url('/profile/'));
    exit;
}

$auth_error = '';
$auth_success = '';

// 1. معالجة تسجيل الدخول
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sr_login_submit'])) {
    if (!isset($_POST['sr_auth_nonce']) || !wp_verify_nonce($_POST['sr_auth_nonce'], 'sr_auth_action')) {
        $auth_error = 'فشل التحقق الأمني، أعد المحاولة.';
    } else {
        $creds = array(
            'user_login'    => sanitize_text_field($_POST['log_username']),
            'user_password' => $_POST['log_password'],
            'remember'      => isset($_POST['log_remember'])
        );
        $user = wp_signon($creds, false);

        if (is_wp_error($user)) {
            $auth_error = 'اسم المستخدم أو كلمة المرور غير صحيحة!';
        } else {
            wp_redirect(home_url('/profile/'));
            exit;
        }
    }
}

// 2. معالجة إنشاء حساب جديد
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sr_register_submit'])) {
    if (!isset($_POST['sr_auth_nonce']) || !wp_verify_nonce($_POST['sr_auth_nonce'], 'sr_auth_action')) {
        $auth_error = 'فشل التحقق الأمني، أعد المحاولة.';
    } else {
        $username = sanitize_user($_POST['reg_username']);
        $email    = sanitize_email($_POST['reg_email']);
        $password = $_POST['reg_password'];

        if (empty($username) || empty($email) || empty($password)) {
            $auth_error = 'يرجى ملء جميع الحقول المطلوبة!';
        } elseif (username_exists($username)) {
            $auth_error = 'اسم المستخدم هذا محجوز مسبقاً، اختر اسماً آخر.';
        } elseif (email_exists($email)) {
            $auth_error = 'هذا البريد الإلكتروني مسجل بالفعل!';
        } elseif (strlen($password) < 6) {
            $auth_error = 'يجب ألا تقل كلمة المرور عن 6 خانات.';
        } else {
            $new_user_id = wp_create_user($username, $password, $email);
            if (!is_wp_error($new_user_id)) {
                // تسجيل الدخول التلقائي فور إنشاء الحساب
                wp_set_current_user($new_user_id);
                wp_set_auth_cookie($new_user_id);
                wp_redirect(home_url('/profile/'));
                exit;
            } else {
                $auth_error = 'حدث خطأ أثناء إنشاء الحساب، حاول مجدداً.';
            }
        }
    }
}

get_header();
?>

<main class="contenedor">
    <div class="sr-auth-box">
        
        <!-- التبويبات -->
        <div class="sr-auth-tabs">
            <button type="button" class="tab-btn active" onclick="switchAuthTab('login')">تسجيل الدخول</button>
            <button type="button" class="tab-btn" onclick="switchAuthTab('register')">عضو جديد (انضم للنقابة)</button>
        </div>

        <?php if (!empty($auth_error)): ?>
            <div class="sr-alert sr-error"><?php echo esc_html($auth_error); ?></div>
        <?php endif; ?>

        <!-- نموذج تسجيل الدخول -->
        <form id="form-login" method="POST" class="auth-form active">
            <?php wp_nonce_field('sr_auth_action', 'sr_auth_nonce'); ?>
            
            <div class="sr-row">
                <label>اسم المستخدم أو البريد الإلكتروني:</label>
                <input type="text" name="log_username" class="sr-input" required placeholder="اسم المستخدم">
            </div>

            <div class="sr-row">
                <label>كلمة المرور:</label>
                <input type="password" name="log_password" class="sr-input" required placeholder="••••••••">
            </div>

            <div class="sr-row remember-row">
                <label><input type="checkbox" name="log_remember" value="1" checked> تذكر دخولي</label>
            </div>

            <button type="submit" name="sr_login_submit" class="btn-primary-submit">دخول إلى الحساب</button>
        </form>

        <!-- نموذج إنشاء حساب جديد -->
        <form id="form-register" method="POST" class="auth-form">
            <?php wp_nonce_field('sr_auth_action', 'sr_auth_nonce'); ?>

            <div class="sr-row">
                <label>اسم المستخدم (بالإنجليزية وبدون مسافات):</label>
                <input type="text" name="reg_username" class="sr-input" required placeholder="Username">
            </div>

            <div class="sr-row">
                <label>البريد الإلكتروني:</label>
                <input type="email" name="reg_email" class="sr-input" required placeholder="example@mail.com">
            </div>

            <div class="sr-row">
                <label>كلمة المرور (6 خانات على الأقل):</label>
                <input type="password" name="reg_password" class="sr-input" required placeholder="••••••••">
            </div>

            <button type="submit" name="sr_register_submit" class="btn-primary-submit">إنشاء حساب جديد</button>
        </form>

    </div>
</main>

<script>
function switchAuthTab(tab) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.auth-form').forEach(f => f.classList.remove('active'));

    if (tab === 'login') {
        document.querySelectorAll('.tab-btn')[0].classList.add('active');
        document.getElementById('form-login').classList.add('active');
    } else {
        document.querySelectorAll('.tab-btn')[1].classList.add('active');
        document.getElementById('form-register').classList.add('active');
    }
}
</script>

<?php get_footer(); ?>
