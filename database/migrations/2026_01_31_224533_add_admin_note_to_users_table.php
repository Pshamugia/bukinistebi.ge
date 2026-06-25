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
        // database/migrations/xxxx_add_admin_note_to_users_table.php
if (! Schema::hasColumn('users', 'admin_note')) {
    Schema::table('users', function (Blueprint $table) {
        $table->text('admin_note')->nullable()->after('phone');
    });
}

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
