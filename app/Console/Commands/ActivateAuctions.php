<?php

use App\Models\Auction;
use Illuminate\Console\Command;

class ActivateAuctions extends Command
{
    protected $signature = 'auctions:activate';
    protected $description = 'Activate approved auctions when start_time arrives';

    public function handle()
    {
        $count = Auction::where('is_approved', true)
            ->where('is_active', false)
            ->where('start_time', '<=', now())
            ->update(['is_active' => true]);

        $this->info("Activated {$count} auctions.");
    }
}

