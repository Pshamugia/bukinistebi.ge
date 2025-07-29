<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Auction;
use App\Models\Bid;

class CloseExpiredAuctions extends Command
{
    protected $signature = 'auctions:close-expired';
    protected $description = 'Close auctions that have expired and assign winners';

    public function handle()
    {
        $auctions = Auction::where('is_active', true)
            ->where('end_time', '<', now())
            ->get();

        foreach ($auctions as $auction) {
            $highestBid = $auction->bids()->orderByDesc('amount')->first();

            if ($highestBid) {
                $auction->winner_id = $highestBid->user_id;
            }

            $auction->is_active = false;
            $auction->save();
        }

        $this->info('✅ Closed expired auctions.');
    }
}

