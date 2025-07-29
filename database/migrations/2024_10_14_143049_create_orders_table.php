<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->unsignedBigInteger('user_id');
            $table->decimal('subtotal', 8, 2);
            $table->decimal('shipping', 8, 2);
            $table->decimal('total', 8, 2);
            $table->string('status')->default('pending');
            $table->timestamps();

            // Foreign Key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Index
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
