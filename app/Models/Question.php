<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_text', 
        'content_id'
    ];

    // Define the relationship with the Answer model
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    // Define the relationship with the Content model
    public function content()
    {
        return $this->belongsTo(Content::class);
    }

    // Get the correct answer for the question
    public function correctAnswer()
    {
        return $this->hasOne(Answer::class)->where('is_correct', true);
    }
}
