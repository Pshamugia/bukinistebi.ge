<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('title');
            $table->string('photo');
            $table->text('description');
            $table->longText('full');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('author_id');
            $table->decimal('price', 8, 2);
            $table->timestamps();

            // Foreign Keys
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('author_id')->references('id')->on('authors')->onDelete('cascade');

            // Indexes
            $table->index('category_id');
            $table->index('author_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('books');
    }
}
