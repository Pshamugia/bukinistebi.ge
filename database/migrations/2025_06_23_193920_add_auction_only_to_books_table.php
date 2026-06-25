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
        if (! Schema::hasColumn('books', 'auction_only')) {
            Schema::table('books', function (Blueprint $table) {
                $table->boolean('auction_only')->default(false);
            });
        }
    }
    
    public function down()
    {
        if (Schema::hasColumn('books', 'auction_only')) {
            Schema::table('books', function (Blueprint $table) {
                $table->dropColumn('auction_only');
            });
        }
    }
    
};
