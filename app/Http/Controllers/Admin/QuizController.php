<?php
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
 
class QuizController extends Controller
{
    public function store(Request $request, Lesson $lesson)
    {
        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'passing_score' => 'required|integer|min:1|max:100',
        ]);
 
        $data['lesson_id'] = $lesson->id;
 
        // حذف الكويز القديم لو موجود وعمل واحد جديد
        Quiz::where('lesson_id', $lesson->id)->delete();
        $quiz = Quiz::create($data);
 
        return back()->with('success', 'تم إنشاء الكويز بنجاح ✅');
    }
 
    public function addQuestion(Request $request, Quiz $quiz)
    {
        $data = $request->validate([
            'question'       => 'required|string',
            'option_a'       => 'required|string',
            'option_b'       => 'required|string',
            'option_c'       => 'nullable|string',
            'option_d'       => 'nullable|string',
            'correct_answer' => 'required|in:a,b,c,d',
        ]);
 
        $data['quiz_id'] = $quiz->id;
        $data['order']   = $quiz->questions()->count() + 1;
        QuizQuestion::create($data);
 
        return back()->with('success', 'تم إضافة السؤال ✅');
    }
 
    public function destroyQuestion(QuizQuestion $question)
    {
        $question->delete();
        return back()->with('success', 'تم حذف السؤال ✅');
    }
 
    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        return back()->with('success', 'تم حذف الكويز ✅');
    }
}