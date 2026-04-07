{{-- resources/views/register.blade.php --}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DivHive - احجز مقعدك</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('styles.css') }}">
    <style>
        .reg-page {
            min-height: 100vh;
            padding: 110px 1rem 60px;
            display: flex;
            align-items: flex-start;
            justify-content: center
        }

        .reg-container {
            width: 100%;
            max-width: 560px
        }

        .reg-page-title {
            text-align: center;
            margin-bottom: 2rem
        }

        .reg-page-title h1 {
            font-size: 2.2rem;
            font-weight: 800;
            color: white;
            margin-bottom: .5rem
        }

        .reg-page-title h1 .hl {
            background: linear-gradient(135deg, #a78bfa, #7c3aed);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text
        }

        .reg-page-title p {
            color: rgba(255, 255, 255, .5);
            font-size: .95rem
        }

        .reg-card {
            background: linear-gradient(135deg, rgba(15, 30, 74, .97), rgba(42, 22, 84, .95));
            border: 1px solid rgba(61, 38, 101, .5);
            border-radius: 22px;
            padding: 2.5rem;
            box-shadow: 0 25px 70px rgba(0, 0, 0, .55);
            position: relative;
            overflow: hidden
        }

        .reg-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 10%;
            right: 10%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #7c3aed, #a78bfa, #7c3aed, transparent)
        }

        /* Steps */
        .steps-bar {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2.5rem
        }

        .sbar-dot {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: .35rem
        }

        .sbar-circle {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 2px solid rgba(61, 38, 101, .4);
            background: rgba(10, 15, 40, .7);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: .9rem;
            color: rgba(255, 255, 255, .3);
            transition: all .4s;
            flex-shrink: 0
        }

        .sbar-label {
            font-size: .68rem;
            font-weight: 600;
            color: rgba(255, 255, 255, .25);
            white-space: nowrap;
            transition: color .4s
        }

        .sbar-line {
            width: 52px;
            height: 2px;
            background: rgba(61, 38, 101, .3);
            margin: 0 2px 1.4rem;
            flex-shrink: 0;
            border-radius: 2px;
            transition: background .4s
        }

        .sbar-dot.active .sbar-circle {
            background: linear-gradient(135deg, #1a3a6b, #7c3aed);
            border-color: #a78bfa;
            color: white;
            box-shadow: 0 0 20px rgba(124, 58, 237, .55), 0 0 0 4px rgba(124, 58, 237, .12)
        }

        .sbar-dot.active .sbar-label {
            color: #c4b5fd
        }

        .sbar-dot.done .sbar-circle {
            background: linear-gradient(135deg, #15803d, #4ade80);
            border-color: #4ade80;
            color: white
        }

        .sbar-dot.done .sbar-circle .num {
            display: none
        }

        .sbar-dot.done .sbar-circle::after {
            content: '✓';
            font-size: 1rem
        }

        .sbar-dot.done .sbar-label {
            color: #86efac
        }

        .sbar-line.done {
            background: linear-gradient(90deg, #4ade80, #22c55e)
        }

        .reg-step {
            display: none
        }

        .reg-step.active {
            display: block;
            animation: stepIn .38s cubic-bezier(.4, 0, .2, 1)
        }

        @keyframes stepIn {
            from {
                opacity: 0;
                transform: translateY(20px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .step-heading {
            margin-bottom: 1.6rem
        }

        .step-heading h2 {
            color: white;
            font-size: 1.45rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: .6rem;
            margin-bottom: .3rem
        }

        .step-heading h2 i {
            color: #a78bfa;
            font-size: 1.1rem
        }

        .step-heading p {
            color: rgba(255, 255, 255, .4);
            font-size: .85rem
        }

        .field {
            margin-bottom: 1.25rem
        }

        .field label {
            display: block;
            color: rgba(255, 255, 255, .88);
            font-weight: 600;
            font-size: .9rem;
            margin-bottom: .45rem
        }

        .field label .req {
            color: #f87171
        }

        .field input {
            width: 100%;
            padding: .92rem 1.05rem;
            background: linear-gradient(135deg, rgba(26, 58, 82, .3), rgba(42, 22, 84, .3));
            border: 2px solid rgba(61, 38, 101, .3);
            border-radius: 10px;
            color: white;
            font-size: .95rem;
            font-family: inherit;
            direction: rtl;
            transition: border-color .3s, box-shadow .3s
        }

        .field input::placeholder {
            color: rgba(255, 255, 255, .28)
        }

        .field input:focus {
            outline: none;
            border-color: rgba(124, 58, 237, .65);
            box-shadow: 0 0 0 3px rgba(124, 58, 237, .14)
        }

        .pw-field {
            position: relative
        }

        .pw-field input {
            padding-left: 3rem
        }

        .pw-eye {
            position: absolute;
            left: .9rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: rgba(255, 255, 255, .3);
            cursor: pointer;
            font-size: 1rem;
            transition: color .3s;
            padding: 0
        }

        .pw-eye:hover {
            color: rgba(255, 255, 255, .75)
        }

        .err-msg {
            display: none;
            align-items: center;
            gap: .55rem;
            background: rgba(239, 68, 68, .1);
            border: 1px solid rgba(239, 68, 68, .35);
            border-radius: 8px;
            padding: .7rem 1rem;
            color: #fca5a5;
            font-size: .875rem;
            font-weight: 600;
            margin-bottom: 1.2rem
        }

        .err-msg.show {
            display: flex
        }

        .laravel-errors {
            background: rgba(239, 68, 68, .1);
            border: 1px solid rgba(239, 68, 68, .35);
            border-radius: 10px;
            padding: 1rem 1.2rem;
            margin-bottom: 1.5rem;
            color: #fca5a5;
            font-size: .875rem;
            font-weight: 600
        }

        .laravel-errors ul {
            padding-right: 1.2rem;
            margin: 0
        }

        .laravel-errors ul li {
            margin-bottom: .3rem
        }

        .success-alert {
            background: rgba(74, 222, 128, .1);
            border: 1px solid rgba(74, 222, 128, .3);
            border-radius: 10px;
            padding: .8rem 1rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: .6rem;
            color: #4ade80;
            font-size: .88rem;
            font-weight: 700
        }

        /* ✅ Order Summary */
        .order-summary {
            background: linear-gradient(135deg, rgba(124, 58, 237, .1), rgba(88, 28, 220, .05));
            border: 1px solid rgba(124, 58, 237, .25);
            border-radius: 16px;
            padding: 1.4rem;
            margin-bottom: 1.5rem
        }

        .order-summary-title {
            color: #c4b5fd;
            font-size: .88rem;
            font-weight: 800;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: .5rem;
            letter-spacing: .5px
        }

        .order-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: .55rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, .05)
        }

        .order-row:last-child {
            border-bottom: none
        }

        .order-row-label {
            color: rgba(255, 255, 255, .45);
            font-size: .83rem;
            display: flex;
            align-items: center;
            gap: .4rem
        }

        .order-row-label i {
            color: #a78bfa;
            width: 14px;
            text-align: center
        }

        .order-row-value {
            color: #f1f5f9;
            font-size: .88rem;
            font-weight: 700
        }

        .order-total {
            background: rgba(124, 58, 237, .18);
            border-radius: 10px;
            padding: .9rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: .75rem;
            border: 1px solid rgba(124, 58, 237, .25)
        }

        .order-total-label {
            color: #c4b5fd;
            font-weight: 800;
            font-size: .88rem
        }

        .order-total-value {
            color: white;
            font-size: 1.4rem;
            font-weight: 900
        }

        .pay-methods-row {
            display: flex;
            flex-wrap: wrap;
            gap: .45rem;
            margin-top: .9rem
        }

        .pay-method-tag {
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            padding: .28rem .7rem;
            background: rgba(255, 255, 255, .05);
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 6px;
            font-size: .72rem;
            color: rgba(255, 255, 255, .5)
        }

        .pay-method-tag i {
            color: #a78bfa;
            font-size: .7rem
        }

        /* Kashier redirect button */
        .btn-pay {
            width: 100%;
            padding: 1.05rem;
            background: linear-gradient(135deg, #1a3a6b 0%, #7c3aed 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 800;
            font-size: 1.05rem;
            font-family: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .6rem;
            transition: all .3s;
            box-shadow: 0 6px 24px rgba(124, 58, 237, .45);
            position: relative;
            overflow: hidden
        }

        .btn-pay::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, .08), transparent);
            opacity: 0;
            transition: opacity .3s
        }

        .btn-pay:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 32px rgba(124, 58, 237, .6)
        }

        .btn-pay:hover::before {
            opacity: 1
        }

        .btn-pay:disabled {
            opacity: .65;
            cursor: not-allowed;
            transform: none
        }

        .btn-main {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #0f1e4a, #7c3aed);
            color: white;
            border: none;
            border-radius: 11px;
            font-weight: 700;
            font-size: 1rem;
            font-family: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .55rem;
            transition: all .3s;
            box-shadow: 0 6px 22px rgba(42, 22, 84, .45)
        }

        .btn-main:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 32px rgba(124, 58, 237, .5)
        }

        .btn-main:disabled {
            opacity: .65;
            cursor: not-allowed;
            transform: none
        }

        .btn-back {
            width: 100%;
            padding: .88rem;
            background: rgba(61, 38, 101, .22);
            color: rgba(255, 255, 255, .6);
            border: 2px solid rgba(61, 38, 101, .38);
            border-radius: 11px;
            font-weight: 600;
            font-size: .9rem;
            font-family: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .45rem;
            transition: all .3s;
            margin-top: .75rem
        }

        .btn-back:hover {
            background: rgba(61, 38, 101, .45);
            border-color: rgba(124, 58, 237, .45);
            color: white
        }

        .btn-login-redirect {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .92rem 2.8rem;
            background: linear-gradient(135deg, #0f1e4a, #7c3aed);
            color: white;
            border: none;
            border-radius: 11px;
            font-weight: 700;
            font-size: 1rem;
            font-family: inherit;
            cursor: pointer;
            text-decoration: none;
            transition: all .3s;
            box-shadow: 0 6px 22px rgba(124, 58, 237, .4)
        }

        .btn-login-redirect:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 32px rgba(124, 58, 237, .6)
        }

        .btn-whatsapp {
            width: 100%;
            padding: .88rem;
            background: linear-gradient(135deg, #25d366, #16a34a);
            color: white;
            border: none;
            border-radius: 11px;
            font-weight: 700;
            font-size: .9rem;
            font-family: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            transition: all .3s;
            text-decoration: none;
            margin-top: .75rem
        }

        .btn-whatsapp:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(37, 211, 102, .4)
        }

        .security-note {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .4rem;
            color: rgba(74, 222, 128, .65);
            font-size: .76rem;
            font-weight: 600;
            margin-top: .65rem
        }

        .success-screen {
            text-align: center;
            padding: 1rem 0 .5rem
        }

        .success-screen .big-icon {
            font-size: 5.5rem;
            display: block;
            margin-bottom: 1rem;
            animation: iconPop .65s cubic-bezier(.4, 0, .2, 1)
        }

        @keyframes iconPop {
            0% {
                transform: scale(0) rotate(-180deg);
                opacity: 0
            }

            55% {
                transform: scale(1.18) rotate(6deg)
            }

            75% {
                transform: scale(.95) rotate(-3deg)
            }

            100% {
                transform: scale(1) rotate(0);
                opacity: 1
            }
        }

        .success-screen h2 {
            color: white;
            font-size: 1.75rem;
            font-weight: 800;
            margin-bottom: .5rem
        }

        .success-screen p {
            color: rgba(255, 255, 255, .5);
            line-height: 1.75;
            margin-bottom: 2rem;
            font-size: .95rem
        }

        .reg-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: rgba(255, 255, 255, .35);
            font-size: .88rem
        }

        .reg-footer a {
            color: #a78bfa;
            font-weight: 700;
            text-decoration: none
        }

        .reg-footer a:hover {
            color: #c4b5fd;
            text-decoration: underline
        }

        @media(max-width:540px) {
            .reg-card {
                padding: 1.5rem 1.1rem
            }

            .reg-page-title h1 {
                font-size: 1.7rem
            }

            .sbar-line {
                width: 28px
            }

            .sbar-circle {
                width: 38px;
                height: 38px;
                font-size: .82rem
            }
        }
    </style>
