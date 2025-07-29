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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('name')->nullable(); // Add name field
            $table->string('phone')->nullable(); // Add phone field
            $table->string('address')->nullable(); // Add address field
            $table->string('payment_method')->nullable(); // Add address field
         });
    }
    
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('phone');
            $table->dropColumn('address');
            $table->dropColumn('payment_method');
        });
    }
    
};
