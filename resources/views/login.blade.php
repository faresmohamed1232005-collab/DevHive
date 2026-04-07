{{-- resources/views/login.blade.php --}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DevHive - تسجيل الدخول</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('styles.css') }}">
    <style>
        .login-page {
            min-height: 100vh;
            padding-top: 90px;
            padding-bottom: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-wrapper {
            width: 100%;
            max-width: 460px;
            padding: 0 1rem;
        }

        .login-heading {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-heading h1 {
            color: white;
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.4rem;
        }

        .login-heading h1 span {
            background: linear-gradient(135deg, #a78bfa 0%, #7c3aed 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .login-heading p {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.95rem;
        }

        .login-card {
            background: linear-gradient(135deg, rgba(15, 30, 74, 0.97) 0%, rgba(42, 22, 84, 0.95) 100%);
            border: 1px solid rgba(61, 38, 101, 0.5);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5), 0 0 40px rgba(42, 22, 84, 0.3);
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, #7c3aed, #a78bfa, #7c3aed, transparent);
        }

        .card-icon-wrap {
            text-align: center;
            margin-bottom: 1.8rem;
        }

        .card-icon {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, rgba(15, 30, 74, 0.8) 0%, rgba(124, 58, 237, 0.4) 100%);
            border: 2px solid rgba(124, 58, 237, 0.4);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: #a78bfa;
            box-shadow: 0 0 25px rgba(124, 58, 237, 0.3);
        }

        .card-title-text {
            color: white;
            font-size: 1.4rem;
            font-weight: 700;
            margin-top: 0.8rem;
        }

        .card-sub-text {
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.85rem;
            margin-top: 0.2rem;
        }

        .laravel-error {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.4);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            color: #fca5a5;
            font-size: 0.88rem;
            font-weight: 600;
            margin-bottom: 1.3rem;
        }

        .lg-group {
            margin-bottom: 1.3rem;
        }

        .lg-group label {
            display: block;
            color: rgba(255, 255, 255, 0.85);
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.45rem;
        }

        .lg-group input {
            width: 100%;
            padding: 0.9rem 1rem;
            background: linear-gradient(135deg, rgba(26, 58, 82, 0.35) 0%, rgba(42, 22, 84, 0.35) 100%);
            border: 2px solid rgba(61, 38, 101, 0.35);
            border-radius: 10px;
            color: white;
            font-size: 0.95rem;
            font-family: inherit;
            direction: rtl;
            transition: border-color 0.3s, box-shadow 0.3s, background 0.3s;
        }

        .lg-group input::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        .lg-group input:focus {
            outline: none;
            border-color: rgba(124, 58, 237, 0.7);
            background: linear-gradient(135deg, rgba(26, 58, 82, 0.55) 0%, rgba(42, 22, 84, 0.55) 100%);
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.15);
        }

        .lg-group input.input-error {
            border-color: rgba(239, 68, 68, 0.6);
        }

        .pw-wrap {
            position: relative;
        }

        .pw-wrap input {
            padding-left: 3rem;
        }

        .pw-toggle {
            position: absolute;
            left: 0.9rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.35);
            cursor: pointer;
            font-size: 1rem;
            transition: color 0.3s;
            padding: 0;
        }

        .pw-toggle:hover {
            color: rgba(255, 255, 255, 0.8);
        }

        .btn-login-action {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #0f1e4a 0%, #7c3aed 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 1rem;
            font-family: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(42, 22, 84, 0.4);
            margin-top: 0.5rem;
        }

        .btn-login-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(124, 58, 237, 0.5);
        }

        .btn-login-action.loading {
            pointer-events: none;
            opacity: 0.75;
        }

        .spin-icon {
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            display: none;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .btn-login-action.loading .spin-icon {
            display: block;
        }

        .btn-login-action.loading .btn-arrow {
            display: none;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            margin: 1.5rem 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(61, 38, 101, 0.4);
        }

        .divider span {
            color: rgba(255, 255, 255, 0.3);
            font-size: 0.8rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .btn-goto-register {
            width: 100%;
            padding: 0.85rem;
            background: transparent;
            color: rgba(255, 255, 255, 0.65);
            border: 2px solid rgba(61, 38, 101, 0.45);
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            font-family: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-goto-register:hover {
            background: rgba(124, 58, 237, 0.12);
            border-color: rgba(124, 58, 237, 0.5);
            color: #c4b5fd;
        }

        .back-home-link {
            text-align: center;
            margin-top: 1.2rem;
        }

        .back-home-link a {
            color: rgba(255, 255, 255, 0.3);
            font-size: 0.85rem;
            text-decoration: none;
            transition: color 0.3s;
        }

        .back-home-link a:hover {
            color: rgba(255, 255, 255, 0.7);
        }

        @media (max-width:480px) {
            .login-card {
                padding: 1.5rem 1.2rem;
            }

            .login-heading h1 {
                font-size: 1.6rem;
            }
        }
    </style>
</head>

<body>

    <header id="header">
        <nav>
            <div class="logo"><i class="fas fa-graduation-cap"></i>DevHive</div>
            <ul class="nav-links">
                <li><a href="/#courses">الكورسات</a></li>
                <li><a href="/#featured">الكورس المميز</a></li>
                <li><a href="/#testimonials">آراء الطلاب</a></li>
                <li><a href="/#footer">التواصل</a></li>
            </ul>
            <div class="nav-buttons">
                <button class="btn-login" onclick="location.href='/login'">دخول</button>
                <button class="btn-signup" onclick="location.href='/register'">انضم الآن</button>
            </div>
            <button class="hamburger-menu" id="hamburger"><span></span><span></span><span></span></button>
        </nav>
        <ul class="mobile-menu" id="mobileMenu">
            <li><a href="/#courses">الكورسات</a></li>
            <li><a href="/#featured">الكورس المميز</a></li>
            <li><a href="/#testimonials">آراء الطلاب</a></li>
            <li><a href="/#footer">التواصل</a></li>
            <li><button class="btn-login-mobile" onclick="location.href='/login'">دخول</button></li>
            <li><button class="btn-signup-mobile" onclick="location.href='/register'">انضم الآن</button></li>
        </ul>
    </header>

    <div class="login-page">
        <div class="login-wrapper">

            <div class="login-heading">
                <h1>مرحباً <span>بعودتك 👋</span></h1>
                <p>سجّل دخولك للوصول إلى كورساتك ومحتواك</p>
            </div>

            <div class="login-card">

                <div class="card-icon-wrap">
                    <div class="card-icon"><i class="fas fa-sign-in-alt"></i></div>
                    <div class="card-title-text">تسجيل الدخول</div>
                    <div class="card-sub-text">أدخل بياناتك أدناه</div>
                </div>

                @if ($errors->any())
                    <div class="laravel-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form id="loginForm" action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="lg-group">
                        <label for="lgUser">
                            <i class="fas fa-user" style="color:#a78bfa;font-size:0.8rem;"></i>
                            اسم المستخدم أو البريد الإلكتروني
                        </label>
                        <input type="text" id="lgUser" name="login" placeholder="username أو email@example.com"
                            autocomplete="username" value="{{ old('login') }}"
                            class="{{ $errors->has('login') ? 'input-error' : '' }}">
                    </div>

                    <div class="lg-group">
                        <label for="lgPass">
                            <i class="fas fa-lock" style="color:#a78bfa;font-size:0.8rem;"></i>
                            كلمة المرور
                        </label>
                        <div class="pw-wrap">
                            <input type="password" id="lgPass" name="password" placeholder="أدخل كلمة المرور"
                                autocomplete="current-password">
                            <button class="pw-toggle" type="button" onclick="togglePW()">
                                <i class="fas fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-login-action" id="loginBtn">
                        <span id="loginBtnTxt">دخول</span>
                        <div class="spin-icon"></div>
                        <i class="fas fa-arrow-left btn-arrow"></i>
                    </button>

                </form>

                <div class="divider"><span>أو</span></div>

                <a href="/register" class="btn-goto-register">
                    <i class="fas fa-user-plus"></i>
                    إنشاء حساب جديد
                </a>

            </div>

            <div class="back-home-link">
                <a href="/"><i class="fas fa-home"></i> العودة للرئيسية</a>
            </div>

        </div>
    </div>

    <script>
        function togglePW() {
            const inp = document.getElementById('lgPass');
            const icon = document.getElementById('eyeIcon');
            inp.type = inp.type === 'password' ? 'text' : 'password';
            icon.className = inp.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
        }

        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('loginBtn');
            btn.classList.add('loading');
            document.getElementById('loginBtnTxt').textContent = 'جاري الدخول...';
        });

        document.addEventListener('keydown', e => {
            if (e.key === 'Enter') document.getElementById('loginForm').submit();
        });

        const hamburger = document.getElementById('hamburger');
        const mobileMenu = document.getElementById('mobileMenu');
        hamburger.addEventListener('click', e => {
            e.stopPropagation();
            hamburger.classList.toggle('active');
            mobileMenu.classList.toggle('active');
        });
        document.addEventListener('click', e => {
            if (!e.target.closest('header') && mobileMenu.classList.contains('active')) {
                hamburger.classList.remove('active');
                mobileMenu.classList.remove('active');
            }
        });

        window.addEventListener('scroll', () =>
            document.getElementById('header').classList.toggle('scrolled', scrollY > 50)
        );
    </script>

</body>

</html>
