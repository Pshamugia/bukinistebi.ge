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
        if (!Schema::hasTable('search_keywords')) {
            Schema::create('search_keywords', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Optional user reference
                $table->string('keyword');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('search_keywords')) {
            Schema::dropIfExists('search_keywords');
        }
    }
};
