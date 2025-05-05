<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SearchKeyword;
use Carbon\Carbon;

class ClearOldSearchKeywords extends Command
{
    protected $signature = 'searchkeywords:clear-old';
    protected $description = 'Clear search keywords older than one month';

    public function handle()
    {
        $date = Carbon::now()->subMonth(); // Calculate date one month ago
        SearchKeyword::where('created_at', '<', $date)->delete(); // Delete old records
        $this->info('Old search keywords cleared successfully.');
    }
}
