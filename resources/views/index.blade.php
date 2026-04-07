{{-- resources/views/index.blade.php --}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevHive - منصة الكورسات الذكية</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('styles.css') }}">
    <style>
        /* ══ Hero Video ══ */
        .hero-video-wrap {
            position: relative;
            width: 100%;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(124, 58, 237, 0.35), 0 0 0 1px rgba(124, 58, 237, 0.2);
        }

        .hero-video-wrap::before {
            content: '';
            display: block;
            padding-bottom: 56.25%;
        }

        .hero-video-wrap iframe {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }

        /* glow pulse */
        .hero-video-wrap::after {
            content: '';
            position: absolute;
            inset: -2px;
            border-radius: 22px;
            background: linear-gradient(135deg, #7c3aed, #a78bfa, #7c3aed);
            z-index: -1;
            animation: glowPulse 3s ease-in-out infinite;
        }

        @keyframes glowPulse {

            0%,
            100% {
                opacity: .5
            }

            50% {
                opacity: 1
            }
        }

        /* ══ الفيديو يبقى ظاهر على الموبايل ══ */
        .hero-video-mobile-wrap {
            display: none;
            margin-top: 2rem;
        }

        @media (max-width: 900px) {
            .hero-image {
                display: none !important;
            }

            .hero-video-mobile-wrap {
                display: block;
                order: -1;
                margin-top: 0;
                margin-bottom: 1.5rem;
            }

            .hero-content {
                display: flex;
                flex-direction: column;
            }
        }

        /* ══ Featured Video ══ */
        .featured-video-wrap {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 12px 40px rgba(124, 58, 237, 0.3);
            margin-top: 1.5rem;
        }

        .featured-video-wrap::before {
            content: '';
            display: block;
            padding-bottom: 56.25%;
        }

        .featured-video-wrap iframe {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }

        /* ══ Testimonials ══ */
        .testimonials {
            padding: 4rem 0;
            overflow: hidden;
        }

        .testimonials-track-wrap {
            overflow: hidden;
            direction: ltr;
            mask-image: linear-gradient(90deg, transparent 0%, black 6%, black 94%, transparent 100%);
            -webkit-mask-image: linear-gradient(90deg, transparent 0%, black 6%, black 94%, transparent 100%);
        }

        .testimonials-track {
            display: flex;
            gap: 1.5rem;
            width: max-content;
            direction: ltr;
            animation: testimonialsScroll 32s linear infinite;
        }

        .testimonials-track-wrap:hover .testimonials-track {
            animation-play-state: paused;
        }

        @keyframes testimonialsScroll {
            0% {
                transform: translateX(0)
            }

            100% {
                transform: translateX(-50%)
            }
        }

        .testimonial-card {
            direction: rtl;
            background: linear-gradient(135deg, rgba(15, 30, 74, .7), rgba(30, 15, 60, .6));
            border: 1px solid rgba(61, 38, 101, .4);
            border-radius: 20px;
            padding: 1.8rem;
            width: 300px;
            flex-shrink: 0;
            transition: border-color .3s, box-shadow .3s, transform .3s;
        }

        .testimonials-track-wrap:hover .testimonial-card:hover {
            border-color: rgba(124, 58, 237, .5);
            box-shadow: 0 8px 30px rgba(124, 58, 237, .15);
            transform: translateY(-4px);
        }

        .stars {
            margin-bottom: .8rem;
        }

        .star {
            color: #f59e0b;
            font-size: 1rem;
        }

        .testimonial-text {
            color: rgba(255, 255, 255, .75);
            font-size: .9rem;
            line-height: 1.7;
            margin-bottom: 1.2rem;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: .8rem;
        }

        .author-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1a3a6b, #7c3aed);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: white;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .author-info h4 {
            color: white;
            font-size: .9rem;
            font-weight: 700;
            margin-bottom: .15rem;
        }

        .author-info p {
            color: rgba(255, 255, 255, .45);
            font-size: .78rem;
        }

        /* ══ Footer Social ══ */
        .footer-social {
            display: flex;
            gap: .65rem;
            margin-top: 1.1rem;
        }

        .social-btn {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .95rem;
            color: rgba(255, 255, 255, .65);
            text-decoration: none;
            background: rgba(255, 255, 255, .06);
            border: 1px solid rgba(255, 255, 255, .1);
            backdrop-filter: blur(6px);
            transition: color .25s, background .25s, border-color .25s, transform .25s;
        }

        .social-btn:hover {
            color: white;
            transform: translateY(-3px);
        }

        .social-btn.facebook:hover {
            background: rgba(24, 119, 242, .2);
            border-color: rgba(24, 119, 242, .4);
        }

        .social-btn.instagram:hover {
            background: rgba(225, 48, 108, .2);
            border-color: rgba(225, 48, 108, .4);
        }

        .social-btn.tiktok:hover {
            background: rgba(255, 255, 255, .12);
            border-color: rgba(255, 255, 255, .3);
        }

        .social-btn.whatsapp:hover {
            background: rgba(37, 211, 102, .2);
            border-color: rgba(37, 211, 102, .4);
        }

        /* ══ WhatsApp Float ══ */
        .whatsapp-float {
            position: fixed;
            bottom: 2rem;
            left: 2rem;
            z-index: 9999;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, #25d366, #128c7e);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            text-decoration: none;
            box-shadow: 0 6px 24px rgba(37, 211, 102, .5);
            transition: transform .3s, box-shadow .3s;
            animation: waPulse 2.5s ease-in-out infinite;
        }

        .whatsapp-float:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 32px rgba(37, 211, 102, .7);
        }

        @keyframes waPulse {

            0%,
            100% {
                box-shadow: 0 6px 24px rgba(37, 211, 102, .5)
            }

            50% {
                box-shadow: 0 6px 36px rgba(37, 211, 102, .8)
            }
        }

        .wa-tooltip {
            position: absolute;
            right: calc(100% + 12px);
            background: rgba(15, 30, 74, .95);
            color: white;
            font-size: .8rem;
            font-weight: 600;
            padding: .4rem .8rem;
            border-radius: 8px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity .3s;
            border: 1px solid rgba(37, 211, 102, .3);
            font-family: 'Cairo', sans-serif;
        }

        .whatsapp-float:hover .wa-tooltip {
            opacity: 1;
        }

        /* ══ Featured mobile fix ══ */
        @media (max-width: 768px) {
            .featured-content {
                flex-direction: column !important;
                gap: 1.5rem !important;
            }

            .featured-icon-box {
                display: none !important;
            }
        }
    </style>
