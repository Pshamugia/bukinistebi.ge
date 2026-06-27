<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('auctions', function (Blueprint $table) {
            $table->decimal('buy_now_price', 10, 2)->nullable()->after('start_price');
            $table->foreignId('buy_now_user_id')
                ->nullable()
                ->after('winner_id')
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('buy_now_reserved_until')->nullable()->after('buy_now_user_id');
            $table->timestamp('bought_now_at')->nullable()->after('buy_now_reserved_until');
        });
    }

    public function down(): void
    {
        Schema::table('auctions', function (Blueprint $table) {
            $table->dropForeign(['buy_now_user_id']);
            $table->dropColumn(['buy_now_price', 'buy_now_user_id', 'buy_now_reserved_until', 'bought_now_at']);
        });
    }
};
