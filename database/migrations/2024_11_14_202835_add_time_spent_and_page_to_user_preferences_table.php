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
                if (! Schema::hasColumn('user_preferences', 'time_spent')) {
                    $table->integer('time_spent')->nullable();
                }

                if (! Schema::hasColumn('user_preferences', 'page')) {
                    $table->string('page')->nullable();
                }
            });
        }
    }
    
    public function down()
    {
        if (Schema::hasTable('user_preferences')) {
            $columns = array_filter([
                Schema::hasColumn('user_preferences', 'time_spent') ? 'time_spent' : null,
                Schema::hasColumn('user_preferences', 'page') ? 'page' : null,
            ]);

            if ($columns !== []) {
                Schema::table('user_preferences', function (Blueprint $table) use ($columns) {
                    $table->dropColumn($columns);
                });
            }
        }
    }
    
};
