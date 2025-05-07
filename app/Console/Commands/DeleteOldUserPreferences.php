<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserPreference;

class DeleteOldUserPreferences extends Command
{
    protected $signature = 'user-preferences:clean';
    protected $description = 'Delete all user behavior logs from user_preferences table';

    public function handle()
    {
        $count = UserPreference::count();

        UserPreference::truncate(); // 🧹 Delete all records
        $this->info("Deleted {$count} user behavior records.");
    }
}
