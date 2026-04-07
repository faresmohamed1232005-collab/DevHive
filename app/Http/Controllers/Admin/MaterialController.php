<?php
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
 
class MaterialController extends Controller
{
    public function store(Request $request, Lesson $lesson)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'type'        => 'required|in:pdf,code,link,zip,other',
            'file'        => 'nullable|file|max:20480',
            'file_url'    => 'nullable|url',
        ]);
 
        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('materials', 'public');
        }
 
        $data['lesson_id'] = $lesson->id;
        Material::create($data);
 
        return back()->with('success', 'تم إضافة المادة بنجاح ✅');
    }
 
    public function destroy(Material $material)
    {
        if ($material->file_path) {
            Storage::disk('public')->delete($material->file_path);
        }
        $material->delete();
        return back()->with('success', 'تم حذف المادة ✅');
    }
}