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

    <!-- تنسيق شامل لنقابة حاصد الأرواح (Soul Reapers) واستقرار القوائم المنسدلة -->
    <style>
        :root {
            /* هوية حاصد الأرواح: بنفسجي داكن + هالة زرقاء متوهجة */
            --Primario: #8a2be2;
            --Primario-hover: #9d4edd;
            --Aura-Blue: #00b4d8;
            --Aura-DarkBlue: #0077b6;
            --Aura-Glow: rgba(0, 180, 216, 0.45);
            --Purple-Glow: rgba(138, 43, 226, 0.45);

            --gris-oscuro: #08090d;
            --gris-medio: #10111a;
            --gris-claro: #1c1e2d;
            --blanco: #ffffff;
            --negro: #040508;
            --texto-secundario: #8f93a7;
        }

        body {
            background-color: var(--gris-oscuro) !important;
            color: var(--blanco) !important;
            font-family: 'Cairo', sans-serif !important;
            direction: rtl;
        }

        /* شريط التمرير بالبنفسجي والتوهج الأزرق */
        * {
            scrollbar-width: thin;
            scrollbar-color: var(--Primario) var(--gris-claro);
        }
        *::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, var(--Primario), var(--Aura-Blue)) !important;
            border-radius: 4px;
        }

        /* لوغو النقابة البنفسجي والأزرق الشبح */
        .site-logo .logo-ar {
            font-family: 'Changa', sans-serif !important;
            font-size: 3.8rem !important;
            font-weight: 900 !important;
            background: linear-gradient(180deg, #d88aff 0%, #9d4edd 45%, #7b2cbf 75%, #00b4d8 100%) !important;
            -webkit-background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
            filter: drop-shadow(0 2px 4px #000) drop-shadow(0 0 12px var(--Aura-Glow)) !important;
        }
        .site-logo .logo-en {
            font-family: 'Montserrat', sans-serif !important;
            font-size: 0.9rem !important;
            letter-spacing: 4px !important;
            color: var(--Aura-Blue) !important;
            text-shadow: 0 0 8px var(--Aura-Glow) !important;
        }

        /* الهيدر */
        .site-navbar {
            background: #050608 !important;
            border-bottom: 2px solid var(--gris-claro) !important;
            padding: 10px 0 !important;
            position: sticky;
            top: 0;
            z-index: 999999;
        }
        .navegacion {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
        }

        /* شريط البحث */
        .header-search .search-form {
            background: var(--gris-medio) !important;
            border: 1px solid var(--gris-claro) !important;
            border-radius: 20px !important;
            padding: 4px 14px !important;
            transition: 0.3s;
        }
        .header-search .search-form:focus-within {
            border-color: var(--Aura-Blue) !important;
            box-shadow: 0 0 12px var(--Aura-Glow) !important;
        }
        .header-search .search-field {
            color: #fff !important;
            font-family: 'Cairo', sans-serif !important;
        }

        /* القوائم والأزرار */
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
            user-select: none;
            transition: 0.25s ease;
        }

        /* زر النشر البنفسجي المتوهج */
        .btn-publish {
            background: linear-gradient(135deg, #7b2cbf, #5a189a) !important;
            color: #fff !important;
            border: 1px solid rgba(0, 180, 216, 0.4) !important;
            box-shadow: 0 0 10px rgba(123, 44, 191, 0.4) !important;
        }
        .btn-publish:hover, .dropdown-wrapper.is-open .btn-publish {
            background: linear-gradient(135deg, #9d4edd, #7b2cbf) !important;
            box-shadow: 0 0 16px var(--Aura-Glow) !important;
        }

        /* زر البروفايل */
        .btn-user {
            background: var(--gris-medio) !important;
            color: #eee !important;
            border: 1px solid var(--gris-claro) !important;
        }
        .btn-user:hover, .dropdown-wrapper.is-open .btn-user {
            border-color: var(--Aura-Blue) !important;
            box-shadow: 0 0 10px var(--Aura-Glow) !important;
        }
        .nav-avatar img {
            border-radius: 50% !important;
            border: 2px solid var(--Aura-Blue) !important;
        }

        /* القائمة المنسدلة: مخفية تماماً وتظهر فقط عند النقر (is-open) */
        .dropdown-content {
            display: none !important;
            position: absolute !important;
            top: 100% !important;
            left: 0 !important;
            background: #0c0d14 !important;
            border: 1px solid var(--Primario) !important;
            min-width: 175px !important;
            border-radius: 8px !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.95), 0 0 15px rgba(138, 43, 226, 0.4) !important;
            z-index: 9999999 !important;
            margin-top: 8px !important;
            overflow: hidden !important;
            flex-direction: column !important;
        }

        /* كلاس الفتح عند النقر */
        .dropdown-wrapper.is-open .dropdown-content {
            display: flex !important;
            animation: fadeInDrop 0.2s ease;
        }

        @keyframes fadeInDrop {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dropdown-content a {
            display: block !important;
            padding: 12px 16px !important;
            color: #ddd !important;
            font-size: 13.5px !important;
            border-bottom: 1px solid #181a26 !important;
            text-align: right !important;
            text-decoration: none !important;
            transition: 0.2s !important;
        }
        .dropdown-content a:hover {
            background: var(--Primario) !important;
            color: #fff !important;
            padding-right: 20px !important;
        }
        .logout-item { color: #ff5252 !important; }

        /* شارات السلايدر والأزرار */
        .slide-badge {
            background: var(--Aura-Blue) !important;
            color: #000 !important;
            font-weight: 900 !important;
            box-shadow: 0 0 10px var(--Aura-Glow);
        }
        .swiper-button-next, .swiper-button-prev {
            color: var(--Aura-Blue) !important;
        }
    </style>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

    <header class="site-navbar">
        <div class="contenedor navegacion">
            
            <!-- لوغو النقابة -->
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

            <!-- أزرار النشر والبروفايل -->
            <div class="enlaces">
                <?php if (sr_can_user_publish()): ?>
                    <div class="dropdown-wrapper" id="dropdown-publish">
                        <button type="button" class="btn-action-trigger btn-publish">
                            <span>نشر ▾</span>
                        </button>
                        <div class="dropdown-content">
                            <a href="<?php echo esc_url(home_url('/add-episode/')); ?>">🎬 نشر حلقة جديدة</a>
                            <a href="<?php echo esc_url(home_url('/add-anime/')); ?>">✨ إضافة عمل جديد</a>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (is_user_logged_in()): 
                    $current_user = wp_get_current_user();
                ?>
                    <div class="dropdown-wrapper" id="dropdown-user">
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

    <!-- كود الجافاسكربت الذكي لتثبيت القائمة وإغلاقها بالنقر في أي مكان فارغ -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // فتح وإغلاق القوائم عند النقر على الزر
        document.querySelectorAll('.btn-action-trigger').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                var parent = this.closest('.dropdown-wrapper');
                var isOpen = parent.classList.contains('is-open');

                // إغلاق أي قائمة أخرى مفتوحة أولاً
                document.querySelectorAll('.dropdown-wrapper').forEach(function(d) {
                    d.classList.remove('is-open');
                });

                // التبديل (فتح إن كانت مغلقة، والعكس)
                if (!isOpen) {
                    parent.classList.add('is-open');
                }
            });
        });

        // منع إغلاق القائمة إذا نقر المستخدم داخل محتوى القائمة نفسها
        document.querySelectorAll('.dropdown-content').forEach(function(content) {
            content.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });

        // إغلاق القائمة فوراً عند النقر في أي مكان فارغ في الموقع
        document.addEventListener('click', function() {
            document.querySelectorAll('.dropdown-wrapper').forEach(function(d) {
                d.classList.remove('is-open');
            });
        });
    });
    </script>
