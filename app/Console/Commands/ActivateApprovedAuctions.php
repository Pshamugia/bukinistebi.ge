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
        Auction::whereNotNull('buy_now_user_id')
            ->whereNull('winner_id')
            ->whereNull('bought_now_at')
            ->where('is_paid', false)
            ->where('buy_now_reserved_until', '<=', now())
            ->update([
                'buy_now_user_id' => null,
                'buy_now_reserved_until' => null,
            ]);

        $count = Auction::where('is_approved', true)
            ->where('is_active', false)
            ->where('start_time', '<=', now())
            ->where('end_time', '>', now())
            ->whereNull('winner_id')
            ->whereNull('buy_now_user_id')
            ->whereNull('bought_now_at')
            ->where('is_paid', false)
            ->update(['is_active' => true]);

        $this->info("Activated {$count} auctions.");
    }
}
