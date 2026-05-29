<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'customer_name',
        'customer_dni',
        'customer_email',
        'customer_phone',
        'order_number',
        'total',
        'status',
        'payment_method',
        'payment_id',
        'payment_status',
        'payment_preference_id',
        'paypal_order_id',
        'paypal_capture_id',
    ];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function generateOrderNumber(): string
    {
        return 'ORD-' . strtoupper(uniqid());
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid' || $this->payment_status === 'approved';
    }
}
