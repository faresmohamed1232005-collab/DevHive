{{-- resources/views/admin/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>لوحة التحكم — EduLux</title>
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
        .topbar-actions { display:flex; align-items:center; gap:.75rem; }
        .btn-topbar { padding:.55rem 1.1rem; border-radius:8px; font-family:inherit; font-size:.85rem; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:.4rem; transition:all .25s; border:none; text-decoration:none; }
        .btn-topbar.primary { background:linear-gradient(135deg,#1a3a6b,#7c3aed); color:white; }
        .btn-topbar.primary:hover { box-shadow:0 4px 15px rgba(124,58,237,.4); transform:translateY(-1px); }
        .btn-topbar.ghost { background:transparent; color:var(--muted); border:1px solid var(--border); }
        .btn-topbar.ghost:hover { color:white; border-color:var(--purple2); }
        .admin-content { padding:2rem; flex:1; }

        /* Stats */
        .stats-row { display:grid; grid-template-columns:repeat(auto-fill,minmax(180px,1fr)); gap:1.2rem; margin-bottom:2rem; }
        .stat-card-admin { background:linear-gradient(135deg,rgba(15,30,74,.8),rgba(42,22,84,.7)); border:1px solid var(--border); border-radius:14px; padding:1.3rem; display:flex; align-items:center; gap:1rem; position:relative; overflow:hidden; }
        .stat-card-admin::before { content:''; position:absolute; top:0; left:0; right:0; height:2px; background:linear-gradient(90deg,transparent,var(--purple),var(--purple2),transparent); }
        .stat-icon { width:48px; height:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.3rem; flex-shrink:0; }
        .stat-icon.blue   { background:rgba(59,130,246,.2); color:#93c5fd; }
        .stat-icon.purple { background:rgba(124,58,237,.2); color:#a78bfa; }
        .stat-icon.green  { background:rgba(74,222,128,.15); color:#4ade80; }
        .stat-icon.orange { background:rgba(251,146,60,.15); color:#fb923c; }
        .stat-icon.red    { background:rgba(248,113,113,.15); color:#f87171; }
        .stat-icon.teal   { background:rgba(45,212,191,.15); color:#2dd4bf; }
        .stat-card-admin h3 { color:white; font-size:1.6rem; font-weight:900; line-height:1; margin-bottom:.2rem; }
        .stat-card-admin p { color:var(--muted); font-size:.78rem; font-weight:600; }

        /* Tabs */
        .section-tabs { display:flex; gap:.5rem; border-bottom:1px solid var(--border); margin-bottom:1.5rem; }
        .section-tab { padding:.7rem 1.4rem; background:none; border:none; color:var(--muted); font-family:inherit; font-size:.9rem; font-weight:700; cursor:pointer; border-bottom:2px solid transparent; margin-bottom:-1px; transition:all .25s; }
        .section-tab.active, .section-tab:hover { color:var(--purple2); border-bottom-color:var(--purple); }
        .tab-panel { display:none; }
        .tab-panel.active { display:block; }

        /* Table */
        .data-table { width:100%; border-collapse:collapse; }
        .data-table th { padding:.75rem 1rem; text-align:right; color:var(--muted); font-size:.75rem; font-weight:700; text-transform:uppercase; border-bottom:1px solid var(--border); letter-spacing:.05em; }
        .data-table td { padding:.85rem 1rem; border-bottom:1px solid rgba(255,255,255,.04); font-size:.85rem; vertical-align:middle; }
        .data-table tr:last-child td { border-bottom:none; }
        .data-table tr:hover td { background:rgba(124,58,237,.04); }
        .admin-card { background:linear-gradient(135deg,rgba(15,30,74,.85),rgba(30,15,60,.8)); border:1px solid var(--border); border-radius:16px; overflow:hidden; margin-bottom:1.5rem; }
        .admin-card-head { padding:1.2rem 1.5rem; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
        .admin-card-head h3 { color:white; font-size:1rem; font-weight:700; display:flex; align-items:center; gap:.5rem; }
        .admin-card-head h3 i { color:var(--purple2); }
        .admin-card-body { padding:0; }

        /* Badges */
        .status-badge { padding:.25rem .75rem; border-radius:20px; font-size:.7rem; font-weight:700; white-space:nowrap; }
        .status-badge.pending  { background:rgba(251,146,60,.15); color:#fb923c; }
        .status-badge.accepted { background:rgba(74,222,128,.15); color:#4ade80; }
        .status-badge.rejected { background:rgba(248,113,113,.15); color:#f87171; }
        .status-badge.reviewed { background:rgba(99,102,241,.15); color:#a5b4fc; }
        .score-badge { padding:.25rem .75rem; border-radius:20px; font-size:.75rem; font-weight:800; }
        .score-badge.passed { background:rgba(74,222,128,.15); color:#4ade80; }
        .score-badge.failed { background:rgba(248,113,113,.15); color:#f87171; }

        /* Student avatar */
        .student-cell { display:flex; align-items:center; gap:.6rem; }
        .st-av { width:30px; height:30px; border-radius:50%; background:linear-gradient(135deg,#1a3a6b,#7c3aed); display:flex; align-items:center; justify-content:center; font-size:.8rem; font-weight:700; color:white; flex-shrink:0; }

        /* Review form inline */
        .review-form { display:flex; gap:.5rem; align-items:center; }
        .review-form select { background:rgba(26,58,82,.5); border:1px solid rgba(61,38,101,.4); border-radius:6px; color:white; font-family:inherit; font-size:.78rem; padding:.3rem .5rem; direction:rtl; }
        .review-form input { background:rgba(26,58,82,.5); border:1px solid rgba(61,38,101,.4); border-radius:6px; color:white; font-family:inherit; font-size:.78rem; padding:.3rem .5rem; width:60px; text-align:center; direction:ltr; }
        .btn-review { padding:.3rem .7rem; background:linear-gradient(135deg,#1a3a6b,#7c3aed); color:white; border:none; border-radius:6px; font-family:inherit; font-size:.75rem; font-weight:700; cursor:pointer; }

        /* Download link */
        .dl-link { color:var(--purple2); text-decoration:none; font-size:.8rem; display:inline-flex; align-items:center; gap:.3rem; }
        .dl-link:hover { color:white; }

        /* Alert */
        .alert { padding:.85rem 1.2rem; border-radius:10px; margin-bottom:1.5rem; font-size:.88rem; font-weight:600; display:flex; align-items:center; gap:.5rem; }
        .alert.success { background:rgba(74,222,128,.1); border:1px solid rgba(74,222,128,.35); color:#4ade80; }

        /* Latest students grid */
        .grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-bottom:2rem; }
        .data-row { display:flex; align-items:center; justify-content:space-between; padding:.7rem 1.5rem; border-bottom:1px solid rgba(255,255,255,.04); }
        .data-row:last-child { border-bottom:none; }
        .data-row-info h5 { color:white; font-size:.88rem; font-weight:700; margin-bottom:.15rem; }
        .data-row-info span { color:var(--muted); font-size:.75rem; }

        /* Empty */
        .empty-state { text-align:center; padding:3rem; color:var(--muted); }
        .empty-state i { font-size:2.5rem; display:block; margin-bottom:.75rem; opacity:.3; }

        @media (max-width:900px) { .admin-sidebar { display:none; } .admin-main { margin-right:0; } .grid-2 { grid-template-columns:1fr; } }
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
        <a href="/admin/dashboard" class="nav-item active"><i class="fas fa-chart-pie"></i>الإحصائيات</a>
    </div>
    <div class="nav-section">
        <div class="nav-section-title">المحتوى</div>
        @if(isset($course) && $course)
        <a href="{{ route('admin.courses.lessons.index', $course) }}" class="nav-item">
            <i class="fas fa-play-circle"></i>إدارة الدروس
        </a>
        @endif
        <a href="#submissions" class="nav-item" onclick="switchTab('submissions')">
            <i class="fas fa-file-upload"></i>حلول الطلاب
        </a>
        <a href="#quizzes" class="nav-item" onclick="switchTab('quizzes')">
            <i class="fas fa-question-circle"></i>نتائج الكويزات
        </a>
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
        <h1><i class="fas fa-chart-pie"></i>لوحة التحكم</h1>
        <div class="topbar-actions">
            @if(isset($course) && $course)
            <a href="{{ route('admin.courses.lessons.create', $course) }}" class="btn-topbar primary">
                <i class="fas fa-plus"></i> درس جديد
            </a>
            @endif
        </div>
    </div>

    <div class="admin-content">

        @if(session('success'))
        <div class="alert success"><i class="fas fa-check-circle"></i>{{ session('success') }}</div>
        @endif

        {{-- Stats --}}
        <div class="stats-row">
            <div class="stat-card-admin">
                <div class="stat-icon blue"><i class="fas fa-users"></i></div>
                <div><h3>{{ $stats['students'] }}</h3><p>طالب مسجّل</p></div>
            </div>
            <div class="stat-card-admin">
                <div class="stat-icon purple"><i class="fas fa-book-open"></i></div>
                <div><h3>{{ $stats['courses'] }}</h3><p>كورس</p></div>
            </div>
            <div class="stat-card-admin">
                <div class="stat-icon teal"><i class="fas fa-play-circle"></i></div>
                <div><h3>{{ $stats['lessons'] }}</h3><p>درس</p></div>
            </div>
            <div class="stat-card-admin">
                <div class="stat-icon green"><i class="fas fa-user-check"></i></div>
                <div><h3>{{ $stats['enrollments'] }}</h3><p>تسجيل</p></div>
            </div>
            <div class="stat-card-admin">
                <div class="stat-icon orange"><i class="fas fa-file-upload"></i></div>
                <div><h3>{{ $stats['submissions'] }}</h3><p>حل مرفوع</p></div>
            </div>
            <div class="stat-card-admin">
                <div class="stat-icon red"><i class="fas fa-clock"></i></div>
                <div><h3>{{ $stats['pending'] }}</h3><p>ينتظر المراجعة</p></div>
            </div>
        </div>

        {{-- آخر الطلاب --}}
        <div class="admin-card" style="margin-bottom:2rem">
            <div class="admin-card-head">
                <h3><i class="fas fa-users"></i>آخر الطلاب المسجلين</h3>
            </div>
            <div>
                @forelse($latestStudents as $s)
                <div class="data-row">
                    <div class="data-row-info">
                        <h5>{{ $s->name }}</h5>
                        <span>{{ $s->email }} · {{ $s->created_at->diffForHumans() }}</span>
                    </div>
                    <span style="color:#a78bfa;font-size:.75rem;font-weight:700">{{ $s->payment_method ?? '—' }}</span>
                </div>
                @empty
                <div class="empty-state"><i class="fas fa-users"></i>لا يوجد طلاب بعد</div>
                @endforelse
            </div>
        </div>

        {{-- Tabs: حلول الطلاب / نتائج الكويزات --}}
        <div class="section-tabs">
            <button class="section-tab active" onclick="switchTab('submissions')" id="tab-submissions">
                <i class="fas fa-file-upload"></i> حلول الواجبات
                <span style="background:rgba(251,146,60,.2);color:#fb923c;padding:.1rem .5rem;border-radius:10px;font-size:.72rem;margin-right:.3rem">
                    {{ $stats['pending'] }}
                </span>
            </button>
            <button class="section-tab" onclick="switchTab('quizzes')" id="tab-quizzes">
                <i class="fas fa-question-circle"></i> نتائج الكويزات
            </button>
        </div>

        {{-- ===== حلول الواجبات ===== --}}
        <div class="tab-panel active" id="panel-submissions">
            <div class="admin-card">
                <div class="admin-card-head">
                    <h3><i class="fas fa-file-upload"></i>جميع حلول الواجبات</h3>
                </div>
                <div class="admin-card-body">
                    @if($allSubmissions->isEmpty())
                    <div class="empty-state"><i class="fas fa-inbox"></i>لا توجد حلول مرفوعة بعد</div>
                    @else
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>الطالب</th>
                                <th>الواجب</th>
                                <th>الدرس</th>
                                <th>الملف</th>
                                <th>الحالة</th>
                                <th>الدرجة</th>
                                <th>التاريخ</th>
                                <th>مراجعة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allSubmissions as $sub)
                            <tr>
                                <td>
                                    <div class="student-cell">
                                        <div class="st-av">{{ mb_substr($sub->user->name ?? '?', 0, 1) }}</div>
                                        <div>
                                            <div style="color:white;font-weight:600;font-size:.85rem">{{ $sub->user->name ?? '—' }}</div>
                                            <div style="color:var(--muted);font-size:.75rem">{{ $sub->user->email ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="color:white;font-weight:600">{{ $sub->assignment->title ?? '—' }}</td>
                                <td style="color:var(--muted)">{{ $sub->assignment->lesson->title ?? '—' }}</td>
                                <td>
                                    @if($sub->file_path)
                                    <a href="{{ asset('storage/' . $sub->file_path) }}" target="_blank" class="dl-link">
                                        <i class="fas fa-download"></i>{{ $sub->original_filename ?? 'تحميل' }}
                                    </a>
                                    @else
                                    <span style="color:var(--muted)">—</span>
                                    @endif
                                </td>
                                <td><span class="status-badge {{ $sub->status }}">
                                    {{ $sub->status === 'pending' ? 'قيد المراجعة' : ($sub->status === 'accepted' ? 'مقبول' : ($sub->status === 'rejected' ? 'مرفوض' : 'تمت المراجعة')) }}
                                </span></td>
                                <td style="color:{{ $sub->grade ? '#4ade80' : 'var(--muted)' }};font-weight:700">
                                    {{ $sub->grade ? $sub->grade . '%' : '—' }}
                                </td>
                                <td style="color:var(--muted);font-size:.78rem">{{ $sub->created_at->format('d/m/Y') }}</td>
                                <td>
                                    {{-- فورم المراجعة --}}
                                    <form action="{{ route('admin.submissions.review', $sub) }}" method="POST">
                                        @csrf
                                        <div class="review-form">
                                            <input type="number" name="grade" placeholder="%" min="0" max="100"
                                                   value="{{ $sub->grade }}" required>
                                            <select name="status" required>
                                                <option value="accepted" {{ $sub->status === 'accepted' ? 'selected' : '' }}>✅ قبول</option>
                                                <option value="rejected" {{ $sub->status === 'rejected' ? 'selected' : '' }}>❌ رفض</option>
                                            </select>
                                            <button type="submit" class="btn-review">حفظ</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>

        {{-- ===== نتائج الكويزات ===== --}}
        <div class="tab-panel" id="panel-quizzes">
            <div class="admin-card">
                <div class="admin-card-head">
                    <h3><i class="fas fa-question-circle"></i>نتائج الكويزات</h3>
                </div>
                <div class="admin-card-body">
                    @if($allQuizAttempts->isEmpty())
                    <div class="empty-state"><i class="fas fa-clipboard-list"></i>لا توجد محاولات كويز بعد</div>
                    @else
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>الطالب</th>
                                <th>الكويز</th>
                                <th>الدرس</th>
                                <th>النتيجة</th>
                                <th>درجة النجاح</th>
                                <th>الحالة</th>
                                <th>التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allQuizAttempts as $attempt)
                            <tr>
                                <td>
                                    <div class="student-cell">
                                        <div class="st-av">{{ mb_substr($attempt->user->name ?? '?', 0, 1) }}</div>
                                        <div>
                                            <div style="color:white;font-weight:600;font-size:.85rem">{{ $attempt->user->name ?? '—' }}</div>
                                            <div style="color:var(--muted);font-size:.75rem">{{ $attempt->user->email ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="color:white;font-weight:600">{{ $attempt->quiz->title ?? '—' }}</td>
                                <td style="color:var(--muted)">{{ $attempt->quiz->lesson->title ?? '—' }}</td>
                                <td>
                                    <span style="font-size:1.1rem;font-weight:900;color:{{ $attempt->passed ? '#4ade80' : '#f87171' }}">
                                        {{ $attempt->score }}%
                                    </span>
                                </td>
                                <td style="color:var(--muted)">{{ $attempt->quiz->passing_score ?? 60 }}%</td>
                                <td>
                                    <span class="score-badge {{ $attempt->passed ? 'passed' : 'failed' }}">
                                        {{ $attempt->passed ? '✅ نجح' : '❌ رسب' }}
                                    </span>
                                </td>
                                <td style="color:var(--muted);font-size:.78rem">{{ $attempt->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

<script>
function switchTab(name) {
    // tabs
    document.querySelectorAll('.section-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    document.getElementById('tab-' + name).classList.add('active');
    document.getElementById('panel-' + name).classList.add('active');
}
</script>

</body>
</html>