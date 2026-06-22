<?php

namespace App\Console\Commands;

use App\Mail\FailedPaymentReminderMail;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendFailedPaymentReminders extends Command
{
    protected $signature = 'orders:send-failed-payment-reminders';

    protected $description = 'Send reminder emails for failed or expired payments';

    public function handle(): int
{
    $orders = Order::with('user')
        ->where('payment_method', 'bank_transfer')
        ->whereIn('status', ['Failed', 'Expired', 'failed', 'expired'])
        ->whereNull('failed_payment_reminder_sent_at')
        ->where('updated_at', '<=', now()->subMinutes(20))
        ->get();

    foreach ($orders as $order) {
        $email = $order->email ?? optional($order->user)->email;

        if (!$email) {
            continue;
        }

        // IMPORTANT: mark as sent BEFORE sending email
        $updated = Order::where('id', $order->id)
            ->whereNull('failed_payment_reminder_sent_at')
            ->update([
                'failed_payment_reminder_sent_at' => now(),
            ]);

        if (!$updated) {
            continue;
        }

        try {
            Mail::to($email)->send(new FailedPaymentReminderMail($order));
        } catch (\Throwable $e) {
            Log::error('Failed payment reminder email failed', [
                'order_id' => $order->id,
                'email' => $email,
                'error' => $e->getMessage(),
            ]);
        }
    }

    return self::SUCCESS;
}
}