<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('book_orders', function (Blueprint $table) {
            $table->boolean('is_done')->default(false)->index()->after('email');
        });
    }
    public function down(): void {
        Schema::table('book_orders', function (Blueprint $table) {
            $table->dropColumn('is_done');
        });
    }
};
