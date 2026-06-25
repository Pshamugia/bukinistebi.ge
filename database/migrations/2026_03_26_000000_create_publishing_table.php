<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('publishing')) {
            Schema::create('publishing', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('category')->nullable();
                $table->string('shop_url')->nullable();
                $table->text('description')->nullable();
                $table->string('image_1')->nullable();
                $table->string('image_2')->nullable();
                $table->string('image_3')->nullable();
                $table->string('image_4')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publishing');
    }
};
