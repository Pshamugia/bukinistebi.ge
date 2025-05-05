<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SearchKeyword;
use Carbon\Carbon;

class CleanupSearchKeywords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up search keywords older than 1 month and show top 10 keywords for the current month.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Step 1: Delete old keywords
        SearchKeyword::where('created_at', '<', Carbon::now()->subMonth())->delete();
        $this->info('Deleted keywords older than 1 month.');

        // Step 2: Fetch top 10 keywords for the current month
        $topKeywords = SearchKeyword::select('keyword')
            ->where('created_at', '>=', Carbon::now()->subMonth())
            ->groupBy('keyword')
            ->selectRaw('COUNT(*) as count')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(10)
            ->get();

        // Display the results in the console (optional)
        $this->info('Top 10 Keywords for the Current Month:');
        foreach ($topKeywords as $keyword) {
            $this->info("{$keyword->keyword}: {$keyword->count}");
        }

        return 0;
    }
}
