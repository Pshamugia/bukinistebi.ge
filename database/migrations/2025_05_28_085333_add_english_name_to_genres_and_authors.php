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
        Schema::table('genres', function (Blueprint $table) {
            $table->string('name_en')->nullable();
        });
    
        Schema::table('authors', function (Blueprint $table) {
            $table->string('name_en')->nullable();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('genres_and_authors', function (Blueprint $table) {
            //
        });
    }
};
