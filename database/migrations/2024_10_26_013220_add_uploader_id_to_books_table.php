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
        Schema::table('books', function (Blueprint $table) {
            $table->unsignedBigInteger('uploader_id')->nullable()->after('id');
            $table->foreign('uploader_id')->references('id')->on('users')->onDelete('set null');
        });
    }
    
    public function down()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropForeign(['uploader_id']);
            $table->dropColumn('uploader_id');
        });
    }
    
};
