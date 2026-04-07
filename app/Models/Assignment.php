<?php
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Assignment extends Model
{
    protected $fillable = ['lesson_id','title','description','points','deadline_days'];
 
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
 
    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }
 
    public function userSubmission(int $userId)
    {
        return $this->submissions()->where('user_id', $userId)->first();
    }
}