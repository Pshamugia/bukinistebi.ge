<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartItemsTable extends Migration
{
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->unsignedBigInteger('cart_id');
            $table->unsignedBigInteger('book_id');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 8, 2);
            $table->timestamps();

            // Foreign Keys
            $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');

            // Indexes
            $table->index('cart_id');
            $table->index('book_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cart_items');
    }
}
