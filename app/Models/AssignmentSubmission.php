<?php
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class AssignmentSubmission extends Model
{
    protected $fillable = [
        'user_id','assignment_id','file_path',
        'original_filename','notes','status','grade','feedback',
    ];
 
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }
 
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}