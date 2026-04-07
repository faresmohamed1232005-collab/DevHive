{{-- resources/views/admin/lessons/edit.blade.php --}}
{{-- نفس هذا الملف يُستخدم لـ create أيضاً (غيّر الـ action والـ method) --}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تعديل الدرس — EduLux Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root { --bg:#050a1a; --surface:rgba(15,30,74,0.9); --border:rgba(61,38,101,0.45); --purple:#7c3aed; --purple2:#a78bfa; --text:rgba(255,255,255,0.88); --muted:rgba(255,255,255,0.45); }
        body { font-family:'Cairo',sans-serif; background:var(--bg); color:var(--text); min-height:100vh; }
        .page-wrap { max-width: 900px; margin: 0 auto; padding: 2rem 1.5rem; }
        .back-link { display:inline-flex; align-items:center; gap:.5rem; color:var(--muted); text-decoration:none; font-size:.9rem; font-weight:600; margin-bottom:1.5rem; transition:color .25s; }
        .back-link:hover { color:var(--purple2); }
        .page-title { color:white; font-size:1.6rem; font-weight:800; margin-bottom:2rem; }
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
        .btn-save { padding:.85rem 2rem; background:linear-gradient(135deg,#1a3a6b,#7c3aed); color:white; border:none; border-radius:9px; font-family:inherit; font-weight:700; font-size:.95rem; cursor:pointer; transition:all .3s; }
        .btn-save:hover { box-shadow:0 6px 20px rgba(124,58,237,.4); transform:translateY(-1px); }
        .btn-add { padding:.7rem 1.4rem; background:rgba(124,58,237,.2); color:var(--purple2); border:1px solid rgba(124,58,237,.3); border-radius:8px; font-family:inherit; font-weight:700; font-size:.85rem; cursor:pointer; transition:all .25s; }
        .btn-add:hover { background:rgba(124,58,237,.35); }
        .btn-del { padding:.5rem .9rem; background:rgba(248,113,113,.12); color:#f87171; border:1px solid rgba(248,113,113,.25); border-radius:7px; font-family:inherit; font-size:.8rem; font-weight:700; cursor:pointer; }
        .list-item { display:flex; align-items:center; justify-content:space-between; padding:.75rem 1rem; background:rgba(255,255,255,.03); border:1px solid rgba(255,255,255,.06); border-radius:9px; margin-bottom:.6rem; }
        .list-item-info h5 { color:white; font-size:.88rem; font-weight:700; }
        .list-item-info span { color:var(--muted); font-size:.75rem; }
        .alert { padding:.85rem 1.2rem; border-radius:10px; margin-bottom:1.2rem; font-size:.88rem; font-weight:600; display:flex; align-items:center; gap:.5rem; }
        .alert.success { background:rgba(74,222,128,.1); border:1px solid rgba(74,222,128,.35); color:#4ade80; }
        .alert.error { background:rgba(248,113,113,.1); border:1px solid rgba(248,113,113,.35); color:#f87171; }
        .toggle-wrap { display:flex; align-items:center; gap:.8rem; }
        .toggle-label { color:rgba(255,255,255,.7); font-size:.88rem; }
        hr.section-divider { border:none; border-top:1px solid var(--border); margin:1.5rem 0; }
        .option-grid { display:grid; grid-template-columns:1fr 1fr; gap:.75rem; }
        .quiz-q-card { background:rgba(255,255,255,.03); border:1px solid rgba(255,255,255,.07); border-radius:10px; padding:1rem; margin-bottom:.75rem; }
        .quiz-q-card p { color:rgba(255,255,255,.8); font-size:.88rem; font-weight:600; margin-bottom:.5rem; }
        .quiz-q-card .opts { display:flex; flex-wrap:wrap; gap:.4rem; }
        .opt-chip { padding:.2rem .6rem; border-radius:6px; font-size:.75rem; font-weight:700; background:rgba(124,58,237,.1); color:var(--purple2); }
        .opt-chip.correct { background:rgba(74,222,128,.15); color:#4ade80; }
    </style>
</head>
<body>
<div class="page-wrap">

    <a href="{{ route('admin.courses.lessons.index', $course) }}" class="back-link">
        <i class="fas fa-arrow-right"></i> العودة لقائمة الدروس
    </a>

    <div class="page-title">
        <i class="fas fa-pen" style="color:#a78bfa;margin-left:.5rem"></i>
        تعديل الدرس: {{ $lesson->title }}
    </div>

    @if(session('success'))
    <div class="alert success"><i class="fas fa-check-circle"></i>{{ session('success') }}</div>
    @endif
    @if($errors->any())
    <div class="alert error"><i class="fas fa-exclamation-circle"></i>{{ $errors->first() }}</div>
    @endif

    {{-- ════ 1. بيانات الدرس ════ --}}
    <div class="section-card">
        <div class="section-card-head">
            <h3><i class="fas fa-info-circle"></i>بيانات الدرس الأساسية</h3>
        </div>
        <div class="section-card-body">
            <form action="{{ route('admin.courses.lessons.update', [$course, $lesson]) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="field">
                    <label>عنوان الدرس *</label>
                    <input type="text" name="title" value="{{ old('title', $lesson->title) }}" placeholder="مثال: مقدمة عن JavaScript" required>
                </div>
                <div class="field">
                    <label>وصف الدرس</label>
                    <textarea name="description" rows="3" placeholder="وصف مختصر للدرس...">{{ old('description', $lesson->description) }}</textarea>
                </div>

                <div class="form-row">
                    <div class="field">
                        <label>نوع الفيديو</label>
                        <select name="video_type" id="videoTypeSelect" onchange="toggleVideoInput()">
                            <option value="youtube" {{ $lesson->video_type === 'youtube' ? 'selected' : '' }}>YouTube</option>
                            <option value="upload"  {{ $lesson->video_type === 'upload'  ? 'selected' : '' }}>رفع ملف فيديو</option>
                        </select>
                    </div>
                    <div class="field" id="ytField">
                        <label>YouTube Video ID أو الرابط الكامل</label>
                        <input type="text" name="video_url" value="{{ old('video_url', $lesson->video_url) }}" placeholder="مثال: dQw4w9WgXcQ أو https://youtube.com/watch?v=...">
                    </div>
                    <div class="field" id="uploadField" style="display:none">
                        <label>ملف الفيديو (mp4 / avi / mov)</label>
                        <input type="file" name="video_file" accept="video/*">
                        @if($lesson->video_type === 'upload' && $lesson->video_url)
                        <small style="color:#4ade80;font-size:.75rem">✅ يوجد فيديو مرفوع حالياً</small>
                        @endif
                    </div>
                </div>

                <div class="form-row">
                    <div class="field">
                        <label>المدة (بالدقائق)</label>
                        <input type="number" name="duration" value="{{ old('duration', $lesson->duration) }}" min="1" placeholder="20">
                    </div>
                    <div class="field">
                        <label>الترتيب</label>
                        <input type="number" name="order" value="{{ old('order', $lesson->order) }}" min="0" placeholder="1">
                    </div>
                </div>

                <div class="form-row">
                    <div class="field">
                        <label>المستوى</label>
                        <select name="level">
                            <option value="basic"        {{ $lesson->level === 'basic'        ? 'selected' : '' }}>أساسي</option>
                            <option value="intermediate" {{ $lesson->level === 'intermediate' ? 'selected' : '' }}>متوسط</option>
                            <option value="advanced"     {{ $lesson->level === 'advanced'     ? 'selected' : '' }}>متقدم</option>
                        </select>
                    </div>
                    <div class="field" style="display:flex;align-items:flex-end;padding-bottom:.2rem">
                        <div class="toggle-wrap">
                            <input type="checkbox" name="is_active" id="isActive" value="1" {{ $lesson->is_active ? 'checked' : '' }} style="width:auto">
                            <label class="toggle-label" for="isActive">الدرس مفعّل ومرئي للطلاب</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-save"><i class="fas fa-save"></i> حفظ الدرس</button>
            </form>
        </div>
    </div>

    {{-- ════ 2. المواد التعليمية ════ --}}
    <div class="section-card">
        <div class="section-card-head">
            <h3><i class="fas fa-paperclip"></i>المواد التعليمية</h3>
        </div>
        <div class="section-card-body">
            {{-- قائمة المواد الحالية --}}
            @foreach($lesson->materials as $mat)
            <div class="list-item">
                <div class="list-item-info">
                    <h5>{{ $mat->title }}</h5>
                    <span>{{ strtoupper($mat->type) }} · {{ $mat->description }}</span>
                </div>
                <form action="{{ route('admin.materials.destroy', $mat) }}" method="POST" onsubmit="return confirm('حذف المادة؟')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-del"><i class="fas fa-trash"></i></button>
                </form>
            </div>
            @endforeach

            <hr class="section-divider">

            {{-- فورم إضافة مادة --}}
            <form action="{{ route('admin.materials.store', $lesson) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="field">
                        <label>اسم المادة *</label>
                        <input type="text" name="title" placeholder="مثال: ملخص PDF">
                    </div>
                    <div class="field">
                        <label>النوع</label>
                        <select name="type">
                            <option value="pdf">PDF</option>
                            <option value="code">كود</option>
                            <option value="link">رابط</option>
                            <option value="zip">ZIP</option>
                            <option value="other">أخرى</option>
                        </select>
                    </div>
                </div>
                <div class="field">
                    <label>وصف المادة</label>
                    <input type="text" name="description" placeholder="وصف مختصر...">
                </div>
                <div class="form-row">
                    <div class="field">
                        <label>رفع ملف</label>
                        <input type="file" name="file">
                    </div>
                    <div class="field">
                        <label>أو رابط خارجي</label>
                        <input type="url" name="file_url" placeholder="https://...">
                    </div>
                </div>
                <button type="submit" class="btn-add"><i class="fas fa-plus"></i> إضافة المادة</button>
            </form>
        </div>
    </div>

    {{-- ════ 3. الواجبات ════ --}}
    <div class="section-card">
        <div class="section-card-head">
            <h3><i class="fas fa-tasks"></i>الواجبات</h3>
        </div>
        <div class="section-card-body">
            @foreach($lesson->assignments as $asgn)
            <div class="list-item">
                <div class="list-item-info">
                    <h5>{{ $asgn->title }}</h5>
                    <span>{{ $asgn->points }} نقطة · مدة التسليم: {{ $asgn->deadline_days }} أيام</span>
                </div>
                <form action="{{ route('admin.assignments.destroy', $asgn) }}" method="POST" onsubmit="return confirm('حذف الواجب؟')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-del"><i class="fas fa-trash"></i></button>
                </form>
            </div>
            @endforeach

            <hr class="section-divider">

            <form action="{{ route('admin.assignments.store', $lesson) }}" method="POST">
                @csrf
                <div class="field">
                    <label>عنوان الواجب *</label>
                    <input type="text" name="title" placeholder="مثال: واجب المتغيرات">
                </div>
                <div class="field">
                    <label>وصف الواجب *</label>
                    <textarea name="description" rows="3" placeholder="تفاصيل الواجب والمطلوب من الطالب..."></textarea>
                </div>
                <div class="form-row">
                    <div class="field">
                        <label>الدرجة (نقاط)</label>
                        <input type="number" name="points" value="20" min="1" max="100">
                    </div>
                    <div class="field">
                        <label>مدة التسليم (أيام)</label>
                        <input type="number" name="deadline_days" value="7" min="1">
                    </div>
                </div>
                <button type="submit" class="btn-add"><i class="fas fa-plus"></i> إضافة الواجب</button>
            </form>
        </div>
    </div>

    {{-- ════ 4. الكويز ════ --}}
    <div class="section-card">
        <div class="section-card-head">
            <h3><i class="fas fa-question-circle"></i>الكويز (بعد الفيديو)</h3>
        </div>
        <div class="section-card-body">
            @if($lesson->quiz)
            {{-- الكويز موجود --}}
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.2rem">
                <div>
                    <h4 style="color:white;font-weight:700">{{ $lesson->quiz->title }}</h4>
                    <span style="color:var(--muted);font-size:.8rem">درجة النجاح: {{ $lesson->quiz->passing_score }}%</span>
                </div>
                <form action="{{ route('admin.quiz.destroy', $lesson->quiz) }}" method="POST" onsubmit="return confirm('حذف الكويز بالكامل؟')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-del"><i class="fas fa-trash"></i> حذف الكويز</button>
                </form>
            </div>

            {{-- الأسئلة الحالية --}}
            @foreach($lesson->quiz->questions as $q)
            <div class="quiz-q-card">
                <p>{{ $loop->iteration }}. {{ $q->question }}</p>
                <div class="opts">
                    <span class="opt-chip {{ $q->correct_answer === 'a' ? 'correct' : '' }}">أ. {{ $q->option_a }}</span>
                    <span class="opt-chip {{ $q->correct_answer === 'b' ? 'correct' : '' }}">ب. {{ $q->option_b }}</span>
                    @if($q->option_c)<span class="opt-chip {{ $q->correct_answer === 'c' ? 'correct' : '' }}">ج. {{ $q->option_c }}</span>@endif
                    @if($q->option_d)<span class="opt-chip {{ $q->correct_answer === 'd' ? 'correct' : '' }}">د. {{ $q->option_d }}</span>@endif
                </div>
                <div style="margin-top:.6rem">
                    <form action="{{ route('admin.quiz.question.destroy', $q) }}" method="POST" style="display:inline" onsubmit="return confirm('حذف السؤال؟')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-del"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </div>
            @endforeach

            <hr class="section-divider">

            {{-- إضافة سؤال جديد --}}
            <h4 style="color:white;margin-bottom:1rem;font-size:.95rem"><i class="fas fa-plus-circle" style="color:#a78bfa;margin-left:.4rem"></i>إضافة سؤال جديد</h4>
            <form action="{{ route('admin.quiz.question.store', $lesson->quiz) }}" method="POST">
                @csrf
                <div class="field">
                    <label>نص السؤال *</label>
                    <input type="text" name="question" placeholder="اكتب السؤال هنا...">
                </div>
                <div class="option-grid">
                    <div class="field"><label>الخيار أ *</label><input type="text" name="option_a" placeholder="الخيار الأول"></div>
                    <div class="field"><label>الخيار ب *</label><input type="text" name="option_b" placeholder="الخيار الثاني"></div>
                    <div class="field"><label>الخيار ج (اختياري)</label><input type="text" name="option_c" placeholder="الخيار الثالث"></div>
                    <div class="field"><label>الخيار د (اختياري)</label><input type="text" name="option_d" placeholder="الخيار الرابع"></div>
                </div>
                <div class="field">
                    <label>الإجابة الصحيحة *</label>
                    <select name="correct_answer">
                        <option value="a">أ</option>
                        <option value="b">ب</option>
                        <option value="c">ج</option>
                        <option value="d">د</option>
                    </select>
                </div>
                <button type="submit" class="btn-add"><i class="fas fa-plus"></i> إضافة السؤال</button>
            </form>

            @else
            {{-- لا يوجد كويز بعد --}}
            <p style="color:var(--muted);margin-bottom:1.2rem">لم يتم إنشاء كويز لهذا الدرس بعد.</p>
            <form action="{{ route('admin.quiz.store', $lesson) }}" method="POST">
                @csrf
                <div class="form-row">
                    <div class="field">
                        <label>عنوان الكويز *</label>
                        <input type="text" name="title" value="اختبار: {{ $lesson->title }}" placeholder="عنوان الكويز">
                    </div>
                    <div class="field">
                        <label>درجة النجاح (%)</label>
                        <input type="number" name="passing_score" value="60" min="10" max="100">
                    </div>
                </div>
                <button type="submit" class="btn-save"><i class="fas fa-magic"></i> إنشاء الكويز</button>
            </form>
            @endif
        </div>
    </div>

</div>

<script>
function toggleVideoInput() {
    const type = document.getElementById('videoTypeSelect').value;
    document.getElementById('ytField').style.display     = type === 'youtube' ? '' : 'none';
    document.getElementById('uploadField').style.display = type === 'upload'  ? '' : 'none';
}
// init
toggleVideoInput();
</script>
</body>
</html>