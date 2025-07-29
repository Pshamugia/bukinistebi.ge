<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Auction;
use App\Models\Bid;
use Carbon\Carbon;

class EndAuctions extends Command
{
    protected $signature = 'auctions:end';
    protected $description = 'Automatically end expired auctions and set winners';

    public function handle()
    {
        $now = Carbon::now();

        $expiredAuctions = Auction::where('is_active', true)
            ->where('end_time', '<=', $now)
            ->get();

        foreach ($expiredAuctions as $auction) {
            $highestBid = $auction->bids()->orderByDesc('amount')->first();

            if ($highestBid) {
                $auction->winner_id = $highestBid->user_id;
            }

            $auction->is_active = false;
            $auction->save();
        }

        $this->info('Auctions ended and winners set.');
    }
}
