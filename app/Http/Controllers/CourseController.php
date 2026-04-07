<?php
// app/Http/Controllers/CourseController.php
 
namespace App\Http\Controllers;
 
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LessonCompletion;
use App\Models\AssignmentSubmission;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;
 
class CourseController extends Controller
{
    public function show()
    {
        $user   = Auth::user();
        $course = Course::where('is_active', true)
                        ->with([
                            'activeLessons.materials',
                            'activeLessons.assignments',
                            'activeLessons.quiz.questions',
                        ])
                        ->first();
 
        if (!$course) {
            return redirect('/')->with('error', 'لا يوجد كورس متاح حالياً');
        }
 
        // تسجيل تلقائي لو مش مسجّل
        if (!$user->isEnrolledIn($course->id)) {
            Enrollment::firstOrCreate([
                'user_id'   => $user->id,
                'course_id' => $course->id,
            ], [
                'payment_method' => $user->payment_method,
            ]);
        }
 
        $totalLessons   = $course->activeLessons->count();
        $completedIds   = LessonCompletion::where('user_id', $user->id)
                                          ->pluck('lesson_id')
                                          ->toArray();
        $completedCount = count($completedIds);
        $progress       = $totalLessons > 0
                          ? round(($completedCount / $totalLessons) * 100)
                          : 0;
 
        $submittedIds = AssignmentSubmission::where('user_id', $user->id)
                                            ->pluck('assignment_id')
                                            ->toArray();
 
        // ✅ الدروس المفتوحة (اللي اجتاز الطالب كويزها أو أول درس)
        $passedQuizLessonIds = QuizAttempt::where('user_id', $user->id)
                                          ->where('passed', true)
                                          ->join('quizzes', 'quiz_attempts.quiz_id', '=', 'quizzes.id')
                                          ->pluck('quizzes.lesson_id')
                                          ->toArray();
 
        // الدرس الأول دايماً مفتوح
        $firstLessonId = $course->activeLessons->first()?->id;
 
        // درس مفتوح لو: هو الأول، أو الدرس اللي قبله اتعمل له كويز ناجح
        $unlockedLessonIds = [];
        foreach ($course->activeLessons as $index => $lesson) {
            if ($index === 0) {
                // أول درس دايماً مفتوح
                $unlockedLessonIds[] = $lesson->id;
            } else {
                // الدرس مفتوح لو الطالب نجح في كويز الدرس اللي قبله
                $prevLesson = $course->activeLessons[$index - 1];
                if (in_array($prevLesson->id, $passedQuizLessonIds) ||
                    in_array($prevLesson->id, $completedIds)) {
                    $unlockedLessonIds[] = $lesson->id;
                }
            }
        }
 
        // تحضير بيانات الدروس لـ JavaScript
        $lessonsJson = $course->activeLessons->map(function ($l) use ($unlockedLessonIds) {
            return [
                'id'         => $l->id,
                'title'      => $l->title,
                'youtubeId'  => $l->youtube_id,
                'videoType'  => $l->video_type,
                'videoUrl'   => $l->video_url,
                'unlocked'   => in_array($l->id, $unlockedLessonIds),
                'quiz'       => $l->quiz ? [
                    'id'            => $l->quiz->id,
                    'title'         => $l->quiz->title,
                    'passing_score' => $l->quiz->passing_score,
                    'questions'     => $l->quiz->questions->map(function ($q) {
                        return [
                            'id'       => $q->id,
                            'question' => $q->question,
                            'options'  => array_filter([
                                'a' => $q->option_a,
                                'b' => $q->option_b,
                                'c' => $q->option_c,
                                'd' => $q->option_d,
                            ]),
                        ];
                    })->values(),
                ] : null,
            ];
        })->values();
 
        return view('course', compact(
            'course', 'completedIds', 'completedCount',
            'progress', 'totalLessons', 'submittedIds',
            'lessonsJson', 'unlockedLessonIds'
        ));
    }
}
 