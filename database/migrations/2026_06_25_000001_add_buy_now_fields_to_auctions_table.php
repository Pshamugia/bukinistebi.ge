<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('auctions', 'buy_now_price')) {
            Schema::table('auctions', function (Blueprint $table) {
                $table->decimal('buy_now_price', 10, 2)->nullable()->after('start_price');
            });
        }

        if (! Schema::hasColumn('auctions', 'buy_now_user_id')) {
            Schema::table('auctions', function (Blueprint $table) {
                $table->foreignId('buy_now_user_id')
                    ->nullable()
                    ->after('winner_id')
                    ->constrained('users')
                    ->nullOnDelete();
            });
        }

        if (! Schema::hasColumn('auctions', 'bought_now_at')) {
            Schema::table('auctions', function (Blueprint $table) {
                $table->timestamp('bought_now_at')->nullable()->after('buy_now_user_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('auctions', 'buy_now_user_id')) {
            try {
                Schema::table('auctions', function (Blueprint $table) {
                    $table->dropForeign(['buy_now_user_id']);
                });
            } catch (Throwable $e) {
                //
            }
        }

        $columns = array_filter([
            Schema::hasColumn('auctions', 'buy_now_price') ? 'buy_now_price' : null,
            Schema::hasColumn('auctions', 'buy_now_user_id') ? 'buy_now_user_id' : null,
            Schema::hasColumn('auctions', 'bought_now_at') ? 'bought_now_at' : null,
        ]);

        if ($columns !== []) {
            Schema::table('auctions', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }
};
