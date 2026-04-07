<?php
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
 
class LessonController extends Controller
{
    public function index(Course $course)
    {
        $lessons = $course->lessons()->orderBy('order')->get();
        return view('admin.lessons.index', compact('course', 'lessons'));
    }
 
    public function create(Course $course)
    {
        return view('admin.lessons.create', compact('course'));
    }
 
    public function store(Request $request, Course $course)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_url'   => 'nullable|string',
            'video_type'  => 'required|in:youtube,upload',
            'video_file'  => 'nullable|file|mimes:mp4,avi,mov|max:512000',
            'duration'    => 'nullable|integer|min:1',
            'order'       => 'nullable|integer|min:0',
            'level'       => 'required|in:basic,intermediate,advanced',
        ]);
 
        // رفع الفيديو لو upload
        if ($request->video_type === 'upload' && $request->hasFile('video_file')) {
            $data['video_url'] = $request->file('video_file')->store('videos', 'public');
        }
 
        $data['course_id'] = $course->id;
        $data['is_active'] = $request->boolean('is_active', true);
 
        Lesson::create($data);
 
        return redirect()->route('admin.courses.lessons.index', $course)
                         ->with('success', 'تم إضافة الدرس بنجاح ✅');
    }
 
    public function edit(Course $course, Lesson $lesson)
    {
        return view('admin.lessons.edit', compact('course', 'lesson'));
    }
 
    public function update(Request $request, Course $course, Lesson $lesson)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_url'   => 'nullable|string',
            'video_type'  => 'required|in:youtube,upload',
            'video_file'  => 'nullable|file|mimes:mp4,avi,mov|max:512000',
            'duration'    => 'nullable|integer|min:1',
            'order'       => 'nullable|integer|min:0',
            'level'       => 'required|in:basic,intermediate,advanced',
        ]);
 
        if ($request->video_type === 'upload' && $request->hasFile('video_file')) {
            if ($lesson->video_url && $lesson->video_type === 'upload') {
                Storage::disk('public')->delete($lesson->video_url);
            }
            $data['video_url'] = $request->file('video_file')->store('videos', 'public');
        }
 
        $data['is_active'] = $request->boolean('is_active');
        $lesson->update($data);
 
        return redirect()->route('admin.courses.lessons.index', $course)
                         ->with('success', 'تم تعديل الدرس بنجاح ✅');
    }
 
    public function destroy(Course $course, Lesson $lesson)
    {
        if ($lesson->video_type === 'upload' && $lesson->video_url) {
            Storage::disk('public')->delete($lesson->video_url);
        }
        $lesson->delete();
        return back()->with('success', 'تم حذف الدرس ✅');
    }
}