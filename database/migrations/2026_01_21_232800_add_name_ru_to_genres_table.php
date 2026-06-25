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
    if (! Schema::hasColumn('genres', 'name_ru')) {
        Schema::table('genres', function (Blueprint $table) {
            $table->string('name_ru')->nullable()->after('name_en');
        });
    }
}

public function down()
{
    if (Schema::hasColumn('genres', 'name_ru')) {
        Schema::table('genres', function (Blueprint $table) {
            $table->dropColumn('name_ru');
        });
    }
}
};
