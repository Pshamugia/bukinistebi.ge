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
        Schema::table('carts', function (Blueprint $table) {
            $table->unsignedBigInteger('publisher_id')->nullable()->after('user_id');
            $table->foreign('publisher_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign(['publisher_id']);
            $table->dropColumn('publisher_id');
        });
    }
    
};
