<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['content_id' , 'question_text'];

    public function content()
    {
        return $this->belongsTo(Content::class);
    }
    public function answers(){
        return $this->hasMany(Answer::class);
    }
}
