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
            $table->decimal('min_bid', 8, 2)->nullable();  // e.g. 10.00
            $table->decimal('max_bid', 8, 2)->nullable();  // e.g. 100.00
            $table->boolean('is_free_bid')->default(false); // allows any amount starting from 1
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
