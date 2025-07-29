<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('books', function (Blueprint $table) {
            // First drop the foreign key constraint
            DB::statement('ALTER TABLE books DROP FOREIGN KEY IF EXISTS books_genre_id_foreign');

    
            // Then drop the column
            $table->dropColumn('genre_id');
        });
    }
};
