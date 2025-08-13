<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('books', 'video')) {
            Schema::table('books', function (Blueprint $table) {
                $table->string('video')->nullable()->after('photo');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('books', 'video')) {
            Schema::table('books', function (Blueprint $table) {
                $table->dropColumn('video');
            });
        }
    }
};
