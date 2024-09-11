<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credential extends Model
{
    use HasFactory;

    // Update fillable properties to match new column names
    protected $fillable = [
        'user_id',
        'language_experty', // updated column name
        'credentials', // updated column name (for image path)
        'status', // included the new column
    ];

    // Define the inverse relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
