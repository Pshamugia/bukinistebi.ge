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
        if (Schema::hasTable('user_preferences')) {
            Schema::table('user_preferences', function (Blueprint $table) {
                if (! Schema::hasColumn('user_preferences', 'guest_id')) {
                    $table->string('guest_id')->nullable()->after('user_id');
                }

                if (! Schema::hasColumn('user_preferences', 'date')) {
                    $table->date('date')->nullable()->after('user_name');
                }
            });
        }
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_preferences', function (Blueprint $table) {
            //
        });
    }
};
