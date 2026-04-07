<?php
namespace App\Models;
 
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
 
class User extends Authenticatable
{
    use Notifiable;
 
    protected $fillable = [
        'name','email','phone','username',
        'password','payment_method','role',
    ];
 
    protected $hidden = ['password','remember_token'];
 
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
 
    public function completedLessons()
    {
        return $this->hasMany(LessonCompletion::class);
    }
 
    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }
 
    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }
 
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
 
    public function isEnrolledIn(int $courseId): bool
    {
        return $this->enrollments()->where('course_id', $courseId)->exists();
    }
 
    public function hasCompletedLesson(int $lessonId): bool
    {
        return $this->completedLessons()->where('lesson_id', $lessonId)->exists();
    }
}