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
            // First drop the foreign key constraint
            $table->dropForeign(['genre_id']);
    
            // Then drop the column
            $table->dropColumn('genre_id');
        });
    }
};
