<?php

namespace App\Services;

use App\Models\Order;

class PayPalService
{
    public function createOrder(Order $order): array
    {
        return [
            'status' => 'pending_setup',
            'message' => __('messages.paypal_pending_setup'),
            'approval_url' => null,
            'order_reference' => $order->order_number,
        ];
    }
}
