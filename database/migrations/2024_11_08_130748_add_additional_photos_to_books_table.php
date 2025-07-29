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
        if (!Schema::hasColumn('books', 'photo_2')) {
            $table->string('photo_2')->nullable();
        }

        if (!Schema::hasColumn('books', 'photo_3')) {
            $table->string('photo_3')->nullable();
        }

        if (!Schema::hasColumn('books', 'photo_4')) {
            $table->string('photo_4')->nullable();
        }
    });
}

public function down(): void
{
    Schema::table('books', function (Blueprint $table) {
        if (Schema::hasColumn('books', 'photo_2')) {
            $table->dropColumn('photo_2');
        }

        if (Schema::hasColumn('books', 'photo_3')) {
            $table->dropColumn('photo_3');
        }

        if (Schema::hasColumn('books', 'photo_4')) {
            $table->dropColumn('photo_4');
        }
    });
}


};
