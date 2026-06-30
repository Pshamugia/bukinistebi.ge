<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('page_views')) {
            return;
        }

        DB::statement('ALTER TABLE page_views ADD PRIMARY KEY (id)');
        DB::statement('ALTER TABLE page_views MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
    }

    public function down(): void
    {
        if (!Schema::hasTable('page_views')) {
            return;
        }

        DB::statement('ALTER TABLE page_views MODIFY id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE page_views DROP PRIMARY KEY');
    }
};
