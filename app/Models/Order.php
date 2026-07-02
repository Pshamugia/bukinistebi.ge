<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    public const STATUS_COURIER_PICKED_UP = 'courier_picked_up';
    public const STATUS_DELIVERED = 'delivered';

    public static $statusesMap = [
        'Created' => "შექმნილი",
        'Pending' => "მუშავდება",
        'Processing' => "მუშავდება",
        'Succeeded' => "წარმატებული",
        'Returned' => "თანხა დაბრუნებულია",
        'Delivered' => "მიწოდებულია",
        'paid' => 'გადახდილია',
        'processing' => 'მუშავდება',
        self::STATUS_COURIER_PICKED_UP => 'კურიერმა აიღო',
        self::STATUS_DELIVERED => 'პროდუქტი ჩაიბარა მყიდველმა',
    ];

    // Define the fillable properties
    protected $fillable = ['user_id', 'courier_id', 'order_id', 'subtotal', 'shipping', 'total', 'status', 'courier_picked_up_at', 'delivered_at', 'courier_note', 'address', 'delivery_latitude', 'delivery_longitude', 'name', 'phone', 'email', 'payment_method', 'city',  'failed_payment_reminder_sent_at',];

    protected $casts = [
        'courier_picked_up_at' => 'datetime',
        'delivered_at' => 'datetime',
        'failed_payment_reminder_sent_at' => 'datetime',
    ];

    public static function statusLabel(?string $status): string
    {
        if (!$status) {
            return '';
        }

        $statusMap = array_change_key_case(self::$statusesMap, CASE_LOWER);

        return $statusMap[strtolower($status)] ?? $status;
    }

    // Relationship with OrderItem model
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relationship with User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courier()
    {
        return $this->belongsTo(User::class, 'courier_id');
    }

    // protected static function boot()
    // {
    //     parent::boot();
    //     static::creating(function ($order) {
    //         $order->order_id = 'ORD-' . uniqid();
    //     });
    // }
}
