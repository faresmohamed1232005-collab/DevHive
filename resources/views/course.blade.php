{{-- resources/views/course.blade.php --}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $course->title }} — DevHive</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('styles.css') }}">
    <style>
        .video-embed-wrap {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            border-radius: 12px;
            background: #000;
            margin-bottom: .75rem
        }

        .video-embed-wrap iframe,
        .video-embed-wrap video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0
        }

        .lesson-block {
            background: linear-gradient(135deg, rgba(15, 30, 74, .6), rgba(30, 15, 60, .5));
            border: 1px solid rgba(61, 38, 101, .4);
            border-radius: 16px;
            padding: 0;
            margin-bottom: 1rem;
            transition: all .3s;
            position: relative;
            overflow: hidden
        }

        .lesson-block.locked {
            opacity: .5;
            pointer-events: none
        }

        .lesson-block.locked::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, .3);
            z-index: 2;
            border-radius: 16px
        }

        .lesson-block.completed {
            border-color: rgba(74, 222, 128, .3)
        }

        .lesson-block.open {
            border-color: rgba(124, 58, 237, .45);
            box-shadow: 0 0 20px rgba(124, 58, 237, .1)
        }

        .lesson-block-header {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1.2rem 1.5rem;
            cursor: pointer;
            user-select: none;
            border-radius: 16px;
            transition: background .2s
        }

        .lesson-block-header:hover {
            background: rgba(124, 58, 237, .08)
        }

        .lesson-block.open .lesson-block-header {
            border-radius: 16px 16px 0 0;
            border-bottom: 1px solid rgba(61, 38, 101, .3)
        }

        .lesson-toggle-arrow {
            margin-right: auto;
            color: rgba(255, 255, 255, .35);
            font-size: .95rem;
            transition: transform .35s ease, color .3s;
            flex-shrink: 0;
            margin-top: .25rem
        }

        .lesson-block.open .lesson-toggle-arrow {
            transform: rotate(180deg);
            color: #a78bfa
        }

        .lesson-block-num {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1a3a6b, #7c3aed);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: .95rem;
            color: white;
            flex-shrink: 0;
            margin-top: .1rem
        }

        .lesson-block-num.done {
            background: linear-gradient(135deg, #15803d, #4ade80)
        }

        .lesson-block-num.locked-num {
            background: rgba(61, 38, 101, .4);
            color: rgba(255, 255, 255, .3)
        }

        .lesson-block-info {
            flex: 1;
            min-width: 0
        }

        .lesson-block-info h3 {
            color: white;
            font-size: 1.05rem;
            font-weight: 700;
            margin-bottom: .35rem;
            line-height: 1.4
        }

        .lesson-inline-desc {
            color: rgba(255, 255, 255, .5);
            font-size: .8rem;
            line-height: 1.6;
            margin-bottom: .5rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden
        }

        .lesson-block.open .lesson-inline-desc {
            -webkit-line-clamp: unset;
            overflow: visible
        }

        .lesson-block-meta {
            display: flex;
            gap: .75rem;
            flex-wrap: wrap
        }

        .lesson-block-meta span {
            color: rgba(255, 255, 255, .4);
            font-size: .78rem;
            display: flex;
            align-items: center;
            gap: .3rem
        }

        .lesson-block-meta span i {
            color: #a78bfa;
            font-size: .72rem
        }

        .lock-overlay {
            position: absolute;
            inset: 0;
            z-index: 3;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: .75rem;
            border-radius: 16px
        }

        .lock-overlay i {
            font-size: 2.5rem;
            color: rgba(255, 255, 255, .3)
        }

        .lock-overlay p {
            color: rgba(255, 255, 255, .4);
            font-size: .9rem;
            font-weight: 600
        }

        .lesson-collapsible {
            max-height: 0;
            overflow: hidden;
            transition: max-height .45s ease, opacity .35s ease, padding .35s ease;
            opacity: 0;
            padding: 0 1.5rem
        }

        .lesson-block.open .lesson-collapsible {
            max-height: 4000px;
            opacity: 1;
            padding: 1.5rem
        }

        .video-placeholder {
            height: 240px;
            background: rgba(0, 0, 0, .3);
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: .75rem;
            margin-bottom: 1rem
        }

        .video-placeholder i {
            font-size: 3rem;
            color: rgba(124, 58, 237, .4)
        }

        .video-placeholder p {
            color: rgba(255, 255, 255, .3);
            font-size: .9rem
        }

        .quiz-accordion {
            margin-top: 1rem;
            border: 1px solid rgba(124, 58, 237, .25);
            border-radius: 12px;
            overflow: hidden
        }

        .quiz-accordion-header {
            display: flex;
            align-items: center;
            gap: .7rem;
            padding: .9rem 1.2rem;
            background: linear-gradient(135deg, rgba(124, 58, 237, .12), rgba(42, 22, 84, .25));
            cursor: pointer;
            user-select: none;
            transition: background .2s
        }

        .quiz-accordion-header:hover {
            background: linear-gradient(135deg, rgba(124, 58, 237, .2), rgba(42, 22, 84, .35))
        }

        .quiz-accordion-header>i {
            color: #a78bfa;
            font-size: .95rem
        }

        .quiz-accordion-title {
            color: #a78bfa;
            font-weight: 700;
            font-size: .95rem;
            flex: 1
        }

        .quiz-pass-score {
            color: rgba(255, 255, 255, .35);
            font-size: .75rem
        }

        .quiz-accordion-arrow {
            color: rgba(255, 255, 255, .35);
            font-size: .85rem;
            transition: transform .3s, color .3s
        }

        .quiz-accordion.open .quiz-accordion-arrow {
            transform: rotate(180deg);
            color: #a78bfa
        }

        .quiz-accordion-body {
            max-height: 0;
            overflow: hidden;
            transition: max-height .4s ease, opacity .3s ease;
            opacity: 0;
            background: linear-gradient(135deg, rgba(124, 58, 237, .06), rgba(42, 22, 84, .15))
        }

        .quiz-accordion.open .quiz-accordion-body {
            max-height: 3000px;
            opacity: 1
        }

        .quiz-accordion-inner {
            padding: 1.2rem 1.2rem 1.5rem
        }

        .quiz-question {
            background: rgba(255, 255, 255, .04);
            border: 1px solid rgba(255, 255, 255, .07);
            border-radius: 10px;
            padding: 1rem 1.2rem;
            margin-bottom: 1rem
        }

        .quiz-question p {
            color: white;
            font-weight: 600;
            margin-bottom: .8rem;
            font-size: .92rem
        }

        .quiz-options label {
            display: flex;
            align-items: center;
            gap: .6rem;
            color: rgba(255, 255, 255, .7);
            font-size: .87rem;
            padding: .45rem .7rem;
            border-radius: 7px;
            cursor: pointer;
            transition: background .2s;
            margin-bottom: .3rem
        }

        .quiz-options label:hover {
            background: rgba(124, 58, 237, .15)
        }

        .quiz-options input[type=radio] {
            accent-color: #a78bfa
        }

        .btn-submit-quiz {
            padding: .75rem 1.8rem;
            background: linear-gradient(135deg, #1a3a6b, #7c3aed);
            color: white;
            border: none;
            border-radius: 9px;
            font-weight: 700;
            font-size: .9rem;
            font-family: inherit;
            cursor: pointer;
            transition: all .3s;
            display: inline-flex;
            align-items: center;
            gap: .5rem
        }

        .btn-submit-quiz:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(124, 58, 237, .4)
        }

        .quiz-result {
            display: none;
            text-align: center;
            padding: 1.2rem;
            border-radius: 10px;
            margin-top: 1rem
        }

        .quiz-result.passed {
            background: rgba(74, 222, 128, .08);
            border: 1px solid rgba(74, 222, 128, .25);
            color: #4ade80
        }

        .quiz-result.failed {
            background: rgba(248, 113, 113, .08);
            border: 1px solid rgba(248, 113, 113, .25);
            color: #f87171
        }

        .quiz-result .score-big {
            font-size: 2.5rem;
            font-weight: 900;
            margin-bottom: .4rem
        }

        .quiz-passed-badge {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: rgba(74, 222, 128, .12);
            border: 1px solid rgba(74, 222, 128, .3);
            color: #4ade80;
            padding: .5rem 1rem;
            border-radius: 8px;
            font-size: .85rem;
            font-weight: 700
        }

        .file-drop-zone {
            border: 2px dashed rgba(124, 58, 237, .4);
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all .3s;
            color: rgba(255, 255, 255, .5)
        }

        .file-drop-zone:hover,
        .file-drop-zone.drag-over {
            border-color: #a78bfa;
            background: rgba(124, 58, 237, .08);
            color: white
        }

        .file-drop-zone i {
            font-size: 2rem;
            margin-bottom: .5rem;
            color: #a78bfa;
            display: block
        }

        .file-drop-zone span {
            color: #a78bfa;
            font-weight: 700
        }

        .toast-container {
            position: fixed;
            bottom: 1.5rem;
            left: 5rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: .6rem
        }

        .toast {
            padding: .8rem 1.2rem;
            border-radius: 10px;
            color: white;
            font-weight: 600;
            font-size: .88rem;
            display: flex;
            align-items: center;
            gap: .5rem;
            animation: toastIn .3s ease;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .3);
            max-width: 320px
        }

        .toast.success {
            background: #15803d
        }

        .toast.info {
            background: #1e40af
        }

        .toast.error {
            background: #991b1b
        }

        @keyframes toastIn {
            from {
                opacity: 0;
                transform: translateY(20px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        /* ── Floating WhatsApp ── */
        .whatsapp-float {
            position: fixed;
            bottom: 2rem;
            left: 2rem;
            width: 58px;
            height: 58px;
            border-radius: 50%;
            background: #25D366;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.6rem;
            text-decoration: none;
            z-index: 9998;
            box-shadow: 0 4px 20px rgba(37, 211, 102, .5);
            animation: waPulse 2.5s ease-in-out infinite;
            transition: transform .2s;
        }

        .whatsapp-float:hover {
            transform: scale(1.1)
        }

        @keyframes waPulse {

            0%,
            100% {
                box-shadow: 0 4px 20px rgba(37, 211, 102, .5)
            }

            50% {
                box-shadow: 0 4px 30px rgba(37, 211, 102, .8), 0 0 0 10px rgba(37, 211, 102, .12)
            }
        }

        /* ── Sidebar Brand ── */
        .sidebar-brand {
            display: none
        }

        @media(max-width:768px) {
            .sidebar-brand {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: .6rem;
                grid-column: 1/-1;
                background: linear-gradient(135deg, rgba(10, 20, 60, .9), rgba(35, 15, 70, .9));
                border: 1px solid rgba(124, 58, 237, .4);
                border-radius: 18px;
                padding: 1.4rem 1.2rem 1.2rem;
                position: relative;
                overflow: hidden;
                text-align: center
            }

            .sidebar-brand::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 60%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(167, 139, 250, .15), transparent);
                animation: brandShimmer 2.8s ease-in-out infinite
            }

            .sidebar-brand::after {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 2px;
                background: linear-gradient(90deg, #7c3aed, #a78bfa, #7c3aed);
                border-radius: 18px 18px 0 0
            }

            @keyframes brandShimmer {
                0% {
                    left: -100%
                }

                100% {
                    left: 160%
                }
            }

            .sidebar-brand-icon {
                width: 56px;
                height: 56px;
                border-radius: 16px;
                background: linear-gradient(135deg, #1a3a6b, #7c3aed);
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 6px 20px rgba(124, 58, 237, .5), 0 0 0 4px rgba(124, 58, 237, .15);
                position: relative;
                z-index: 1
            }

            .sidebar-brand-icon i {
                font-size: 1.4rem;
                color: white
            }

            .sidebar-brand-name {
                font-size: 1.5rem;
                font-weight: 900;
                background: linear-gradient(135deg, #fff 30%, #c4b5fd 70%, #a78bfa);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                letter-spacing: 1px;
                line-height: 1;
                position: relative;
                z-index: 1
            }

            .sidebar-brand-tagline {
                font-size: .72rem;
                color: rgba(167, 139, 250, .7);
                font-weight: 600;
                letter-spacing: 2px;
                text-transform: uppercase;
                position: relative;
                z-index: 1
            }

            .sidebar-brand-dots {
                display: flex;
                gap: 5px;
                position: relative;
                z-index: 1
            }

            .sidebar-brand-dots span {
                width: 4px;
                height: 4px;
                border-radius: 50%;
                background: rgba(167, 139, 250, .5)
            }

            .sidebar-brand-dots span:nth-child(2) {
                background: rgba(167, 139, 250, .9);
                width: 20px;
                border-radius: 3px
            }

            .course-sidebar {
                display: grid !important;
                grid-template-columns: 1fr 1fr !important;
                gap: .75rem
            }

            .course-sidebar .sidebar-brand {
                grid-column: 1/-1
            }

            .course-sidebar .sidebar-card:nth-child(2) {
                grid-column: 1
            }

            .course-sidebar .sidebar-card:nth-child(3) {
                grid-column: 2
            }

            .course-sidebar .sidebar-card:nth-child(4) {
                grid-column: 1/-1
            }

            .course-big-title {
                font-size: .78rem !important;
                line-height: 1.4 !important
            }

            .meta-item span {
                font-size: .7rem !important
            }

            .sidebar-card-header h3 {
                font-size: .8rem !important
            }

            .user-avatar {
                width: 30px !important;
                height: 30px !important;
                font-size: .85rem !important
            }

            .user-name-sub h4 {
                font-size: .78rem !important
            }

            .user-name-sub span,
            .user-detail-item .lbl,
            .user-detail-item .val {
                font-size: .67rem !important
            }

            .user-detail-item .val {
                word-break: break-all
            }

            .stat-box-val {
                font-size: 1rem !important
            }

            .stat-box-lbl {
                font-size: .67rem !important
            }

            /* WhatsApp float على موبايل أصغر شوية */
            .whatsapp-float {
                width: 50px;
                height: 50px;
                font-size: 1.4rem;
                bottom: 1.5rem;
                left: 1.5rem
            }
        }

        @media(max-width:400px) {
            .course-sidebar {
                grid-template-columns: 1fr !important
            }

            .course-sidebar .sidebar-brand,
            .course-sidebar .sidebar-card:nth-child(2),
            .course-sidebar .sidebar-card:nth-child(3),
            .course-sidebar .sidebar-card:nth-child(4) {
                grid-column: 1 !important
            }
        }
    </style>
</head>

<body>

    <header id="header">
        <nav>
            <a class="logo" href="/"><i class="fas fa-graduation-cap"></i>DevHive</a>
            <ul class="nav-links">
                <li><a href="#" data-tab-link="lessons">الدروس</a></li>
                <li><a href="#" data-tab-link="materials">المواد التعليمية</a></li>
                <li><a href="#" data-tab-link="assignments">الواجبات</a></li>
            </ul>
            <div class="nav-buttons">
                <button class="btn-home" onclick="location.href='/'"><i class="fas fa-home"></i>الرئيسية</button>
                <form action="{{ route('logout') }}" method="POST" style="display:inline">
                    @csrf
                    <button type="submit" class="btn-logout"><i class="fas fa-sign-out-alt"></i>خروج</button>
                </form>
            </div>
            <button class="hamburger-menu" id="hamburger"><span></span><span></span><span></span></button>
        </nav>
        <ul class="mobile-menu" id="mobileMenu">
            <li><a href="#" data-tab-link="lessons">الدروس</a></li>
            <li><a href="#" data-tab-link="materials">المواد التعليمية</a></li>
            <li><a href="#" data-tab-link="assignments">الواجبات</a></li>
            <li>
                <div class="mobile-btn-row">
                    <button class="mob-primary" onclick="location.href='/'"><i class="fas fa-home"></i>الرئيسية</button>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="mob-secondary mob-danger"><i
                                class="fas fa-sign-out-alt"></i>خروج</button>
                    </form>
                </div>
            </li>
        </ul>
    </header>

    <div class="progress-banner">
        <div class="progress-banner-inner">
            <span><i class="fas fa-chart-line" style="color:var(--purple);margin-left:.4rem"></i>تقدمك في الكورس</span>
            <div class="progress-bar-wrap">
                <div class="progress-bar-fill" id="progressFill" style="width:{{ $progress }}%"></div>
            </div>
            <span class="progress-pct" id="progressPct">{{ $progress }}%</span>
            <span class="progress-label" id="progressLabel">{{ $completedCount }} / {{ $totalLessons }} دروس</span>
        </div>
    </div>

    <div class="course-page">
        <div class="course-wrapper">

            <aside class="course-sidebar">
                <div class="sidebar-brand">
                    <div class="sidebar-brand-icon"><i class="fas fa-graduation-cap"></i></div>
                    <div class="sidebar-brand-name">DevHive</div>
                    <div class="sidebar-brand-dots"><span></span><span></span><span></span></div>
                    <div class="sidebar-brand-tagline">منصة التعلم الذكي</div>
                </div>
                <div class="sidebar-card">
                    <div class="sidebar-card-header"><i class="fas fa-book-open"></i>
                        <h3>معلومات الكورس</h3>
                    </div>
                    <div class="sidebar-card-body">
                        <p class="course-big-title">{{ $course->title }}</p>
                        <div class="course-meta-row">
                            <div class="meta-item"><i class="fas fa-video"></i><span>الدروس:
                                    <strong>{{ $totalLessons }}</strong></span></div>
                            <div class="meta-item"><i class="fas fa-layer-group"></i><span>المستوى:
                                    <strong>مبتدئ</strong></span></div>
                            <div class="meta-item"><i class="fas fa-certificate"></i><span>شهادة:
                                    <strong>معتمدة</strong></span></div>
                        </div>
                    </div>
                </div>
                <div class="sidebar-card">
                    <div class="sidebar-card-header"><i class="fas fa-user-circle"></i>
                        <h3>حسابي</h3>
                    </div>
                    <div class="sidebar-card-body">
                        <div class="user-avatar-box">
                            <div class="user-avatar">{{ mb_substr(auth()->user()->name, 0, 1) }}</div>
                            <div class="user-name-sub">
                                <h4>{{ auth()->user()->name }}</h4><span>طالب مسجّل</span>
                            </div>
                        </div>
                        <div class="user-detail-row">
                            <div class="user-detail-item"><span class="lbl">البريد:</span><span
                                    class="val">{{ auth()->user()->email }}</span></div>
                            <div class="user-detail-item"><span class="lbl">المستخدم:</span><span
                                    class="val">{{ '@' . auth()->user()->username }}</span></div>
                        </div>
                    </div>
                </div>
                <div class="sidebar-card">
                    <div class="sidebar-card-header"><i class="fas fa-chart-bar"></i>
                        <h3>إحصائياتي</h3>
                    </div>
                    <div class="sidebar-card-body">
                        <div class="stats-duo">
                            <div class="stat-box"><span class="stat-box-val"
                                    id="completedCountEl">{{ $completedCount }}</span><span class="stat-box-lbl">درس
                                    مكتمل</span></div>
                            <div class="stat-box"><span class="stat-box-val"
                                    id="progressSidebar">{{ $progress }}%</span><span
                                    class="stat-box-lbl">التقدم</span></div>
                            <div class="stat-box"><span class="stat-box-val">{{ count($submittedIds) }}</span><span
                                    class="stat-box-lbl">واجب مرفوع</span></div>
                            <div class="stat-box"><span class="stat-box-val">4.9</span><span
                                    class="stat-box-lbl">تقييم الكورس</span></div>
                        </div>
                    </div>
                </div>
            </aside>

            <main class="course-main">
                <div class="tabs-bar">
                    <button class="tab-btn active" data-tab="lessons"><i class="fas fa-play-circle"></i>الدروس<span
                            class="tab-badge">{{ $totalLessons }}</span></button>
                    <button class="tab-btn" data-tab="materials"><i class="fas fa-book"></i>المواد</button>
                    <button class="tab-btn" data-tab="assignments"><i class="fas fa-tasks"></i>الواجبات</button>
                </div>

                <div id="lessonsTab" class="tab-content active">
                    <div class="lessons-header" style="margin-bottom:1.5rem">
                        <h2>قائمة الدروس</h2>
                    </div>

                    @foreach ($course->activeLessons as $lesson)
                        @php
                            $unlocked = in_array($lesson->id, $unlockedLessonIds);
                            $completed = in_array($lesson->id, $completedIds);
                        @endphp
                        <div class="lesson-block {{ $completed ? 'completed' : '' }} {{ !$unlocked ? 'locked' : '' }}"
                            id="lesson-block-{{ $lesson->id }}">
                            @if (!$unlocked)
                                <div class="lock-overlay"><i class="fas fa-lock"></i>
                                    <p>🔒 انجح في كويز الدرس السابق لفتح هذا الدرس</p>
                                </div>
                            @endif
                            <div class="lesson-block-header" onclick="toggleLesson({{ $lesson->id }})">
                                <div
                                    class="lesson-block-num {{ $completed ? 'done' : ($unlocked ? '' : 'locked-num') }}">
                                    @if ($completed)
                                        <i class="fas fa-check"></i>
                                    @elseif(!$unlocked)
                                        <i class="fas fa-lock" style="font-size:.8rem"></i>
                                        @else{{ $loop->iteration }}
                                    @endif
                                </div>
                                <div class="lesson-block-info">
                                    <h3>{{ $lesson->title }}</h3>
                                    @if ($lesson->description)
                                        <div class="lesson-inline-desc">{{ $lesson->description }}</div>
                                    @endif
                                    <div class="lesson-block-meta">
                                        <span><i class="fas fa-clock"></i>{{ $lesson->duration }} دقيقة</span>
                                        <span><i
                                                class="fas fa-signal"></i>{{ $lesson->level === 'basic' ? 'أساسي' : ($lesson->level === 'intermediate' ? 'متوسط' : 'متقدم') }}</span>
                                        @if ($lesson->quiz)
                                            <span><i class="fas fa-question-circle"></i>يحتوي كويز</span>
                                        @endif
                                        @if ($completed)
                                            <span style="color:#4ade80"><i class="fas fa-check-circle"
                                                    style="color:#4ade80"></i>مكتمل</span>
                                        @endif
                                    </div>
                                </div>
                                <i class="fas fa-chevron-down lesson-toggle-arrow"></i>
                            </div>
                            <div class="lesson-collapsible">
                                @if ($lesson->video_url)
                                    <div class="video-embed-wrap">
                                        <iframe
                                            src="https://play.gumlet.io/embed/{{ $lesson->video_url }}?autoplay=false&loop=false"
                                            allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture"
                                            allowfullscreen></iframe>
                                    </div>
                                @else
                                    <div class="video-placeholder"><i class="fas fa-play-circle"></i>
                                        <p>لم يتم إضافة فيديو لهذا الدرس بعد</p>
                                    </div>
                                @endif

                                @if ($lesson->quiz && $lesson->quiz->questions->count() > 0)
                                    @php
                                        $userPassed = \App\Models\QuizAttempt::where('user_id', auth()->id())
                                            ->where('quiz_id', $lesson->quiz->id)
                                            ->where('passed', true)
                                            ->exists();
                                    @endphp
                                    <div class="quiz-accordion" id="quiz-acc-{{ $lesson->id }}">
                                        <div class="quiz-accordion-header" onclick="toggleQuiz({{ $lesson->id }})">
                                            <i class="fas fa-question-circle"></i>
                                            <span class="quiz-accordion-title">{{ $lesson->quiz->title }}</span>
                                            @if ($userPassed)
                                                <span style="color:#4ade80;font-size:.78rem;font-weight:700"><i
                                                        class="fas fa-check-circle"></i> اجتزته ✅</span>
                                            @else
                                                <span class="quiz-pass-score">نجاح:
                                                    {{ $lesson->quiz->passing_score }}%</span>
                                            @endif
                                            <i class="fas fa-chevron-down quiz-accordion-arrow"></i>
                                        </div>
                                        <div class="quiz-accordion-body">
                                            <div class="quiz-accordion-inner">
                                                @if ($userPassed)
                                                    <div class="quiz-passed-badge"><i
                                                            class="fas fa-check-circle"></i>لقد اجتزت هذا الكويز بنجاح
                                                        ✅</div>
                                                @else
                                                    <div id="quiz-form-{{ $lesson->quiz->id }}">
                                                        @foreach ($lesson->quiz->questions as $qi => $q)
                                                            <div class="quiz-question">
                                                                <p>{{ $qi + 1 }}. {{ $q->question }}</p>
                                                                <div class="quiz-options">
                                                                    @if ($q->option_a)
                                                                        <label><input type="radio"
                                                                                name="quiz_{{ $lesson->quiz->id }}_q{{ $q->id }}"
                                                                                value="a">
                                                                            {{ $q->option_a }}</label>
                                                                    @endif
                                                                    @if ($q->option_b)
                                                                        <label><input type="radio"
                                                                                name="quiz_{{ $lesson->quiz->id }}_q{{ $q->id }}"
                                                                                value="b">
                                                                            {{ $q->option_b }}</label>
                                                                    @endif
                                                                    @if ($q->option_c)
                                                                        <label><input type="radio"
                                                                                name="quiz_{{ $lesson->quiz->id }}_q{{ $q->id }}"
                                                                                value="c">
                                                                            {{ $q->option_c }}</label>
                                                                    @endif
                                                                    @if ($q->option_d)
                                                                        <label><input type="radio"
                                                                                name="quiz_{{ $lesson->quiz->id }}_q{{ $q->id }}"
                                                                                value="d">
                                                                            {{ $q->option_d }}</label>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                        <button class="btn-submit-quiz"
                                                            onclick="submitQuiz({{ $lesson->quiz->id }}, {{ $lesson->quiz->passing_score }}, {{ $lesson->id }}, [{{ $lesson->quiz->questions->pluck('id')->join(',') }}])">
                                                            <i class="fas fa-paper-plane"></i> إرسال الإجابات
                                                        </button>
                                                    </div>
                                                    <div class="quiz-result"
                                                        id="quiz-result-{{ $lesson->quiz->id }}"></div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    <div class="ai-widget" style="margin-top:2rem">
                        <div class="ai-widget-header"><i class="fas fa-robot"></i>
                            <h3>المساعد الذكي للكورس</h3><span class="ai-live-badge">متاح الآن</span>
                        </div>
                        <div class="ai-chat-area" id="aiChatArea">
                            <div class="ai-bubble bot">مرحباً بك! 👋 اسألني عن أي مفهوم في JavaScript!</div>
                        </div>
                        <div class="ai-input-row">
                            <input class="ai-input" id="aiInput" type="text" placeholder="اكتب سؤالك هنا...">
                            <button class="btn-ai-send" id="aiSendBtn" onclick="sendAiMessage()"><i
                                    class="fas fa-paper-plane"></i>إرسال</button>
                        </div>
                    </div>
                </div>

                <div id="materialsTab" class="tab-content">
                    <div class="materials-header">
                        <h2>المواد التعليمية</h2>
                    </div>
                    @php $allMaterials = $course->activeLessons->flatMap->materials; @endphp
                    @if ($allMaterials->isEmpty())
                        <p style="color:rgba(255,255,255,.5);text-align:center;padding:3rem">لا توجد مواد متاحة حالياً
                        </p>
                    @else
                        <div class="materials-grid">
                            @foreach ($allMaterials as $material)
                                <div class="material-card">
                                    <div class="material-icon"><i
                                            class="fas {{ $material->type === 'pdf' ? 'fa-file-pdf' : ($material->type === 'code' ? 'fa-code' : ($material->type === 'zip' ? 'fa-file-archive' : 'fa-link')) }}"></i>
                                    </div>
                                    <span
                                        class="material-type type-{{ $material->type }}">{{ strtoupper($material->type) }}</span>
                                    <h4>{{ $material->title }}</h4>
                                    <p>{{ $material->description }}</p>
                                    @if ($material->file_path)
                                        <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank"
                                            class="btn-download"><i class="fas fa-download"></i>تحميل</a>
                                    @elseif($material->file_url)
                                        <a href="{{ $material->file_url }}" target="_blank" class="btn-download"><i
                                                class="fas fa-external-link-alt"></i>عرض</a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div id="assignmentsTab" class="tab-content">
                    <div class="assignments-header">
                        <h2>الواجبات</h2>
                    </div>
                    @php $allAssignments = $course->activeLessons->flatMap->assignments; @endphp
                    @if ($allAssignments->isEmpty())
                        <p style="color:rgba(255,255,255,.5);text-align:center;padding:3rem">لا توجد واجبات حالياً</p>
                    @else
                        <div class="assignments-list">
                            @foreach ($allAssignments as $assignment)
                                @php $submitted = in_array($assignment->id, $submittedIds); @endphp
                                <div class="assignment-card" id="asgn-{{ $assignment->id }}">
                                    <div class="assignment-card-top">
                                        <div class="assignment-title-row">
                                            <h4>{{ $assignment->title }}</h4>
                                            <div class="assignment-meta-row">
                                                <span><i class="fas fa-clock"></i>مدة التسليم:
                                                    {{ $assignment->deadline_days }} أيام</span>
                                                <span><i class="fas fa-star"></i>الدرجة: {{ $assignment->points }}
                                                    نقطة</span>
                                            </div>
                                        </div>
                                        <span
                                            class="assignment-status {{ $submitted ? 'status-submitted' : 'status-pending' }}">{{ $submitted ? 'تم التسليم' : 'قيد الانتظار' }}</span>
                                    </div>
                                    <div class="assignment-card-body">
                                        <p class="assignment-desc">{{ $assignment->description }}</p>
                                        <div class="assignment-actions">
                                            <button class="btn-submit"
                                                onclick="openUploadModal({{ $assignment->id }})"
                                                {{ $submitted ? 'disabled' : '' }}>
                                                <i class="fas {{ $submitted ? 'fa-check' : 'fa-upload' }}"></i>
                                                {{ $submitted ? 'تم الرفع' : 'رفع الحل' }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </main>
        </div>
    </div>

    <div class="modal" id="uploadModal">
        <div class="upload-modal-inner">
            <button class="modal-close" style="position:absolute;top:1rem;left:1rem"
                onclick="closeUploadModal()">✕</button>
            <h2>رفع الحل</h2>
            <div class="form-group" style="margin-top:1.5rem">
                <div class="file-drop-zone" id="dropZone" onclick="document.getElementById('fileInput').click()">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>اسحب وأفلت أو <span>انقر للاختيار</span></p>
                    <p style="font-size:.8rem;margin-top:.3rem">.js · .zip · .pdf · .doc · .docx</p>
                    <input type="file" id="fileInput" accept=".js,.zip,.pdf,.doc,.docx" style="display:none"
                        onchange="handleFileSelect(this)">
                </div>
                <div id="fileSelectedBox"
                    style="display:none;margin-top:.8rem;color:#4ade80;font-size:.9rem;font-weight:600">
                    <i class="fas fa-check-circle"></i> <span id="fileSelectedName"></span>
                </div>
            </div>
            <div class="form-group">
                <label style="color:rgba(255,255,255,.85);font-weight:600;display:block;margin-bottom:.45rem">ملاحظات
                    (اختياري)</label>
                <textarea id="uploadNotes" class="form-control" rows="3" placeholder="أضف أي ملاحظات..."></textarea>
            </div>
            <div class="modal-actions">
                <button class="btn-modal-back" onclick="closeUploadModal()">إلغاء</button>
                <button class="btn-modal-next" onclick="submitAssignment()"><i
                        class="fas fa-paper-plane"></i>إرسال</button>
            </div>
        </div>
    </div>
    <div class="toast-container" id="toastContainer"></div>

  
    <script>
        const LESSONS = @json($lessonsJson);
        const COMPLETED_IDS = @json($completedIds);
        const SUBMITTED_IDS = @json($submittedIds);
        const CSRF_TOKEN = '{{ csrf_token() }}';
        const ROUTES = {
            markComplete: '{{ route('lessons.complete') }}',
            quizSubmit: '/quizzes/{quiz}/submit',
            assignSubmit: '/assignments/{assignment}/submit'
        };
        let currentAsgnId = null;

        function toggleLesson(id) {
            const b = document.getElementById('lesson-block-' + id);
            if (!b || b.classList.contains('locked')) return;
            b.classList.toggle('open')
        }

        function toggleQuiz(id) {
            const a = document.getElementById('quiz-acc-' + id);
            if (a) a.classList.toggle('open')
        }

        async function submitQuiz(quizId, passingScore, lessonId, questionIds) {
            const answers = {};
            let ok = true;
            questionIds.forEach(q => {
                const c = document.querySelector('input[name="quiz_' + quizId + '_q' + q + '"]:checked');
                if (c) answers[q] = c.value;
                else ok = false
            });
            if (!ok) {
                showToast('يرجى الإجابة على جميع الأسئلة', 'error');
                return
            }
            const fe = document.getElementById('quiz-form-' + quizId),
                sb = fe?.querySelector('.btn-submit-quiz');
            if (sb) {
                sb.disabled = true;
                sb.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جارٍ التصحيح...'
            }
            const res = await postJSON(ROUTES.quizSubmit.replace('{quiz}', quizId), {
                answers
            });
            const rd = document.getElementById('quiz-result-' + quizId);
            rd.style.display = 'block';
            if (res.passed) {
                rd.className = 'quiz-result passed';
                rd.innerHTML = '<div class="score-big">🎉 ' + res.score + '%</div><p>أحسنت! اجتزت الاختبار (' + res
                    .correct + '/' + res.total + ' صحيح)</p>';
                if (fe) fe.style.display = 'none';
                const ps = document.querySelector('#quiz-acc-' + lessonId + ' .quiz-pass-score');
                if (ps) ps.outerHTML =
                    '<span style="color:#4ade80;font-size:.78rem;font-weight:700"><i class="fas fa-check-circle"></i> اجتزته ✅</span>';
                if (!COMPLETED_IDS.includes(lessonId)) {
                    COMPLETED_IDS.push(lessonId);
                    await postJSON(ROUTES.markComplete, {
                        lesson_id: lessonId
                    });
                    updateProgressUI();
                    markLessonBlockDone(lessonId)
                }
                const idx = LESSONS.findIndex(l => l.id === lessonId),
                    next = LESSONS[idx + 1];
                if (next) {
                    next.unlocked = true;
                    unlockLessonBlock(next.id)
                }
                showToast('🎉 ممتاز! الدرس التالي أصبح متاحاً', 'success');
            } else {
                rd.className = 'quiz-result failed';
                rd.innerHTML = '<div class="score-big">😔 ' + res.score + '%</div><p>لم تجتز. درجة النجاح ' +
                    passingScore + '% (أصبت بـ ' + res.correct + '/' + res.total +
                    ')</p><button class="btn-submit-quiz" onclick="retryQuiz(' + quizId + ',' + passingScore + ',' +
                    lessonId + ',[' + questionIds.join(',') +
                    '])" style="margin-top:1rem"><i class="fas fa-redo"></i> إعادة المحاولة</button>';
                if (sb) {
                    sb.disabled = false;
                    sb.innerHTML = '<i class="fas fa-paper-plane"></i> إرسال الإجابات'
                }
                showToast('لم تنجح! حاول مرة أخرى (' + res.score + '/' + passingScore + '%)', 'error');
            }
        }

        function retryQuiz(quizId, passingScore, lessonId, questionIds) {
            questionIds.forEach(q => {
                document.querySelectorAll('input[name="quiz_' + quizId + '_q' + q + '"]').forEach(r => r.checked =
                    false)
            });
            const rd = document.getElementById('quiz-result-' + quizId);
            rd.style.display = 'none';
            rd.className = 'quiz-result';
            const fe = document.getElementById('quiz-form-' + quizId);
            if (fe) {
                fe.style.display = 'block';
                const b = fe.querySelector('.btn-submit-quiz');
                if (b) {
                    b.disabled = false;
                    b.innerHTML = '<i class="fas fa-paper-plane"></i> إرسال الإجابات'
                }
            }
        }

        function unlockLessonBlock(id) {
            const b = document.getElementById('lesson-block-' + id);
            if (!b) return;
            b.classList.remove('locked');
            b.querySelector('.lock-overlay')?.remove();
            const n = b.querySelector('.lesson-block-num');
            if (n) {
                n.classList.remove('locked-num');
                n.innerHTML = LESSONS.findIndex(l => l.id === id) + 1
            }
            b.style.transition = 'border-color .5s,box-shadow .5s';
            b.style.borderColor = 'rgba(124,58,237,.5)';
            b.style.boxShadow = '0 0 30px rgba(124,58,237,.2)';
            setTimeout(() => {
                b.style.borderColor = '';
                b.style.boxShadow = ''
            }, 2000)
        }

        function markLessonBlockDone(id) {
            const b = document.getElementById('lesson-block-' + id);
            if (!b) return;
            b.classList.add('completed');
            const n = b.querySelector('.lesson-block-num');
            if (n) {
                n.classList.add('done');
                n.classList.remove('locked-num');
                n.innerHTML = '<i class="fas fa-check"></i>'
            }
        }

        function updateProgressUI() {
            const total = LESSONS.length,
                done = COMPLETED_IDS.length,
                pct = Math.round(done / total * 100);
            document.getElementById('progressFill').style.width = pct + '%';
            document.getElementById('progressPct').textContent = pct + '%';
            document.getElementById('progressLabel').textContent = done + ' / ' + total + ' دروس';
            document.getElementById('completedCountEl').textContent = done;
            document.getElementById('progressSidebar').textContent = pct + '%'
        }

        function openUploadModal(id) {
            currentAsgnId = id;
            document.getElementById('uploadModal').classList.add('open');
            document.body.style.overflow = 'hidden'
        }

        function closeUploadModal() {
            document.getElementById('uploadModal').classList.remove('open');
            document.body.style.overflow = '';
            document.getElementById('fileInput').value = '';
            document.getElementById('uploadNotes').value = '';
            document.getElementById('fileSelectedBox').style.display = 'none'
        }

        function handleFileSelect(i) {
            if (i.files?.[0]) {
                document.getElementById('fileSelectedName').textContent = i.files[0].name;
                document.getElementById('fileSelectedBox').style.display = 'flex'
            }
        }

        async function submitAssignment() {
            const file = document.getElementById('fileInput').files[0];
            if (!file) {
                showToast('اختر ملفاً أولاً', 'error');
                return
            }
            const fd = new FormData();
            fd.append('file', file);
            fd.append('notes', document.getElementById('uploadNotes').value);
            fd.append('_token', CSRF_TOKEN);
            const res = await fetch(ROUTES.assignSubmit.replace('{assignment}', currentAsgnId), {
                method: 'POST',
                body: fd
            });
            const data = await res.json();
            if (data.success) {
                SUBMITTED_IDS.push(currentAsgnId);
                const s = document.querySelector('#asgn-' + currentAsgnId + ' .assignment-status');
                if (s) {
                    s.textContent = 'تم التسليم';
                    s.className = 'assignment-status status-submitted'
                }
                const b = document.querySelector('#asgn-' + currentAsgnId + ' .btn-submit');
                if (b) {
                    b.disabled = true;
                    b.innerHTML = '<i class="fas fa-check"></i> تم الرفع'
                }
                showToast('✅ تم رفع الحل بنجاح!', 'success');
                closeUploadModal()
            } else showToast('حدث خطأ، حاول مجدداً', 'error')
        }

        async function sendAiMessage() {
            const input = document.getElementById('aiInput'),
                msg = input.value.trim();
            if (!msg) return;
            const btn = document.getElementById('aiSendBtn');
            input.value = '';
            btn.disabled = true;
            appendBubble(msg, 'user');
            const typing = appendBubble('...', 'bot');
            try {
                const res = await fetch('https://api.anthropic.com/v1/messages', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        model: 'claude-sonnet-4-20250514',
                        max_tokens: 1000,
                        system: 'أنت مساعد ذكي متخصص في تعليم JavaScript. أجب باللغة العربية بشكل مختصر وواضح.',
                        messages: [{
                            role: 'user',
                            content: msg
                        }]
                    })
                });
                const data = await res.json();
                typing.textContent = data?.content?.[0]?.text || 'عذراً، حدث خطأ.'
            } catch {
                typing.textContent = 'عذراً، تعذّر الاتصال.'
            }
            btn.disabled = false;
            document.getElementById('aiChatArea').scrollTop = 9999
        }

        function appendBubble(text, role) {
            const area = document.getElementById('aiChatArea'),
                div = document.createElement('div');
            div.className = 'ai-bubble ' + role;
            div.textContent = text;
            area.appendChild(div);
            area.scrollTop = 9999;
            return div
        }
        async function postJSON(url, body) {
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify(body)
            });
            return res.json()
        }

        function showToast(msg, type = 'info') {
            const icons = {
                    success: 'fa-check-circle',
                    info: 'fa-info-circle',
                    error: 'fa-exclamation-circle'
                },
                c = document.getElementById('toastContainer'),
                t = document.createElement('div');
            t.className = 'toast ' + type;
            t.innerHTML = '<i class="fas ' + icons[type] + '"></i> ' + msg;
            c.appendChild(t);
            setTimeout(() => t.remove(), 4000)
        }

        document.querySelectorAll('.tab-btn').forEach(b => b.addEventListener('click', () => {
            document.querySelectorAll('.tab-btn,.tab-content').forEach(x => x.classList.remove('active'));
            b.classList.add('active');
            document.getElementById(b.dataset.tab + 'Tab').classList.add('active')
        }));
        document.querySelectorAll('[data-tab-link]').forEach(a => a.addEventListener('click', e => {
            e.preventDefault();
            document.querySelector('.tab-btn[data-tab="' + a.dataset.tabLink + '"]')?.click();
            document.getElementById('mobileMenu').classList.remove('active')
        }));
        const ham = document.getElementById('hamburger'),
            menu = document.getElementById('mobileMenu');
        ham.addEventListener('click', () => {
            ham.classList.toggle('active');
            menu.classList.toggle('active')
        });
        document.addEventListener('click', e => {
            if (!ham.contains(e.target) && !menu.contains(e.target)) {
                ham.classList.remove('active');
                menu.classList.remove('active')
            }
        });
        window.addEventListener('scroll', () => document.getElementById('header').classList.toggle('scrolled', scrollY >
            20), {
                passive: true
            });
        document.getElementById('uploadModal').addEventListener('click', e => {
            if (e.target === document.getElementById('uploadModal')) closeUploadModal()
        });
        const dz = document.getElementById('dropZone');
        dz.addEventListener('dragover', e => {
            e.preventDefault();
            dz.classList.add('drag-over')
        });
        dz.addEventListener('dragleave', () => dz.classList.remove('drag-over'));
        dz.addEventListener('drop', e => {
            e.preventDefault();
            dz.classList.remove('drag-over');
            const f = e.dataTransfer.files[0];
            if (f) {
                document.getElementById('fileSelectedName').textContent = f.name;
                document.getElementById('fileSelectedBox').style.display = 'flex';
                document.getElementById('fileInput').files = e.dataTransfer.files
            }
        });
        document.getElementById('aiInput').addEventListener('keydown', e => {
            if (e.key === 'Enter') sendAiMessage()
        });
        window.addEventListener('DOMContentLoaded', () => {
            const first = LESSONS.find(l => l.unlocked);
            if (first) {
                const b = document.getElementById('lesson-block-' + first.id);
                if (b && !b.classList.contains('locked')) b.classList.add('open')
            }
        });
        @if (session('welcome'))
            showToast('{{ session('welcome') }}', 'success');
        @endif
    </script>
</body>

</html>
