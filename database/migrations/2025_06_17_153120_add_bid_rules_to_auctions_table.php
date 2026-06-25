<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('auctions', function (Blueprint $table) {
            if (! Schema::hasColumn('auctions', 'min_bid')) {
                $table->decimal('min_bid', 8, 2)->nullable();
            }

            if (! Schema::hasColumn('auctions', 'max_bid')) {
                $table->decimal('max_bid', 8, 2)->nullable();
            }

            if (! Schema::hasColumn('auctions', 'is_free_bid')) {
                $table->boolean('is_free_bid')->default(false);
            }
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('auctions', function (Blueprint $table) {
            //
        });
    }
};
