<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class suggestedWord extends Model
{
    use HasFactory;

    protected $table = 'suggested_words'; // Specify the table name if it doesn't follow Laravel's naming convention

    protected $fillable = ['user_id', 'content_id', 'lesson_id', 'course_id','video', 'text', 'english'];

    /**
     * Get the user that suggested the word.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the content associated with the word.
     */
    public function content()
    {
        return $this->belongsTo(Content::class);
    }

    /**
     * Get the lesson associated with the word.
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Get the course associated with the word.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}

