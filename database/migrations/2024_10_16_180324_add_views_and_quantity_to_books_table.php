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
        Schema::table('books', function (Blueprint $table) {
            $table->integer('views')->default(0); // Track the number of views
            $table->integer('quantity')->default(0); // Track available quantity
        });
    }
    
    public function down()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['views', 'quantity']);
        });
    }
    
};
