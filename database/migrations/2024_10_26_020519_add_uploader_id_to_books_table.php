<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('books', function (Blueprint $table) {
        if (!Schema::hasColumn('books', 'uploader_id')) {
            $table->unsignedBigInteger('uploader_id')->nullable();
            $table->foreign('uploader_id')->references('id')->on('users')->onDelete('set null');
        }
    });
}

public function down(): void
{
    Schema::table('books', function (Blueprint $table) {
        if (Schema::hasColumn('books', 'uploader_id')) {
            $table->dropForeign(['uploader_id']); // Drop foreign key
            $table->dropColumn('uploader_id');   // Drop column
        }
    });
}

    
};
