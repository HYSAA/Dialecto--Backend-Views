<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class suggestedWord extends Model
{
    use HasFactory;


    protected $fillable = ['user_id', 'lesson_id', 'course_id','video', 'text', 'english'];

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
}

