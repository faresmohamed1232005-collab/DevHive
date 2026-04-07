<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إضافة درس — EduLux Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root { --bg:#050a1a; --border:rgba(61,38,101,0.45); --purple:#7c3aed; --purple2:#a78bfa; --text:rgba(255,255,255,0.88); --muted:rgba(255,255,255,0.45); }
        body { font-family:'Cairo',sans-serif; background:var(--bg); color:var(--text); min-height:100vh; display:flex; }

        /* Sidebar */
        .admin-sidebar { width:260px; flex-shrink:0; background:linear-gradient(180deg,rgba(15,30,74,0.98) 0%,rgba(30,15,60,0.98) 100%); border-left:1px solid var(--border); display:flex; flex-direction:column; position:fixed; top:0; right:0; height:100vh; z-index:100; padding:1.5rem 0; }
        .admin-logo { padding:0 1.5rem 1.5rem; border-bottom:1px solid var(--border); margin-bottom:1.5rem; }
        .admin-logo a { display:flex; align-items:center; gap:.6rem; color:white; text-decoration:none; font-size:1.3rem; font-weight:800; }
        .admin-logo .badge { background:linear-gradient(135deg,#7c3aed,#a78bfa); color:white; font-size:.6rem; padding:.15rem .4rem; border-radius:4px; font-weight:700; }
        .nav-section { padding:0 1rem; margin-bottom:1.5rem; }
        .nav-section-title { color:var(--muted); font-size:.7rem; font-weight:700; text-transform:uppercase; padding:0 .5rem; margin-bottom:.5rem; letter-spacing:.08em; }
        .nav-item { display:flex; align-items:center; gap:.7rem; padding:.75rem 1rem; border-radius:10px; color:var(--muted); text-decoration:none; font-weight:600; font-size:.9rem; transition:all .25s; margin-bottom:.2rem; }
        .nav-item:hover, .nav-item.active { background:rgba(124,58,237,.2); color:var(--purple2); }
        .nav-item i { width:20px; text-align:center; }
        .admin-sidebar-footer { margin-top:auto; padding:1rem 1.5rem; border-top:1px solid var(--border); }
        .admin-user { display:flex; align-items:center; gap:.75rem; }
        .admin-user-av { width:38px; height:38px; background:linear-gradient(135deg,#1a3a6b,#7c3aed); border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700; color:white; flex-shrink:0; }
        .admin-user-info h5 { color:white; font-size:.88rem; font-weight:700; }
        .admin-user-info span { color:var(--muted); font-size:.75rem; }

        /* Main */
        .admin-main { margin-right:260px; flex:1; min-height:100vh; display:flex; flex-direction:column; }
        .admin-topbar { background:rgba(15,30,74,.95); border-bottom:1px solid var(--border); padding:1rem 2rem; display:flex; align-items:center; justify-content:space-between; position:sticky; top:0; z-index:50; backdrop-filter:blur(10px); }
        .admin-topbar h1 { color:white; font-size:1.25rem; font-weight:800; display:flex; align-items:center; gap:.5rem; }
        .admin-topbar h1 i { color:var(--purple2); }
        .btn-topbar { padding:.55rem 1.1rem; border-radius:8px; font-family:inherit; font-size:.85rem; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:.4rem; transition:all .25s; border:none; text-decoration:none; }
        .btn-topbar.ghost { background:transparent; color:var(--muted); border:1px solid var(--border); }
        .btn-topbar.ghost:hover { color:white; border-color:var(--purple2); }
        .admin-content { padding:2rem; flex:1; max-width:860px; }

        /* Form Cards */
        .section-card { background:linear-gradient(135deg,rgba(15,30,74,.9),rgba(30,15,60,.85)); border:1px solid var(--border); border-radius:16px; overflow:hidden; margin-bottom:1.5rem; }
        .section-card-head { padding:1.2rem 1.5rem; border-bottom:1px solid var(--border); }
        .section-card-head h3 { color:white; font-size:1.05rem; font-weight:700; display:flex; align-items:center; gap:.5rem; }
        .section-card-head h3 i { color:var(--purple2); }
        .section-card-body { padding:1.5rem; }
        .field { margin-bottom:1.1rem; }
        .field label { display:block; color:rgba(255,255,255,.8); font-size:.85rem; font-weight:700; margin-bottom:.4rem; }
        .field input, .field select, .field textarea {
            width:100%; padding:.8rem 1rem;
            background:rgba(26,58,82,.3); border:2px solid rgba(61,38,101,.3);
            border-radius:9px; color:white; font-family:inherit; font-size:.9rem;
            direction:rtl; transition:border-color .3s;
        }
        .field input:focus, .field select:focus, .field textarea:focus { outline:none; border-color:var(--purple2); }
        .field input::placeholder, .field textarea::placeholder { color:rgba(255,255,255,.25); }
        .field select option { background:#0f1e4a; }
        .form-row { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
        .toggle-wrap { display:flex; align-items:center; gap:.8rem; padding:.5rem 0; }
        .toggle-wrap input[type=checkbox] { width:auto; accent-color:var(--purple2); width:18px; height:18px; cursor:pointer; }
        .toggle-label { color:rgba(255,255,255,.7); font-size:.9rem; cursor:pointer; }
        .btn-save { padding:.85rem 2rem; background:linear-gradient(135deg,#1a3a6b,#7c3aed); color:white; border:none; border-radius:9px; font-family:inherit; font-weight:700; font-size:.95rem; cursor:pointer; transition:all .3s; display:inline-flex; align-items:center; gap:.5rem; }
        .btn-save:hover { box-shadow:0 6px 20px rgba(124,58,237,.4); transform:translateY(-1px); }
        .alert { padding:.85rem 1.2rem; border-radius:10px; margin-bottom:1.5rem; font-size:.88rem; font-weight:600; display:flex; align-items:center; gap:.5rem; }
        .alert.error { background:rgba(248,113,113,.1); border:1px solid rgba(248,113,113,.35); color:#f87171; }
        .hint { color:var(--muted); font-size:.78rem; margin-top:.3rem; }

        @media (max-width:900px) { .admin-sidebar { display:none; } .admin-main { margin-right:0; } .form-row { grid-template-columns:1fr; } }
    </style>
</head>
<body>

{{-- Sidebar --}}
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
        <a href="{{ route('admin.courses.lessons.index', $course) }}" class="nav-item active">
            <i class="fas fa-play-circle"></i>إدارة الدروس
        </a>
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

{{-- Main --}}
<div class="admin-main">
    <div class="admin-topbar">
        <h1><i class="fas fa-plus-circle"></i>إضافة درس جديد</h1>
        <a href="{{ route('admin.courses.lessons.index', $course) }}" class="btn-topbar ghost">
            <i class="fas fa-arrow-right"></i> رجوع
        </a>
    </div>

    <div class="admin-content">

        @if($errors->any())
        <div class="alert error">
            <i class="fas fa-exclamation-circle"></i>
            {{ $errors->first() }}
        </div>
        @endif

        <form action="{{ route('admin.courses.lessons.store', $course) }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- ── بيانات الدرس ── --}}
            <div class="section-card">
                <div class="section-card-head">
                    <h3><i class="fas fa-info-circle"></i>بيانات الدرس</h3>
                </div>
                <div class="section-card-body">

                    <div class="field">
                        <label>عنوان الدرس *</label>
                        <input type="text" name="title" value="{{ old('title') }}"
                               placeholder="مثال: مقدمة عن JavaScript" required>
                    </div>

                    <div class="field">
                        <label>وصف الدرس</label>
                        <textarea name="description" rows="3"
                                  placeholder="وصف مختصر للدرس...">{{ old('description') }}</textarea>
                    </div>

                    <div class="form-row">
                        <div class="field">
                            <label>نوع الفيديو</label>
                            <select name="video_type" id="videoTypeSelect" onchange="toggleVideoInput()">
                                <option value="youtube" {{ old('video_type','youtube') === 'youtube' ? 'selected' : '' }}>
                                    YouTube
                                </option>
                                <option value="upload" {{ old('video_type') === 'upload' ? 'selected' : '' }}>
                                    رفع ملف فيديو
                                </option>
                            </select>
                        </div>
                        <div class="field" id="ytField">
                            <label>YouTube Video ID أو الرابط</label>
                            <input type="text" name="video_url" value="{{ old('video_url') }}"
                                   placeholder="مثال: dQw4w9WgXcQ">
                            <p class="hint">ضع الـ ID فقط أو الرابط الكامل</p>
                        </div>
                        <div class="field" id="uploadField" style="display:none">
                            <label>ملف الفيديو (mp4 / avi / mov)</label>
                            <input type="file" name="video_file" accept="video/*">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="field">
                            <label>المدة (بالدقائق)</label>
                            <input type="number" name="duration" value="{{ old('duration', 20) }}"
                                   min="1" placeholder="20">
                        </div>
                        <div class="field">
                            <label>الترتيب</label>
                            <input type="number" name="order" value="{{ old('order', 1) }}"
                                   min="0" placeholder="1">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="field">
                            <label>المستوى</label>
                            <select name="level">
                                <option value="basic"        {{ old('level','basic') === 'basic'        ? 'selected' : '' }}>أساسي</option>
                                <option value="intermediate" {{ old('level') === 'intermediate'         ? 'selected' : '' }}>متوسط</option>
                                <option value="advanced"     {{ old('level') === 'advanced'             ? 'selected' : '' }}>متقدم</option>
                            </select>
                        </div>
                        <div class="field" style="display:flex;align-items:flex-end;padding-bottom:.3rem">
                            <div class="toggle-wrap">
                                <input type="checkbox" name="is_active" id="isActive" value="1" checked>
                                <label class="toggle-label" for="isActive">الدرس مفعّل ومرئي للطلاب</label>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <button type="submit" class="btn-save">
                <i class="fas fa-plus-circle"></i> إضافة الدرس
            </button>

        </form>

    </div>
</div>

<script>
function toggleVideoInput() {
    const type = document.getElementById('videoTypeSelect').value;
    document.getElementById('ytField').style.display     = type === 'youtube' ? '' : 'none';
    document.getElementById('uploadField').style.display = type === 'upload'  ? '' : 'none';
}
toggleVideoInput();
</script>

</body>
</html>