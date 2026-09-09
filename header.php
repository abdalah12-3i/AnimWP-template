<!DOCTYPE html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title('|', true, 'right'); ?> <?php bloginfo('name'); ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Changa:wght@700;900&family=Cairo:wght@400;600;700;900&family=Montserrat:wght@800&display=swap" rel="stylesheet">

    <?php wp_head(); ?>

    <!-- تنسيق حاصد الأرواح المباشر لفرض الألوان البنفسجية وقوائم الدروب داون -->
    <style>
        :root {
            --sr-purple: #8a2be2;
            --sr-purple-dark: #5a189a;
            --sr-blue-aura: #00b4d8;
            --sr-glow: rgba(0, 180, 216, 0.5);
            --sr-bg: #090a0f;
            --sr-card: #12131c;
            --sr-border: #232536;
        }

        body {
            background-color: var(--sr-bg) !important;
            color: #ffffff !important;
            font-family: 'Cairo', sans-serif !important;
            direction: rtl;
        }

        /* لوغو النقابة بنفسجي حاصد الأرواح مع توهج أزرق داكن */
        .site-logo .logo-ar {
            font-family: 'Changa', sans-serif !important;
            font-size: 3.8rem !important;
            font-weight: 900 !important;
            background: linear-gradient(180deg, #d88aff 0%, #9d4edd 45%, #7b2cbf 75%, #00b4d8 100%) !important;
            -webkit-background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
            filter: drop-shadow(0 2px 4px #000) drop-shadow(0 0 12px var(--sr-glow)) !important;
        }
        .site-logo .logo-en {
            font-family: 'Montserrat', sans-serif !important;
            font-size: 0.9rem !important;
            letter-spacing: 4px !important;
            color: var(--sr-blue-aura) !important;
            text-shadow: 0 0 8px var(--sr-glow) !important;
        }

        /* شريط التنقل */
        .site-navbar {
            background: #050608 !important;
            border-bottom: 2px solid var(--sr-border) !important;
            padding: 10px 0 !important;
            position: sticky;
            top: 0;
            z-index: 999999;
        }
        .navegacion {
            display: flex !important;
            flex-direction: row !important;
            align-items: center !important;
            justify-content: space-between !important;
        }

        /* شريط البحث */
        .header-search .search-form {
            background: var(--sr-card) !important;
            border: 1px solid var(--sr-border) !important;
            border-radius: 20px !important;
            padding: 4px 12px !important;
        }
        .header-search .search-form:focus-within {
            border-color: var(--sr-blue-aura) !important;
            box-shadow: 0 0 10px var(--sr-glow) !important;
        }
        .header-search .search-field {
            color: #fff !important;
            font-family: 'Cairo', sans-serif !important;
        }

        /* الأزرار والقوائم المنسدلة (حل مشكلة ظهور الروابط مفرودة) */
        .enlaces {
            display: flex !important;
            align-items: center !important;
            gap: 15px !important;
        }
        .dropdown-wrapper {
            position: relative !important;
            display: inline-block !important;
        }
        .btn-action-trigger {
            display: inline-flex !important;
            align-items: center !important;
            gap: 8px !important;
            padding: 8px 16px !important;
            border-radius: 6px !important;
            font-family: 'Cairo', sans-serif !important;
            font-weight: 700 !important;
            font-size: 14px !important;
            cursor: pointer !important;
            border: none !important;
        }
        .btn-publish {
            background: linear-gradient(135deg, #7b2cbf, #5a189a) !important;
            color: #fff !important;
            border: 1px solid rgba(0, 180, 216, 0.4) !important;
            box-shadow: 0 0 10px rgba(123, 44, 191, 0.4) !important;
        }
        .btn-publish:hover {
            box-shadow: 0 0 15px var(--sr-glow) !important;
            background: linear-gradient(135deg, #9d4edd, #7b2cbf) !important;
        }
        .btn-user {
            background: var(--sr-card) !important;
            color: #eee !important;
            border: 1px solid var(--sr-border) !important;
        }
        .btn-user:hover {
            border-color: var(--sr-blue-aura) !important;
        }
        .nav-avatar img {
            border-radius: 50% !important;
            border: 2px solid var(--sr-blue-aura) !important;
        }

        /* القائمة المنسدلة: إخفاؤها تماماً وجعلها تظهر كقائمة منسدلة عمودية فقط */
        .dropdown-content {
            display: none !important;
            position: absolute !important;
            top: 100% !important;
            left: 0 !important;
            background: #0e0f17 !important;
            border: 1px solid var(--sr-purple) !important;
            min-width: 170px !important;
            border-radius: 8px !important;
            box-shadow: 0 10px 25px rgba(0,0,0,0.9), 0 0 15px rgba(138, 43, 226, 0.3) !important;
            z-index: 9999999 !important;
            margin-top: 6px !important;
            overflow: hidden !important;
            flex-direction: column !important;
        }
        .dropdown-wrapper:hover .dropdown-content,
        .dropdown-wrapper.active .dropdown-content {
            display: flex !important;
        }
        .dropdown-content a {
            display: block !important;
            padding: 12px 16px !important;
            color: #ddd !important;
            font-size: 13px !important;
            border-bottom: 1px solid #1a1c26 !important;
            text-align: right !important;
            text-decoration: none !important;
            transition: 0.2s !important;
        }
        .dropdown-content a:hover {
            background: var(--sr-purple) !important;
            color: #fff !important;
        }
        .logout-item { color: #ff5252 !important; }
    </style>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

    <header class="site-navbar">
        <div class="contenedor navegacion">
            
            <!-- اللوغو البنفسجي بتوهج الروح الأزرق -->
            <div class="logo">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo">
                    <span class="logo-ar">النّقـابـة</span>
                    <span class="logo-en">SOUL REAPERS</span>
                </a>
            </div>

            <!-- البحث السريع -->
            <div class="header-search">
                <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                    <input type="search" class="search-field" placeholder="ابحث عن أنمي أو حلقة..." value="<?php echo get_search_query(); ?>" name="s" autocomplete="off" />
                    <button type="submit" class="search-submit" title="بحث">🔍</button>
                </form>
            </div>

            <!-- الأزرار والقوائم المنسدلة -->
            <div class="enlaces">
                <!-- زر وقائمة النشر -->
                <?php if (sr_can_user_publish()): ?>
                    <div class="dropdown-wrapper">
                        <button type="button" class="btn-action-trigger btn-publish">
                            <span>نشر ▾</span>
                        </button>
                        <div class="dropdown-content">
                            <a href="<?php echo esc_url(home_url('/add-episode/')); ?>">🎬 نشر حلقة جديدة</a>
                            <a href="<?php echo esc_url(home_url('/add-anime/')); ?>">✨ إضافة عمل جديد</a>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- زر وقائمة الحساب -->
                <?php if (is_user_logged_in()): 
                    $current_user = wp_get_current_user();
                ?>
                    <div class="dropdown-wrapper">
                        <button type="button" class="btn-action-trigger btn-user">
                            <span class="nav-avatar"><?php echo get_avatar($current_user->ID, 28); ?></span>
                            <span class="nav-username"><?php echo esc_html($current_user->display_name); ?> ▾</span>
                        </button>
                        <div class="dropdown-content">
                            <a href="<?php echo esc_url(home_url('/profile/')); ?>">👤 بروفايلي</a>
                            <?php if (sr_can_user_publish()): ?>
                                <a href="<?php echo esc_url(home_url('/add-episode/')); ?>">🎬 رفع حلقة</a>
                            <?php endif; ?>
                            <a href="<?php echo wp_logout_url(home_url()); ?>" class="logout-item">🚪 تسجيل الخروج</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?php echo esc_url(home_url('/login/')); ?>" class="btn-action-trigger btn-publish" style="text-decoration:none;">
                        تسجيل الدخول
                    </a>
                <?php endif; ?>
            </div>

        </div>
    </header>
