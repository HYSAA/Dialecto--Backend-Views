<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'image'];


    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function suggestedWords()
    {
        return $this->hasMany(SuggestedWord::class);
    }
}
