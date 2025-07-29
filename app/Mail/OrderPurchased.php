<?php 

// app/Mail/OrderPurchased.php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderPurchased extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $paymentType;

    public function __construct(Order $order, $paymentType)
    {
        $this->order = $order;
        $this->paymentType = $paymentType;  // Store payment method type
    }

    public function build()
    {
        // Use different views based on the payment type
        if ($this->paymentType === 'courier') {
            return $this->subject('გადახდა კურიერთან- Pay with Courier')
                        ->view('order_request');  // Create this Blade for courier payment
        } else {
            return $this->subject('TBC Payment')
                        ->view('tbc_order_emailed');  // Create this Blade for bank transfer payment
        }
    }

    
}
