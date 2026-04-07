<?php
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Lesson extends Model
{
    protected $fillable = [
        'course_id','title','description','video_url',
        'video_type','duration','order','level','is_active',
    ];
 
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
 
    public function materials()
    {
        return $this->hasMany(Material::class);
    }
 
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
 
    public function quiz()
    {
        return $this->hasOne(Quiz::class);
    }
 
    public function completions()
    {
        return $this->hasMany(LessonCompletion::class);
    }
 
    // استخراج YouTube ID من الرابط
    public function getYoutubeIdAttribute(): ?string
    {
        if (!$this->video_url) return null;
        preg_match(
            '/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([A-Za-z0-9_-]{11})/',
            $this->video_url,
            $m
        );
        return $m[1] ?? $this->video_url; // لو مش رابط كامل، اعتبره ID مباشرة
    }
}