</head>

<body>

    <!-- ===================== HEADER ===================== -->
    <header id="header">
        <nav>
            <div class="logo"><i class="fas fa-graduation-cap"></i>DevHive</div>
            <ul class="nav-links">
                <li><a href="#courses">الكورسات</a></li>
                <li><a href="#featured">الكورس المميز</a></li>
                <li><a href="#testimonials">آراء الطلاب</a></li>
                <li><a href="#footer">التواصل</a></li>
            </ul>
            <div class="nav-buttons">
                @auth
                    @if (auth()->user()->isAdmin())
                        <button class="btn-login" onclick="location.href='/admin/dashboard'">
                            <i class="fas fa-cog"></i> لوحة التحكم
                        </button>
                    @else
                        <button class="btn-login" onclick="location.href='/course'">
                            <i class="fas fa-play-circle"></i> كورساتي
                        </button>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" style="display:inline">
                        @csrf
                        <button type="submit" class="btn-signup">خروج</button>
                    </form>
                @else
                    <button class="btn-login" onclick="location.href='/login'">دخول</button>
                    <button class="btn-signup" onclick="location.href='/register'">انضم الآن</button>
                @endauth
            </div>
            <button class="hamburger-menu" id="hamburger"><span></span><span></span><span></span></button>
        </nav>
        <ul class="mobile-menu" id="mobileMenu">
            <li><a href="#courses">الكورسات</a></li>
            <li><a href="#featured">الكورس المميز</a></li>
            <li><a href="#testimonials">آراء الطلاب</a></li>
            <li><a href="#footer">التواصل</a></li>
            @auth
                <li><button class="btn-login-mobile" onclick="location.href='/course'">كورساتي</button></li>
            @else
                <li><button class="btn-login-mobile" onclick="location.href='/login'">دخول</button></li>
                <li><button class="btn-signup-mobile" onclick="location.href='/register'">انضم الآن</button></li>
            @endauth
        </ul>
    </header>

    <!-- ===================== HERO ===================== -->
    <section class="hero">
        <div class="hero-container">

            <div class="hero-content">

                <h1>تعلّم البرمجة من الصفر إلى الاحترافية</h1>
                <p>انضم إلى آلاف الطلاب الذين تعلموا البرمجة معنا وحصلوا على وظائف أحلامهم في أفضل الشركات التقنية</p>
                <div class="hero-buttons">
                    @auth
                        <button class="btn-primary" onclick="location.href='/course'">
                            <i class="fas fa-play-circle"></i> مشاهدة الكورس
                        </button>
                    @else
                        <button class="btn-primary" onclick="location.href='/register'">
                            <i class="fas fa-play-circle"></i> ابدأ التعلم
                        </button>
                    @endauth
                    <button class="btn-secondary"
                        onclick="document.getElementById('courses').scrollIntoView({behavior:'smooth'})">
                        <i class="fas fa-compass"></i> استكشف الكورسات
                    </button>
                </div>
                <div class="icon-grid">
                    <div class="icon-item"><i class="fas fa-video"></i><span>550+ ساعة فيديو</span></div>
                    <div class="icon-item"><i class="fas fa-certificate"></i><span>شهادات معتمدة</span></div>
                    <div class="icon-item"><i class="fas fa-users"></i><span>مجتمع نشط</span></div>
                    <div class="icon-item"><i class="fas fa-headset"></i><span>دعم 24/7</span></div>
                </div>

                {{-- ✅ الفيديو في الموبايل تحت الأيقونات --}}
                @if (isset($firstLesson) && $firstLesson?->video_url)
                    <div class="hero-video-mobile-wrap">
                        <div class="hero-video-wrap">
                            <iframe id="heroVideoMobile"
                                src="https://play.gumlet.io/embed/{{ $firstLesson->video_url }}?autoplay=true&muted=true&loop=false&disable_player_controls=false"
                                allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        </div>
                    </div>
                @endif
            </div>
            {{-- ✅ الفيديو يحل محل hero-image-box في الديسكتوب --}}
            <div class="hero-image">
                @if (isset($firstLesson) && $firstLesson?->video_url)
                    <div class="hero-video-wrap">
                        <iframe id="heroVideoDesktop"
                            src="https://play.gumlet.io/embed/{{ $firstLesson->video_url }}?autoplay=true&muted=true&loop=false&disable_player_controls=false"
                            allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                    </div>
                @else
                    <div class="hero-image-box"><i class="fas fa-code"></i></div>
                @endif
            </div>
        </div>
    </section>

    <!-- ===================== COURSES ===================== -->
    <section class="courses" id="courses">
        <div class="section-header">
            <h2>استكشف كورساتنا</h2>
            <p>مجموعة شاملة من الكورسات المتخصصة في البرمجة والتطوير</p>
        </div>
        <div class="courses-grid">
            <div class="course-card beginner">
                <div class="course-image">
                    <img src="{{ asset('كورس المبتديئينJS.jpeg') }}" alt="JavaScript">
                </div>
                <div class="course-content">
                    <span class="course-badge beginner">مستوى مبتدئ</span>
                    <h3 class="course-title">JavaScript للمبتدئين</h3>
                    <p class="course-description">تعلم أساسيات جافا سكريبت من الصفر مع أمثلة عملية وتطبيقات حقيقية</p>
                    <div class="course-stats">
                        <div class="stat"><i class="fas fa-clock"></i><span>40 ساعة</span></div>
                        <div class="stat"><i class="fas fa-video"></i><span>120 درس</span></div>
                    </div>
                    <div class="course-footer">
                        <span class="course-price">499 ج.م</span>
                        @auth
                            <button class="btn-enroll" onclick="location.href='/course'">
                                <i class="fas fa-play-circle"></i> مشاهدة الكورس
                            </button>
                        @else
                            <button class="btn-enroll" onclick="location.href='/register'">
                                <i class="fas fa-plus"></i> التحق الآن
                            </button>
                        @endauth
                    </div>
                </div>
            </div>
            <div class="course-card intermediate" style="opacity:.6;pointer-events:none">
                 <div class="course-image">
                    <img src="{{ asset('فرونتREACT.jpeg') }}" alt="React">
                </div>
                <div class="course-content">
                    <span class="course-badge intermediate">مستوى متوسط</span>
                    <h3 class="course-title">React من الصفر</h3>
                    <p class="course-description">بناء تطبيقات ويب حديثة باستخدام مكتبة React القوية والمشهورة</p>
                    <div class="course-stats">
                        <div class="stat"><i class="fas fa-clock"></i><span>60 ساعة</span></div>
                        <div class="stat"><i class="fas fa-video"></i><span>180 درس</span></div>
                    </div>
                    <div class="course-footer">
                        <span class="course-price">1499 ج.م</span>
                        <button class="btn-enroll" disabled><i class="fas fa-clock"></i> قريباً</button>
                    </div>
                </div>
            </div>
            <div class="course-card advanced" style="opacity:.6;pointer-events:none">
                <div class="course-image">
                    <img src="{{ asset('باك اند Node.js.jpeg') }}" alt="Node.js">
                </div>
                <div class="course-content">
                    <span class="course-badge advanced">مستوى متقدم</span>
                    <h3 class="course-title">Node.js وBackend</h3>
                    <p class="course-description">احترف تطوير الخوادم وبناء APIs قوية مع Node.js والقواعد البيانات</p>
                    <div class="course-stats">
                        <div class="stat"><i class="fas fa-clock"></i><span>55 ساعة</span></div>
                        <div class="stat"><i class="fas fa-video"></i><span>165 درس</span></div>
                    </div>
                    <div class="course-footer">
                        <span class="course-price">1999 ج.م</span>
                        <button class="btn-enroll" disabled><i class="fas fa-clock"></i> قريباً</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================== FEATURED ===================== -->
    <section class="featured-section" id="featured">
        <div class="featured-content">
            <div class="featured-text">
                <h2>🚀 Fundamentals of Programming with JavaScript</h2>
                <p>الكورس الأشمل لتعلم أساسيات البرمجة باستخدام JavaScript</p>
                <div class="featured-highlights">
                    <div class="highlight-item"><i class="fas fa-check-circle"></i><span>شرح عميق لكل المفاهيم
                            الأساسية</span></div>
                    <div class="highlight-item"><i class="fas fa-check-circle"></i><span>100+ مشروع عملي وتمارين
                            تفاعلية</span></div>
                    <div class="highlight-item"><i class="fas fa-check-circle"></i><span>معلمون ذو خبرة 10+
                            سنوات</span></div>
                    <div class="highlight-item"><i class="fas fa-check-circle"></i><span>شهادة معتمدة دولياً عند
                            الانتهاء</span></div>
                    <div class="highlight-item"><i class="fas fa-check-circle"></i><span>إمكانية الدخول مدى
                            الحياة</span></div>
                    <div class="highlight-item"><i class="fas fa-check-circle"></i><span>كويز بعد كل درس لتثبيت
                            المعلومات</span></div>
                </div>
                @auth
                    <button class="featured-btn" onclick="location.href='/course'"><i class="fas fa-play-circle"></i>
                        مشاهدة الكورس</button>
                @else
                    <button class="featured-btn" onclick="location.href='/register'"><i class="fas fa-arrow-left"></i>
                        احجز مقعدك الآن</button>
                @endauth
            </div>

            @php
                $firstLesson = \App\Models\Course::with([
                    'lessons' => function ($q) {
                        $q->where('is_active', true)->orderBy('order')->limit(1);
                    },
                ])
                    ->first()
                    ?->lessons->first();
            @endphp

            @if ($firstLesson && $firstLesson->video_url)
                <div class="featured-video-wrap">
                    <iframe
                        src="https://play.gumlet.io/embed/{{ $firstLesson->video_url }}?autoplay=false&loop=false&preload=false"
                        allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture"
                        allowfullscreen>
                    </iframe>
                </div>
            @else
                <div class="featured-icon-box"><i class="fab fa-js-square"></i></div>
            @endif

        </div>
    </section>


    <!-- ===================== STATS ===================== -->
    <section class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">15K+</div>
                <div class="stat-label">طالب متعلم</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">150+</div>
                <div class="stat-label">ساعة محتوى</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">98%</div>
                <div class="stat-label">معدل الرضا</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">50+</div>
                <div class="stat-label">كورس متاح</div>
            </div>
        </div>
    </section>

    <!-- ===================== TESTIMONIALS ===================== -->
    <section class="testimonials" id="testimonials">
        <div class="section-header">
            <h2>آراء طلابنا</h2>
            <p>استمع إلى تجارب الطلاب الحقيقيين</p>
        </div>
        <div class="testimonials-track-wrap">
            <div class="testimonials-track">
                @php
                    $reviews = [
                        [
                            'letter' => 'أ',
                            'name' => 'أحمد علي',
                            'job' => 'مطور ويب — Google',
                            'text' =>
                                'كورس JavaScript الأفضل اللي اخذته! الكويزات بعد كل درس ساعدتني أثبّت المعلومات بشكل احترافي.',
                        ],
                        [
                            'letter' => 'ف',
                            'name' => 'فاطمة محمد',
                            'job' => 'مطورة Frontend — Microsoft',
                            'text' => 'المساعد الذكي في الكورس أجاب على كل أسئلتي! تجربة تعلّم استثنائية.',
                        ],
                        [
                            'letter' => 'م',
                            'name' => 'محمود حسن',
                            'job' => 'Full Stack Developer — Amazon',
                            'text' => 'أفضل استثمار! الواجبات والمشاريع العملية ساعدتني أحصل على وظيفة أحلامي.',
                        ],
                        [
                            'letter' => 'س',
                            'name' => 'سارة إبراهيم',
                            'job' => 'مطورة — Spotify',
                            'text' => 'الشرح واضح جداً والمنصة سهلة. أنهيت الكورس في وقت قياسي بفضل النظام المنظّم.',
                        ],
                        [
                            'letter' => 'ع',
                            'name' => 'عمر خالد',
                            'job' => 'Software Engineer — Meta',
                            'text' => 'نظام الكويزات والدروس المتسلسلة حفّزني أكمل لآخر درس. أروع تجربة تعليمية!',
                        ],
                        [
                            'letter' => 'ن',
                            'name' => 'نور الدين',
                            'job' => 'Mobile Developer — Apple',
                            'text' => 'المحتوى بالعربي وبجودة عالمية! ده اللي بيميز DevHive عن أي منصة تانية.',
                        ],
                    ];
                @endphp

                {{-- الكروت الأصلية --}}
                @foreach ($reviews as $r)
                    <div class="testimonial-card">
                        <div class="stars"><span class="star">★</span><span class="star">★</span><span
                                class="star">★</span><span class="star">★</span><span class="star">★</span>
                        </div>
                        <p class="testimonial-text">{{ $r['text'] }}</p>
                        <div class="testimonial-author">
                            <div class="author-avatar">{{ $r['letter'] }}</div>
                            <div class="author-info">
                                <h4>{{ $r['name'] }}</h4>
                                <p>{{ $r['job'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
                {{-- نسخ مكررة للـ infinite loop --}}
                @foreach ($reviews as $r)
                    <div class="testimonial-card">
                        <div class="stars"><span class="star">★</span><span class="star">★</span><span
                                class="star">★</span><span class="star">★</span><span class="star">★</span>
                        </div>
                        <p class="testimonial-text">{{ $r['text'] }}</p>
                        <div class="testimonial-author">
                            <div class="author-avatar">{{ $r['letter'] }}</div>
                            <div class="author-info">
                                <h4>{{ $r['name'] }}</h4>
                                <p>{{ $r['job'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ===================== FOOTER ===================== -->
    <footer id="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h4><i class="fas fa-graduation-cap"></i> DevHive</h4>
                <p style="color:rgba(255,255,255,.7);line-height:1.8;font-size:.9rem">منصة تعليمية متخصصة في البرمجة
                    والتطوير.</p>
                <div class="footer-social">
                    <a href="https://facebook.com/DivHive" target="_blank" class="social-btn facebook"
                        title="فيسبوك"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://instagram.com/DivHive" target="_blank" class="social-btn instagram"
                        title="انستجرام"><i class="fab fa-instagram"></i></a>
                    <a href="https://tiktok.com/@DivHive" target="_blank" class="social-btn tiktok"
                        title="تيك توك"><i class="fab fa-tiktok"></i></a>
                    <a href="https://wa.me/201000000000" target="_blank" class="social-btn whatsapp"
                        title="واتساب"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
            <div class="footer-section">
                <h4>روابط سريعة</h4>
                <ul>
                    <li><a href="#courses">الكورسات</a></li>
                    <li><a href="/register">احجز مقعدك</a></li>
                    <li><a href="/login">تسجيل الدخول</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>تواصل معنا</h4>
                <ul>
                    <li
                        style="color:rgba(255,255,255,.7);display:flex;align-items:center;gap:.6rem;margin-bottom:.8rem;font-size:.88rem">
                        <i class="fas fa-envelope" style="color:#a78bfa"></i> support@divhive.com
                    </li>
                    <li style="color:rgba(255,255,255,.7);display:flex;align-items:center;gap:.6rem;font-size:.88rem">
                        <i class="fas fa-map-marker-alt" style="color:#a78bfa"></i> القاهرة، مصر
                    </li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2025 DevHive. جميع الحقوق محفوظة.</p>
        </div>
    </footer>

    <!-- ✅ زرار واتساب عائم -->
    <a href="https://wa.me/201000000000" target="_blank" class="whatsapp-float" title="تواصل معنا">
        <i class="fab fa-whatsapp"></i>
        <span class="wa-tooltip">تواصل معنا على واتساب</span>
    </a>

    <script>
        // ✅ وقّف الفيديو بعد 5 ثواني
        // Gumlet بيقبل postMessage لإيقاف الفيديو
        window.addEventListener('load', function() {
            var pauseDelay = 10000; // 10 ثواني

            function pauseGumlet(iframeId) {
                var iframe = document.getElementById(iframeId);
                if (!iframe) return;
                setTimeout(function() {
                    try {
                        // Gumlet player API
                        iframe.contentWindow.postMessage(JSON.stringify({
                            type: 'pause'
                        }), '*');
                        // fallback: غيّر الـ src لإزالة autoplay
                        var src = iframe.src;
                        iframe.src = src.replace('autoplay=true', 'autoplay=false');
                    } catch (e) {}
                }, pauseDelay);
            }

            pauseGumlet('heroVideoDesktop');
            pauseGumlet('heroVideoMobile');
            pauseGumlet('featuredVideo');
        });

        // Header scroll
        window.addEventListener('scroll', () =>
            document.getElementById('header').classList.toggle('scrolled', scrollY > 20), {
                passive: true
            }
        );

        // Hamburger
        const ham = document.getElementById('hamburger');
        const menu = document.getElementById('mobileMenu');
        ham.addEventListener('click', () => {
            ham.classList.toggle('active');
            menu.classList.toggle('active');
        });
        document.addEventListener('click', e => {
            if (!ham.contains(e.target) && !menu.contains(e.target)) {
                ham.classList.remove('active');
                menu.classList.remove('active');
            }
        });
        menu.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
            ham.classList.remove('active');
            menu.classList.remove('active');
        }));
    </script>

</body>

</html>
