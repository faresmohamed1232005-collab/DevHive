<?php
namespace App\Http\Controllers;
 
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\LessonCompletion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
 
class QuizController extends Controller
{
    // إرسال إجابات الكويز
    public function submit(Request $request, Quiz $quiz)
    {
        $user      = Auth::user();
        $questions = $quiz->questions;
        $answers   = $request->answers ?? []; // ['question_id' => 'a']
 
        if ($questions->isEmpty()) {
            return response()->json(['error' => 'لا توجد أسئلة'], 422);
        }
 
        $correct = 0;
        foreach ($questions as $q) {
            if (isset($answers[$q->id]) && $answers[$q->id] === $q->correct_answer) {
                $correct++;
            }
        }
 
        $score  = round(($correct / $questions->count()) * 100);
        $passed = $score >= $quiz->passing_score;
 
        // حفظ المحاولة
        $attempt = QuizAttempt::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'score'   => $score,
            'answers' => $answers,
            'passed'  => $passed,
        ]);
 
        // لو نجح، اعتبر الدرس مكتمل
        if ($passed) {
            LessonCompletion::firstOrCreate([
                'user_id'   => $user->id,
                'lesson_id' => $quiz->lesson_id,
            ]);
        }
 
        return response()->json([
            'success'       => true,
            'score'         => $score,
            'passed'        => $passed,
            'correct'       => $correct,
            'total'         => $questions->count(),
            'passing_score' => $quiz->passing_score,
        ]);
    }
}