</head>

<body>

    <header id="header">
        <nav>
            <div class="logo"><i class="fas fa-graduation-cap"></i>DivHive</div>
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

    <div class="reg-page">
        <div class="reg-container">

            <div class="reg-page-title">
                <h1>🎓 احجز <span class="hl">مقعدك الآن</span></h1>
                <p>أكمل الخطوات السريعة وابدأ رحلتك في التعلم</p>
            </div>

            <div class="reg-card">

                @if ($errors->any())
                    <div class="laravel-errors">
                        <ul>
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('payment_error'))
                    <div class="laravel-errors">
                        <ul>
                            <li>{{ session('payment_error') }}</li>
                        </ul>
                    </div>
                @endif

                <!-- Steps Bar -->
                <div class="steps-bar">
                    <div class="sbar-dot active" id="d1">
                        <div class="sbar-circle"><span class="num">1</span></div>
                        <div class="sbar-label">بياناتك</div>
                    </div>
                    <div class="sbar-line" id="l1"></div>
                    <div class="sbar-dot" id="d2">
                        <div class="sbar-circle"><span class="num">2</span></div>
                        <div class="sbar-label">الدفع</div>
                    </div>
                    <div class="sbar-line" id="l2"></div>
                    <div class="sbar-dot" id="d3">
                        <div class="sbar-circle"><span class="num">3</span></div>
                        <div class="sbar-label">الحساب</div>
                    </div>
                </div>

                {{-- ══ خطوة 1: البيانات ══ --}}
                <div class="reg-step active" id="s1">
                    <div class="step-heading">
                        <h2><i class="fas fa-user-circle"></i> بياناتك الشخصية</h2>
                        <p>الخطوة 1 من 3 — أدخل معلوماتك الأساسية</p>
                    </div>
                    <div class="err-msg" id="e1"><i class="fas fa-exclamation-circle"></i><span
                            id="e1t"></span></div>
                    <div class="field">
                        <label for="fName">الاسم الكامل <span class="req">*</span></label>
                        <input type="text" id="fName" placeholder="مثال: أحمد محمد علي" autocomplete="name"
                            value="{{ session('reg_data.name', old('name')) }}">
                    </div>
                    <div class="field">
                        <label for="fPhone">رقم الهاتف <span class="req">*</span></label>
                        <input type="tel" id="fPhone" placeholder="مثال: 01012345678"
                            value="{{ session('reg_data.phone', old('phone')) }}">
                    </div>
                    <div class="field">
                        <label for="fEmail">البريد الإلكتروني <span class="req">*</span></label>
                        <input type="email" id="fEmail" placeholder="example@email.com" autocomplete="email"
                            value="{{ session('reg_data.email', old('email')) }}">
                    </div>
                    <button class="btn-main" id="nextBtn1" onclick="next1()">
                        التالي — مراجعة الطلب <i class="fas fa-arrow-left"></i>
                    </button>
                    <button class="btn-back" onclick="location.href='/'">
                        <i class="fas fa-arrow-right"></i> العودة للرئيسية
                    </button>
                </div>

                {{-- ══ خطوة 2: مراجعة وزرار الدفع ══ --}}
                <div class="reg-step" id="s2">
                    <div class="step-heading">
                        <h2><i class="fas fa-receipt"></i> مراجعة الطلب</h2>
                        <p>الخطوة 2 من 3 — راجع بياناتك ثم ادفع بأمان</p>
                    </div>

                    <!-- ملخص الطلب -->
                    <div class="order-summary">
                        <div class="order-summary-title">
                            <i class="fas fa-clipboard-list"></i> ملخص الطلب
                        </div>
                        <div class="order-row">
                            <span class="order-row-label"><i class="fas fa-user"></i> الاسم</span>
                            <span class="order-row-value" id="sum-name">—</span>
                        </div>
                        <div class="order-row">
                            <span class="order-row-label"><i class="fas fa-envelope"></i> البريد</span>
                            <span class="order-row-value" id="sum-email">—</span>
                        </div>
                        <div class="order-row">
                            <span class="order-row-label"><i class="fas fa-phone"></i> الهاتف</span>
                            <span class="order-row-value" id="sum-phone">—</span>
                        </div>
                        <div class="order-row">
                            <span class="order-row-label"><i class="fas fa-graduation-cap"></i> الكورس</span>
                            <span class="order-row-value">JavaScript للمبتدئين</span>
                        </div>
                        <div class="order-total">
                            <span class="order-total-label"><i class="fas fa-tag" style="margin-left:6px"></i>إجمالي
                                الاشتراك</span>
                            <span class="order-total-value">499 ج.م</span>
                        </div>
                        <div class="pay-methods-row">
                            <span class="pay-method-tag"><i class="fas fa-mobile-alt"></i> فودافون كاش</span>
                            <span class="pay-method-tag"><i class="fas fa-mobile-alt"></i> إنستاباي</span>
                            <span class="pay-method-tag"><i class="fas fa-credit-card"></i> بطاقة بنكية</span>
                            <span class="pay-method-tag"><i class="fas fa-university"></i> تحويل بنكي</span>
                        </div>
                    </div>

                    <div class="err-msg" id="e2"><i class="fas fa-exclamation-circle"></i><span
                            id="e2t"></span></div>

                    {{-- ✅ زرار الدفع — بيفتح صفحة كاشير مباشرة --}}
                    <button class="btn-pay" id="payBtn" onclick="goToPay()">
                        <i class="fas fa-lock"></i>
                        ادفع الآن 499 ج.م بأمان عبر Kashier
                        <i class="fas fa-arrow-left"></i>
                    </button>

                    <div class="security-note">
                        <i class="fas fa-shield-halved"></i>
                        دفع آمن ومشفر 256-bit — ستُنقل لصفحة الدفع الآمنة
                    </div>

                    <button class="btn-back" onclick="jump(1)">
                        <i class="fas fa-arrow-right"></i> السابق — تعديل البيانات
                    </button>
                </div>

                {{-- ══ خطوة 3: إنشاء الحساب بعد الدفع ══ --}}
                <div class="reg-step" id="s3">
                    <div class="step-heading">
                        <h2><i class="fas fa-lock"></i> إنشاء حسابك</h2>
                        <p>الخطوة 3 من 3 — اختر اسم مستخدم وكلمة مرور</p>
                    </div>

                    <div class="success-alert">
                        <i class="fas fa-circle-check" style="font-size:1.2rem"></i>
                        <span>✅ تم الدفع بنجاح! أكمل إنشاء حسابك الآن</span>
                    </div>

                    <div class="err-msg" id="e3"><i class="fas fa-exclamation-circle"></i><span
                            id="e3t"></span></div>

                    <form id="registerForm" action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="field">
                            <label for="fUser">اسم المستخدم <span class="req">*</span></label>
                            <input type="text" id="fUser" name="username"
                                placeholder="مثال: ahmed2025 (أحرف وأرقام فقط)" autocomplete="username"
                                value="{{ old('username') }}">
                        </div>
                        <div class="field">
                            <label for="fPass">كلمة المرور <span class="req">*</span></label>
                            <div class="pw-field">
                                <input type="password" id="fPass" name="password" placeholder="6 أحرف على الأقل"
                                    autocomplete="new-password">
                                <button class="pw-eye" type="button" onclick="togglePW('fPass',this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="field">
                            <label for="fConf">تأكيد كلمة المرور <span class="req">*</span></label>
                            <div class="pw-field">
                                <input type="password" id="fConf" name="password_confirmation"
                                    placeholder="أعد كتابة كلمة المرور" autocomplete="new-password">
                                <button class="pw-eye" type="button" onclick="togglePW('fConf',this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="btn-main" id="submitBtn" onclick="return validateStep3()">
                            <i class="fas fa-user-plus"></i> إنشاء الحساب والدخول
                        </button>
                    </form>

                    <a href="https://wa.me/201000000000" target="_blank" class="btn-whatsapp">
                        <i class="fab fa-whatsapp"></i> مشكلة؟ تواصل معنا على واتساب
                    </a>
                </div>

                {{-- ══ خطوة 4: النجاح ══ --}}
                <div class="reg-step" id="s4">
                    <div class="success-screen">
                        <span class="big-icon">🎓</span>
                        <h2>أهلاً بك في DivHive!</h2>
                        <p>تم إنشاء حسابك وتفعيل اشتراكك بنجاح 🎉<br>اضغط على الزرار وابدأ رحلتك!</p>
                        <a href="/course" class="btn-login-redirect">
                            <i class="fas fa-play-circle"></i> ابدأ الكورس الآن
                        </a>
                    </div>
                </div>

            </div>

            <div class="reg-footer">
                عندك حساب بالفعل؟ <a href="/login">سجّل دخولك</a>
            </div>
        </div>
    </div>

    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;

        // ── تحديد الخطوة الأولى بعد التحميل ─────────────────────────────
        window.addEventListener('DOMContentLoaded', () => {
            @if (session('payment_paid'))
                jump(3);
            @elseif ($errors->any())
                jump(3);
            @elseif (session('reg_data'))
                fillSummary(
                    '{{ addslashes(session('reg_data.name', '')) }}',
                    '{{ addslashes(session('reg_data.email', '')) }}',
                    '{{ addslashes(session('reg_data.phone', '')) }}'
                );
                jump(2);
            @endif
        });

        // ── التنقل بين الخطوات ───────────────────────────────────────────
        function jump(n) {
            [1, 2, 3, 4].forEach(i => document.getElementById('s' + i)?.classList.remove('active'));
            document.getElementById('s' + n)?.classList.add('active');
            [1, 2, 3].forEach(i => {
                const dot = document.getElementById('d' + i);
                const line = document.getElementById('l' + i);
                if (!dot) return;
                dot.classList.remove('active', 'done');
                if (i < n) {
                    dot.classList.add('done');
                    line?.classList.add('done');
                } else if (i === n) {
                    dot.classList.add('active');
                    line?.classList.remove('done');
                } else {
                    line?.classList.remove('done');
                }
            });
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // ── خطوة 1: تحقق من البيانات وأرسلها ────────────────────────────
        async function next1() {
            const name = document.getElementById('fName').value.trim();
            const phone = document.getElementById('fPhone').value.trim();
            const email = document.getElementById('fEmail').value.trim();

            if (!name || !phone || !email) return showErr(1, 'يرجى ملء جميع الحقول');
            if (name.length < 3) return showErr(1, 'الاسم يجب أن يكون 3 أحرف على الأقل');
            if (!/^\d{10,15}$/.test(phone.replace(/\D/g, ''))) return showErr(1, 'رقم الهاتف غير صحيح');
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) return showErr(1, 'البريد الإلكتروني غير صحيح');

            clearErr(1);
            setBtnLoading('nextBtn1', true, 'جارٍ التحقق...');

            try {
                const res = await fetch('{{ route('payment.session') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: JSON.stringify({
                        name,
                        phone,
                        email
                    })
                });
                const data = await res.json();

                if (data.success) {
                    // ✅ احفظ الـ paymentUrl
                    window._kashierUrl = data.paymentUrl;
                    fillSummary(name, email, phone);
                    jump(2);
                } else {
                    showErr(1, data.message || 'حدث خطأ، حاول مجدداً');
                }
            } catch (e) {
                showErr(1, 'مشكلة في الاتصال، تأكد من الإنترنت');
            }

            setBtnLoading('nextBtn1', false);
        }

        // ── ملء ملخص الطلب ───────────────────────────────────────────────
        function fillSummary(name, email, phone) {
            document.getElementById('sum-name').textContent = name || '—';
            document.getElementById('sum-email').textContent = email || '—';
            document.getElementById('sum-phone').textContent = phone || '—';
        }

        // ── خطوة 2: الذهاب لكاشير ────────────────────────────────────────
        function goToPay() {
            if (!window._kashierUrl) {
                showErr(2, 'حدث خطأ، ارجع للخطوة الأولى وحاول مجدداً');
                return;
            }
            clearErr(2);
            setBtnLoading('payBtn', true, 'جارٍ فتح بوابة الدفع...');
            // ✅ فتح صفحة كاشير مباشرة
            window.location.href = window._kashierUrl;
        }

        // ── خطوة 3: التحقق قبل submit ────────────────────────────────────
        function validateStep3() {
            const user = document.getElementById('fUser').value.trim();
            const pass = document.getElementById('fPass').value;
            const conf = document.getElementById('fConf').value;

            if (!user || !pass || !conf) {
                showErr(3, 'يرجى ملء جميع الحقول');
                return false;
            }
            if (user.length < 3) {
                showErr(3, 'اسم المستخدم 3 أحرف على الأقل');
                return false;
            }
            if (!/^[a-zA-Z0-9]+$/.test(user)) {
                showErr(3, 'اسم المستخدم أحرف وأرقام إنجليزية فقط');
                return false;
            }
            if (pass.length < 6) {
                showErr(3, 'كلمة المرور 6 أحرف على الأقل');
                return false;
            }
            if (pass !== conf) {
                showErr(3, 'كلمتا المرور غير متطابقتين');
                return false;
            }

            clearErr(3);
            setBtnLoading('submitBtn', true, 'جارٍ إنشاء الحساب...');
            return true;
        }

        // ── Helpers ───────────────────────────────────────────────────────
        function showErr(step, msg) {
            const box = document.getElementById('e' + step);
            document.getElementById('e' + step + 't').textContent = msg;
            box?.classList.add('show');
            box?.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest'
            });
        }

        function clearErr(step) {
            document.getElementById('e' + step)?.classList.remove('show');
        }

        function setBtnLoading(id, loading, msg = '') {
            const btn = document.getElementById(id);
            if (!btn) return;
            btn.disabled = loading;
            if (loading && msg) btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ' + msg;
        }

        function togglePW(id, btn) {
            const inp = document.getElementById(id);
            const icon = btn.querySelector('i');
            inp.type = inp.type === 'password' ? 'text' : 'password';
            icon.className = inp.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
        }

        // ── Hamburger ─────────────────────────────────────────────────────
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
