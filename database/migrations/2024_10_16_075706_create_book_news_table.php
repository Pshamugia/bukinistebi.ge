<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookNewsTable extends Migration
{
    public function up()
    {
        Schema::create('book_news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->text('full');
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('book_news');
    }
}
