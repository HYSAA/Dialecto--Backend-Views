<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'title', 'image'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function contents()
    {
        return $this->hasMany(Content::class);
    }

    public function suggestedWords()
    {
        return $this->hasMany(SuggestedWord::class);
    }
}
