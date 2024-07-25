<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    protected $fillable = ['lesson_id', 'course_id'];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
