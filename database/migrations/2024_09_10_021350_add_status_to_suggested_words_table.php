<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('suggested_words', function (Blueprint $table) {
            $table->string('status')->default('pending'); // or whatever default you prefer
        });
    }
    
    public function down()
    {
        Schema::table('suggested_words', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
