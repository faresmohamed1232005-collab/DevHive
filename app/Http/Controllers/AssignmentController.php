<?php
namespace App\Http\Controllers;
 
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
 
class AssignmentController extends Controller
{
    public function submit(Request $request, Assignment $assignment)
    {
        $user = Auth::user();
 
        $request->validate([
            'file'  => 'required|file|mimes:pdf,zip,js,doc,docx|max:10240',
            'notes' => 'nullable|string|max:500',
        ]);
 
        // حذف الملف القديم لو موجود
        $existing = AssignmentSubmission::where('user_id', $user->id)
                                        ->where('assignment_id', $assignment->id)
                                        ->first();
        if ($existing && $existing->file_path) {
            Storage::disk('public')->delete($existing->file_path);
        }
 
        $file     = $request->file('file');
        $path     = $file->store('submissions', 'public');
 
        AssignmentSubmission::updateOrCreate(
            ['user_id' => $user->id, 'assignment_id' => $assignment->id],
            [
                'file_path'         => $path,
                'original_filename' => $file->getClientOriginalName(),
                'notes'             => $request->notes,
                'status'            => 'pending',
                'grade'             => null,
                'feedback'          => null,
            ]
        );
 
        return response()->json([
            'success' => true,
            'message' => 'تم رفع الحل بنجاح! سيتم مراجعته قريباً',
        ]);
    }
}