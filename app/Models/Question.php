<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['content_id', 'question', 'answer'];

    public function content()
    {
        return $this->belongsTo(Content::class);
    }
}
