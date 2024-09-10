<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCredentialsTable extends Migration
{
    public function up()
    {
        Schema::table('credentials', function (Blueprint $table) {
            // Make 'language_experty' column nullable
            $table->string('language_experty')->nullable()->change();

            // Make 'credentials' column nullable
            $table->string('credentials')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('credentials', function (Blueprint $table) {
            // Revert 'language_experty' column to not nullable
            $table->string('language_experty')->nullable(false)->change();

            // Revert 'credentials' column to not nullable
            $table->string('credentials')->nullable(false)->change();
        });
    }
}
