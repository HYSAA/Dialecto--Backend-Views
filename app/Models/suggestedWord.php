<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class suggestedWord extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'content_id', 'lesson_id', 'course_id', 'text', 'english'];

    
}

