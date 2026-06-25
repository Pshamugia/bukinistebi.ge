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
    if (! Schema::hasColumn('books', 'condition')) {
        Schema::table('books', function (Blueprint $table) {
            $table->string('condition')->nullable()->after('quantity');
        });
    }
}

public function down()
{
    if (Schema::hasColumn('books', 'condition')) {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('condition');
        });
    }
}

};
