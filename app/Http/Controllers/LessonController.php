<?php
namespace App\Http\Controllers;
 
use App\Models\LessonCompletion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
 
class LessonController extends Controller
{
    // تمييز الدرس كمكتمل (POST)
    public function markComplete(Request $request)
    {
        $user = Auth::user();
 
        LessonCompletion::firstOrCreate([
            'user_id'   => $user->id,
            'lesson_id' => $request->lesson_id,
        ]);
 
        return response()->json(['success' => true]);
    }
}