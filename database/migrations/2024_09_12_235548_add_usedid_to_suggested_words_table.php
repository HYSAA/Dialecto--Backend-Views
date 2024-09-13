<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUsedidToSuggestedWordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suggested_words', function (Blueprint $table) {
            $table->unsignedBigInteger('usedID')->nullable(); // Adds the 'usedID' column
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suggested_words', function (Blueprint $table) {
            $table->dropColumn('usedID'); // Removes the 'usedID' column
        });
    }
}
