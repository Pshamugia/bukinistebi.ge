<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // CART ITEMS: allow bundle rows
        if (Schema::hasTable('cart_items')) {
            Schema::table('cart_items', function (Blueprint $t) {
                if (Schema::hasColumn('cart_items','book_id')) {
                    $t->unsignedBigInteger('book_id')->nullable()->change(); // book is null for bundle rows
                }
                if (!Schema::hasColumn('cart_items','bundle_id')) {
                    $t->foreignId('bundle_id')->nullable()->after('book_id')
                      ->constrained('bundles')->nullOnDelete();
                }
                if (!Schema::hasColumn('cart_items','meta')) {
                    $t->json('meta')->nullable()->after('quantity');
                }
                // Optional: avoid duplicates per cart
                if (!Schema::hasColumn('cart_items','bundle_id')) return;
            });
        }

        // ORDER ITEMS: allow bundle rows
        if (Schema::hasTable('order_items')) {
            Schema::table('order_items', function (Blueprint $t) {
                if (Schema::hasColumn('order_items','book_id')) {
                    $t->unsignedBigInteger('book_id')->nullable()->change();
                }
                if (!Schema::hasColumn('order_items','bundle_id')) {
                    $t->foreignId('bundle_id')->nullable()->after('book_id')
                      ->constrained('bundles')->nullOnDelete();
                }
                if (!Schema::hasColumn('order_items','meta')) {
                    $t->json('meta')->nullable()->after('price');
                }
            });
        }
    }

    public function down(): void {
        if (Schema::hasTable('cart_items')) {
            Schema::table('cart_items', function (Blueprint $t) {
                if (Schema::hasColumn('cart_items','bundle_id')) $t->dropConstrainedForeignId('bundle_id');
                if (Schema::hasColumn('cart_items','meta')) $t->dropColumn('meta');
            });
        }
        if (Schema::hasTable('order_items')) {
            Schema::table('order_items', function (Blueprint $t) {
                if (Schema::hasColumn('order_items','bundle_id')) $t->dropConstrainedForeignId('bundle_id');
                if (Schema::hasColumn('order_items','meta')) $t->dropColumn('meta');
            });
        }
    }
};
