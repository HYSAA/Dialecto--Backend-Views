<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade'); // Link to Lesson
            $table->string('question_text');
            $table->foreignId('correct_answer_id')->nullable(); // To store the ID of the correct answer
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('questions');
    }
};
