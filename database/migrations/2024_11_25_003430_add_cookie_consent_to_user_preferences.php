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
        if (Schema::hasTable('user_preferences') && ! Schema::hasColumn('user_preferences', 'cookie_consent')) {
            Schema::table('user_preferences', function (Blueprint $table) {
                $table->string('cookie_consent')->default('not_given');
            });
        }
    }
    
    public function down()
    {
        if (Schema::hasTable('user_preferences') && Schema::hasColumn('user_preferences', 'cookie_consent')) {
            Schema::table('user_preferences', function (Blueprint $table) {
                $table->dropColumn('cookie_consent');
            });
        }
    }
};
