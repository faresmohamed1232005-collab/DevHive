<?php
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Course extends Model
{
    protected $fillable = [
        'title','description','price','level',
        'thumbnail','tech_icon','tech_label','is_active',
    ];
 
    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }
 
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
 
    public function activeLessons()
    {
        return $this->hasMany(Lesson::class)
                    ->where('is_active', true)
                    ->orderBy('order');
    }
}
