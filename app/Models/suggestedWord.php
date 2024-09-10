<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class suggestedWord extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'expert_id', 'lesson_id', 'course_id', 'video', 'text', 'english', 'status'];

    // Define relationship with Course model
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    // Define relationship with Lesson model
    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }

    // Define relationship with User model (for expert)
    public function expert()
    {
        return $this->belongsTo(User::class, 'expert_id');
    }
}