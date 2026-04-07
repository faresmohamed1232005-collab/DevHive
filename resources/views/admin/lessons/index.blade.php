<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إدارة الدروس — EduLux Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root { --bg:#050a1a; --border:rgba(61,38,101,0.45); --purple:#7c3aed; --purple2:#a78bfa; --text:rgba(255,255,255,0.88); --muted:rgba(255,255,255,0.45); }
        body { font-family:'Cairo',sans-serif; background:var(--bg); color:var(--text); min-height:100vh; display:flex; }
        .admin-sidebar { width:260px; flex-shrink:0; background:linear-gradient(180deg,rgba(15,30,74,0.98) 0%,rgba(30,15,60,0.98) 100%); border-left:1px solid var(--border); display:flex; flex-direction:column; position:fixed; top:0; right:0; height:100vh; z-index:100; padding:1.5rem 0; }
        .admin-logo { padding:0 1.5rem 1.5rem; border-bottom:1px solid var(--border); margin-bottom:1.5rem; }
        .admin-logo a { display:flex; align-items:center; gap:.6rem; color:white; text-decoration:none; font-size:1.3rem; font-weight:800; }
        .admin-logo .badge { background:linear-gradient(135deg,#7c3aed,#a78bfa); color:white; font-size:.6rem; padding:.15rem .4rem; border-radius:4px; font-weight:700; }
        .nav-section { padding:0 1rem; margin-bottom:1.5rem; }
        .nav-section-title { color:var(--muted); font-size:.7rem; font-weight:700; text-transform:uppercase; padding:0 .5rem; margin-bottom:.5rem; letter-spacing:.08em; }
        .nav-item { display:flex; align-items:center; gap:.7rem; padding:.75rem 1rem; border-radius:10px; color:var(--muted); text-decoration:none; font-weight:600; font-size:.9rem; transition:all .25s; margin-bottom:.2rem; }
        .nav-item:hover, .nav-item.active { background:rgba(124,58,237,.2); color:var(--purple2); }
        .nav-item i { width:20px; text-align:center; font-size:.95rem; }
        .admin-sidebar-footer { margin-top:auto; padding:1rem 1.5rem; border-top:1px solid var(--border); }
        .admin-user { display:flex; align-items:center; gap:.75rem; }
        .admin-user-av { width:38px; height:38px; background:linear-gradient(135deg,#1a3a6b,#7c3aed); border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:1rem; color:white; flex-shrink:0; }
        .admin-user-info h5 { color:white; font-size:.88rem; font-weight:700; }
        .admin-user-info span { color:var(--muted); font-size:.75rem; }
        .admin-main { margin-right:260px; flex:1; min-height:100vh; display:flex; flex-direction:column; }
        .admin-topbar { background:rgba(15,30,74,.95); border-bottom:1px solid var(--border); padding:1rem 2rem; display:flex; align-items:center; justify-content:space-between; position:sticky; top:0; z-index:50; backdrop-filter:blur(10px); }
        .admin-topbar h1 { color:white; font-size:1.25rem; font-weight:800; display:flex; align-items:center; gap:.5rem; }
        .admin-topbar h1 i { color:var(--purple2); }
        .topbar-actions { display:flex; align-items:center; gap:.75rem; }
        .btn-topbar { padding:.55rem 1.1rem; border-radius:8px; font-family:inherit; font-size:.85rem; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:.4rem; transition:all .25s; border:none; text-decoration:none; }
        .btn-topbar.primary { background:linear-gradient(135deg,#1a3a6b,#7c3aed); color:white; }
        .btn-topbar.primary:hover { box-shadow:0 4px 15px rgba(124,58,237,.4); transform:translateY(-1px); }
        .btn-topbar.ghost { background:transparent; color:var(--muted); border:1px solid var(--border); }
        .btn-topbar.ghost:hover { color:white; border-color:var(--purple2); }
        .admin-content { padding:2rem; flex:1; }
        .admin-card { background:linear-gradient(135deg,rgba(15,30,74,.85),rgba(30,15,60,.8)); border:1px solid var(--border); border-radius:16px; overflow:hidden; }
        .lesson-row { display:flex; align-items:center; gap:1rem; padding:.9rem 1.5rem; border-bottom:1px solid rgba(255,255,255,.04); transition:background .2s; }
        .lesson-row:hover { background:rgba(124,58,237,.06); }
        .lesson-row:last-child { border-bottom:none; }
        .lesson-num-badge { width:32px; height:32px; background:rgba(124,58,237,.2); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:.8rem; font-weight:700; color:var(--purple2); flex-shrink:0; }
        .lesson-row-info { flex:1; }
        .lesson-row-info h5 { color:white; font-size:.9rem; font-weight:700; margin-bottom:.15rem; }
        .lesson-row-info span { color:var(--muted); font-size:.75rem; }
        .lesson-row-actions { display:flex; gap:.5rem; }
        .btn-icon { width:34px; height:34px; border-radius:8px; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:.85rem; transition:all .25s; text-decoration:none; }
        .btn-icon.edit { background:rgba(59,130,246,.15); color:#93c5fd; }
        .btn-icon.del  { background:rgba(248,113,113,.15); color:#f87171; }
        .btn-icon:hover { transform:translateY(-1px); filter:brightness(1.2); }
        .alert { padding:.85rem 1.2rem; border-radius:10px; margin-bottom:1.5rem; font-size:.88rem; font-weight:600; display:flex; align-items:center; gap:.5rem; }
        .alert.success { background:rgba(74,222,128,.1); border:1px solid rgba(74,222,128,.35); color:#4ade80; }
        .empty-state { text-align:center; padding:4rem 2rem; color:var(--muted); }
        .empty-state i { font-size:3rem; margin-bottom:1rem; color:rgba(124,58,237,.4); display:block; }
        @media (max-width:900px) { .admin-sidebar { display:none; } .admin-main { margin-right:0; } }
    </style>
</head>
<body>

<aside class="admin-sidebar">
    <div class="admin-logo">
        <a href="/admin/dashboard">
            <i class="fas fa-graduation-cap" style="color:#a78bfa"></i>
            EduLux <span class="badge">ADMIN</span>
        </a>
    </div>
    <div class="nav-section">
        <div class="nav-section-title">الرئيسية</div>
        <a href="/admin/dashboard" class="nav-item"><i class="fas fa-chart-pie"></i>الإحصائيات</a>
    </div>
    <div class="nav-section">
        <div class="nav-section-title">المحتوى</div>
        <a href="{{ route('admin.courses.lessons.index', $course) }}" class="nav-item active"><i class="fas fa-play-circle"></i>إدارة الدروس</a>
        <a href="/admin/dashboard#submissions" class="nav-item"><i class="fas fa-file-upload"></i>حلول الطلاب</a>
    </div>
    <div class="nav-section">
        <div class="nav-section-title">النظام</div>
        <a href="/" class="nav-item"><i class="fas fa-globe"></i>الموقع الرئيسي</a>
    </div>
    <div class="admin-sidebar-footer">
        <div class="admin-user">
            <div class="admin-user-av">{{ mb_substr(auth()->user()->name, 0, 1) }}</div>
            <div class="admin-user-info">
                <h5>{{ auth()->user()->name }}</h5>
                <span>مدير المنصة</span>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST" style="margin-top:.8rem">
            @csrf
            <button type="submit" class="btn-topbar ghost" style="width:100%">
                <i class="fas fa-sign-out-alt"></i>خروج
            </button>
        </form>
    </div>
</aside>

<div class="admin-main">
    <div class="admin-topbar">
        <h1><i class="fas fa-play-circle"></i>دروس: {{ $course->title }}</h1>
        <div class="topbar-actions">
            <a href="{{ route('admin.courses.lessons.create', $course) }}" class="btn-topbar primary">
                <i class="fas fa-plus"></i> إضافة درس
            </a>
            <a href="/admin/dashboard" class="btn-topbar ghost">
                <i class="fas fa-arrow-right"></i> الداشبورد
            </a>
        </div>
    </div>

    <div class="admin-content">

        @if(session('success'))
        <div class="alert success"><i class="fas fa-check-circle"></i>{{ session('success') }}</div>
        @endif

        <div class="admin-card">
            @forelse($lessons as $lesson)
            <div class="lesson-row">
                <div class="lesson-num-badge">{{ $lesson->order }}</div>
                <div class="lesson-row-info">
                    <h5>{{ $lesson->title }}</h5>
                    <span>
                        {{ $lesson->duration }} دقيقة ·
                        {{ $lesson->video_type === 'youtube' ? 'YouTube' : 'ملف مرفوع' }} ·
                        {{ $lesson->quiz ? 'كويز ✅' : 'بدون كويز' }} ·
                        {{ $lesson->materials->count() }} مواد ·
                        {{ $lesson->assignments->count() }} واجبات ·
                        {{ $lesson->is_active ? '🟢 مفعّل' : '🔴 مخفي' }}
                    </span>
                </div>
                <div class="lesson-row-actions">
                    <a href="{{ route('admin.courses.lessons.edit', [$course, $lesson]) }}" class="btn-icon edit">
                        <i class="fas fa-pen"></i>
                    </a>
                    <form action="{{ route('admin.courses.lessons.destroy', [$course, $lesson]) }}"
                          method="POST" onsubmit="return confirm('حذف الدرس نهائياً؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-icon del"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="fas fa-video-slash"></i>
                <p>لا توجد دروس بعد — اضغط "إضافة درس" للبدء</p>
            </div>
            @endforelse
        </div>

    </div>
</div>

</body>
</html>