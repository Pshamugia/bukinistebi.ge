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
        $table->integer('status')->change(); // Change column type to INT
    });
}

public function down(): void
{
    Schema::table('books', function (Blueprint $table) {
        $table->string('status')->change(); // Revert column type to VARCHAR
    });
}

};
