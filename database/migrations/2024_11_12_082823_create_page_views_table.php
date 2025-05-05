<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageViewsTable extends Migration
{
    public function up()
    {
        // Check if the table already exists
        if (!Schema::hasTable('page_views')) {
            Schema::create('page_views', function (Blueprint $table) {
                $table->id();
                $table->string('url'); // Store the URL visited
                $table->ipAddress('ip_address'); // Store the visitor's IP address
                $table->timestamp('created_at')->useCurrent(); // Timestamp of the visit
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('page_views')) {
            Schema::dropIfExists('page_views');
        }
    }
}
