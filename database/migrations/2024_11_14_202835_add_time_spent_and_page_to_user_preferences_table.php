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
        Schema::table('user_preferences', function (Blueprint $table) {
            // Adding columns for tracking user behavior
            $table->integer('time_spent')->nullable(); // Store time spent on a page
            $table->string('page')->nullable(); // Store the page URL
        });
    }
    
    public function down()
    {
        Schema::table('user_preferences', function (Blueprint $table) {
            // Remove the columns if we roll back the migration
            $table->dropColumn(['time_spent', 'page']);
        });
    }
    
};
