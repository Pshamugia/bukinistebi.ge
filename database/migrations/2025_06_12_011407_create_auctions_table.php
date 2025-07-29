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
        Schema::create('auctions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->decimal('start_price', 8, 2);
            $table->decimal('current_price', 8, 2)->nullable();
            $table->timestamp('start_time')->nullable(); // ✅ Add nullable()
            $table->timestamp('end_time')->nullable();   // ✅ Add nullable()
            $table->boolean('is_active')->default(true);
            $table->foreignId('winner_id')->nullable()->constrained('users');
            $table->timestamps();
        });
        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auctions');
    }
};
