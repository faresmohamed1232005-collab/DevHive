<?php
// app/Http/Controllers/Admin/DashboardController.php
 
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Enrollment;
use App\Models\AssignmentSubmission;
use App\Models\QuizAttempt;
 
class DashboardController extends Controller
{
    public function index()
    {
        $course = Course::first();
 
        $stats = [
            'students'    => User::where('role', 'student')->count(),
            'courses'     => Course::count(),
            'lessons'     => Lesson::count(),
            'enrollments' => Enrollment::count(),
            'submissions' => AssignmentSubmission::count(),
            'pending'     => AssignmentSubmission::where('status', 'pending')->count(),
        ];
 
        $latestStudents = User::where('role', 'student')
                              ->latest()
                              ->take(5)
                              ->get();
 
        // ✅ كل الحلول المرفوعة مع بيانات الطالب والواجب
        $allSubmissions = AssignmentSubmission::with(['user', 'assignment.lesson'])
                                              ->latest()
                                              ->get();
 
        // ✅ كل نتائج الكويزات مع بيانات الطالب والكويز
        $allQuizAttempts = QuizAttempt::with(['user', 'quiz.lesson'])
                                      ->latest()
                                      ->get();
 
        return view('admin.dashboard', compact(
            'stats', 'latestStudents',
            'allSubmissions', 'allQuizAttempts',
            'course'
        ));
    }
}
 