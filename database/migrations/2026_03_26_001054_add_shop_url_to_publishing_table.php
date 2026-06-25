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
       if (Schema::hasTable('publishing') && ! Schema::hasColumn('publishing', 'shop_url')) {
           Schema::table('publishing', function (Blueprint $table) {
    $table->string('shop_url')->nullable()->after('category');
});
       }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       if (Schema::hasTable('publishing') && Schema::hasColumn('publishing', 'shop_url')) {
           Schema::table('publishing', function (Blueprint $table) {
    $table->dropColumn('shop_url');
});
       }
    }
};
