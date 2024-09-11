<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColumnsInCredentialsTable extends Migration
{
    public function up()
    {
        Schema::table('credentials', function (Blueprint $table) {
            // Rename the 'experties' column to 'language_experty'
            $table->renameColumn('experties', 'language_experty');

            // Rename the 'years' column to 'credentials' and change its type to string
            $table->renameColumn('years', 'credentials');
            $table->string('credentials')->change(); // Change type to string
        });
    }

    public function down()
    {
        Schema::table('credentials', function (Blueprint $table) {
            // Revert column type and rename it back
            $table->renameColumn('credentials', 'years');
            $table->integer('years')->change(); // Revert type to integer

            // Rename 'language_experty' back to 'experties'
            $table->renameColumn('language_experty', 'experties');
        });
    }
}
