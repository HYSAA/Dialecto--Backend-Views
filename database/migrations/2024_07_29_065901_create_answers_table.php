<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->string('answer_text');
            $table->boolean('is_correct')->default(false); // Mark the correct answer
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('answers');
    }
};
