<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LessonController as AdminLessonController;
use App\Http\Controllers\Admin\MaterialController as AdminMaterialController;
use App\Http\Controllers\Admin\AssignmentController as AdminAssignmentController;
use App\Http\Controllers\Admin\QuizController as AdminQuizController;
use App\Http\Controllers\PaymentController;
// ── الصفحة الرئيسية ─────────────────────────────────────────────────
Route::get('/', function () {
    $firstLesson = \App\Models\Lesson::where('is_active', true)
        ->whereNotNull('video_url')
        ->orderBy('order')
        ->first();
    return view('index', compact('firstLesson'));
});

// ── Auth ─────────────────────────────────────────────────────────────
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ── صفحة الكورس (تحتاج تسجيل دخول) ────────────────────────────────
Route::middleware('auth.student')->group(function () {
    Route::get('/course', [CourseController::class, 'show'])->name('course');

    Route::post('/lessons/complete', [LessonController::class, 'markComplete'])->name('lessons.complete');
    Route::post('/quizzes/{quiz}/submit', [QuizController::class, 'submit'])->name('quizzes.submit');
    Route::post('/assignments/{assignment}/submit', [AssignmentController::class, 'submit'])->name('assignments.submit');
});

// ── Admin Panel ───────────────────────────────────────────────────────
Route::middleware(['auth.student', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // ── إدارة الدروس ────────────────────────────────────────────────
        Route::prefix('courses/{course}/lessons')->name('courses.lessons.')->group(function () {
            Route::get('/', [AdminLessonController::class, 'index'])->name('index');
            Route::get('/create', [AdminLessonController::class, 'create'])->name('create');
            Route::post('/', [AdminLessonController::class, 'store'])->name('store');
            Route::get('/{lesson}/edit', [AdminLessonController::class, 'edit'])->name('edit');
            Route::put('/{lesson}', [AdminLessonController::class, 'update'])->name('update');
            Route::delete('/{lesson}', [AdminLessonController::class, 'destroy'])->name('destroy');
        });

        // ── إدارة المواد ─────────────────────────────────────────────────
        Route::post('/lessons/{lesson}/materials', [AdminMaterialController::class, 'store'])->name('materials.store');
        Route::delete('/materials/{material}', [AdminMaterialController::class, 'destroy'])->name('materials.destroy');

        // ── إدارة الواجبات ───────────────────────────────────────────────
        Route::post('/lessons/{lesson}/assignments', [AdminAssignmentController::class, 'store'])->name('assignments.store');
        Route::delete('/assignments/{assignment}', [AdminAssignmentController::class, 'destroy'])->name('assignments.destroy');
        Route::get('/lessons/{lesson}/submissions', [AdminAssignmentController::class, 'submissions'])->name('assignments.submissions');
        Route::post('/submissions/{submission}/review', [AdminAssignmentController::class, 'review'])->name('submissions.review');

        // ── إدارة الكويزات ───────────────────────────────────────────────
        Route::post('/lessons/{lesson}/quiz', [AdminQuizController::class, 'store'])->name('quiz.store');
        Route::delete('/quizzes/{quiz}', [AdminQuizController::class, 'destroy'])->name('quiz.destroy');
        Route::post('/quizzes/{quiz}/questions', [AdminQuizController::class, 'addQuestion'])->name('quiz.question.store');
        Route::delete('/quiz-questions/{question}', [AdminQuizController::class, 'destroyQuestion'])->name('quiz.question.destroy');
    });





Route::post('/payment/session', [PaymentController::class, 'saveSession'])
    ->name('payment.session');

// Callbacks من كاشير
Route::get('/payment/success', [PaymentController::class, 'success'])
    ->name('payment.success');

Route::get('/payment/fail', [PaymentController::class, 'fail'])
    ->name('payment.fail');

// Webhook (server to server) — أضف الرابط ده في إعدادات كاشير
Route::post('/payment/webhook', [PaymentController::class, 'webhook'])
    ->name('payment.webhook')
    ->withoutMiddleware(['web']); // بدون CSRF عشان هو request خارجي
