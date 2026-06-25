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
        Schema::table('book_news', function (Blueprint $table) {
            if (! Schema::hasColumn('book_news', 'title_en')) {
                $table->string('title_en')->nullable();
            }

            if (! Schema::hasColumn('book_news', 'description_en')) {
                $table->text('description_en')->nullable();
            }

            if (! Schema::hasColumn('book_news', 'full_en')) {
                $table->longText('full_en')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('book_news', function (Blueprint $table) {
            //
        });
    }
};
