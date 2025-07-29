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
    Schema::table('orders', function (Blueprint $table) {
        if (!Schema::hasColumn('orders', 'order_id')) {
            $table->unsignedBigInteger('order_id')->nullable();
        }
    });
}

public function down(): void
{
    Schema::table('orders', function (Blueprint $table) {
        if (Schema::hasColumn('orders', 'order_id')) {
            $table->dropColumn('order_id');
        }
    });
}
};
