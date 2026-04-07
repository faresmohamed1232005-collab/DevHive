<?php
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
 
class AssignmentController extends Controller
{
    public function store(Request $request, Lesson $lesson)
    {
        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'required|string',
            'points'        => 'required|integer|min:1|max:100',
            'deadline_days' => 'required|integer|min:1',
        ]);
 
        $data['lesson_id'] = $lesson->id;
        Assignment::create($data);
 
        return back()->with('success', 'تم إضافة الواجب بنجاح ✅');
    }
 
    public function destroy(Assignment $assignment)
    {
        $assignment->delete();
        return back()->with('success', 'تم حذف الواجب ✅');
    }
 
    // مراجعة حل الطالب
    public function review(Request $request, AssignmentSubmission $submission)
    {
        $data = $request->validate([
            'grade'    => 'required|integer|min:0|max:100',
            'feedback' => 'nullable|string',
            'status'   => 'required|in:accepted,rejected',
        ]);
 
        $submission->update($data);
 
        return back()->with('success', 'تم مراجعة الحل ✅');
    }
 
    public function submissions(Lesson $lesson)
    {
        $assignments  = $lesson->assignments()->with(['submissions.user'])->get();
        return view('admin.assignments.submissions', compact('lesson', 'assignments'));
    }
}