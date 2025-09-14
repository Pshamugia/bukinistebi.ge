<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderInvoice extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        // preload relations so the blade can loop items
        $this->order = $order->load(['orderItems.book', 'orderItems.bundle.books']);
    }

    public function build()
    {
        return $this->subject('ინვოისი / Order Receipt — ' . $this->order->order_id)
            ->markdown('emails.order_invoice');
    }
}
