<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\MercadoPagoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MercadoPagoWebhookController extends Controller
{
    public function __invoke(Request $request, MercadoPagoService $mercadoPago)
    {
        $paymentId = data_get($request->all(), 'data.id') ?? $request->input('id');

        if (! $paymentId) {
            return response()->json(['status' => 'ignored']);
        }

        $payment = $mercadoPago->getPayment((string) $paymentId);
        $orderNumber = $payment['external_reference'] ?? null;

        if (! $orderNumber) {
            return response()->json(['status' => 'missing_reference']);
        }

        $order = Order::where('order_number', $orderNumber)->first();
        if (! $order) {
            return response()->json(['status' => 'order_not_found']);
        }

        DB::transaction(function () use ($order, $paymentId, $payment) {
            $order = Order::query()
                ->with('items')
                ->lockForUpdate()
                ->findOrFail($order->id);

            $status = $payment['status'] ?? 'pending';

            if ($status === 'approved') {
                $order->update([
                    'status' => 'paid',
                    'payment_gateway_id' => (string) $paymentId,
                ]);
                return;
            }

            if (in_array($status, ['cancelled', 'rejected', 'expired'], true)) {
                if ($order->status !== 'cancelled') {
                    $this->restoreOrderStock($order);
                }

                $order->update([
                    'status' => 'cancelled',
                    'payment_gateway_id' => (string) $paymentId,
                ]);
                return;
            }

            $order->update([
                'status' => 'pending',
                'payment_gateway_id' => (string) $paymentId,
            ]);
        });

        return response()->json(['status' => 'ok']);
    }

    private function restoreOrderStock(Order $order): void
    {
        $products = Product::withTrashed()
            ->whereIn('id', $order->items->pluck('product_id')->filter()->unique())
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        foreach ($order->items as $item) {
            $products->get($item->product_id)?->incrementStock($item->quantity);
        }
    }
}
