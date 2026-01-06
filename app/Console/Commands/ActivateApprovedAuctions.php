<?php

namespace App\Console\Commands;

use App\Models\Auction;
use Illuminate\Console\Command;

class ActivateApprovedAuctions extends Command
{
    protected $signature = 'auctions:activate-approved';
    protected $description = 'Activate approved auctions when start_time arrives';

    public function handle(): void
    {
        $count = Auction::where('is_approved', true)
            ->where('is_active', false)
            ->where('start_time', '<=', now())
            ->update(['is_active' => true]);

        $this->info("Activated {$count} auctions.");
    }
}
