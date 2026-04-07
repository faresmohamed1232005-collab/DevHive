<?php
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Material extends Model
{
    protected $fillable = [
        'lesson_id','title','description','file_path','file_url','type',
    ];
 
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
 
    public function getDownloadUrlAttribute(): string
    {
        if ($this->file_path) {
            return asset('storage/' . $this->file_path);
        }
        return $this->file_url ?? '#';
    }
}