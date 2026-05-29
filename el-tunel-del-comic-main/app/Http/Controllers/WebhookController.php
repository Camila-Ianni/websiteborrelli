<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MercadoPagoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    protected MercadoPagoService $mercadoPago;

    public function __construct(MercadoPagoService $mercadoPago)
    {
        $this->mercadoPago = $mercadoPago;
    }

    /**
     * Handle MercadoPago webhook notifications
     */
    public function mercadopago(Request $request)
    {
        Log::info('MercadoPago webhook received', $request->all());

        // Verify signature if configured
        $xSignature = $request->header('x-signature');
        $xRequestId = $request->header('x-request-id');
        
        $type = $request->input('type');
        $data = $request->input('data');

        // Handle payment notifications
        if ($type === 'payment' && isset($data['id'])) {
            $paymentId = $data['id'];
            
            // Get payment details from MercadoPago
            $payment = $this->mercadoPago->getPayment($paymentId);
            
            if ($payment) {
                $this->processPayment($payment);
            }
        }

        return response()->json(['status' => 'ok'], 200);
    }

    /**
     * Process payment notification and update order
     */
    protected function processPayment(array $payment)
    {
        $externalReference = $payment['external_reference'] ?? null;
        $status = $payment['status'] ?? null;
        $paymentId = $payment['id'] ?? null;

        if (!$externalReference) {
            Log::warning('MercadoPago payment without external_reference', ['payment_id' => $paymentId]);
            return;
        }

        $order = Order::where('order_number', $externalReference)->first();

        if (!$order) {
            Log::warning('Order not found for external_reference', ['external_reference' => $externalReference]);
            return;
        }

        // Map MercadoPago status to our order status
        $orderStatus = match ($status) {
            'approved' => 'paid',
            'pending', 'in_process', 'authorized' => 'pending',
            'rejected', 'cancelled', 'refunded', 'charged_back' => 'cancelled',
            default => $order->status,
        };

        // If payment was rejected, restore stock
        if (in_array($status, ['rejected', 'cancelled', 'refunded', 'charged_back']) && $order->status === 'pending') {
            foreach ($order->items as $item) {
                $item->comic->increment('stock', $item->quantity);
            }
        }

        $order->update([
            'status' => $orderStatus,
            'payment_id' => $paymentId,
            'payment_status' => $status,
        ]);

        Log::info('Order updated from webhook', [
            'order_number' => $order->order_number,
            'status' => $orderStatus,
            'payment_id' => $paymentId,
        ]);
    }
}
