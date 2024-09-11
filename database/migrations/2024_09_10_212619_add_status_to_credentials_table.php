<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToCredentialsTable extends Migration
{
    public function up()
    {
        Schema::table('credentials', function (Blueprint $table) {
            $table->boolean('status')->nullable()->default(null); // Add the new column with default value of null
        });
    }

    public function down()
    {
        Schema::table('credentials', function (Blueprint $table) {
            $table->dropColumn('status'); // Remove the column if rolling back
        });
    }
}
