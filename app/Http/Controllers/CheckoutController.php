<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\MercadoPagoService;
use App\Services\PayPalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', ['items' => []]);

        if (empty($cart['items'])) {
            return redirect()->route('catalog.index')->withErrors([
                'cart' => __('messages.cart_empty'),
            ]);
        }

        return view('checkout.index', [
            'cart' => $cart,
            'totals' => $this->calculateTotals($cart),
        ]);
    }

    public function store(Request $request, MercadoPagoService $mercadoPago, PayPalService $payPal)
    {
        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_dni' => ['required', 'string', 'max:32'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:32'],
            'payment_method' => ['required', 'in:mercadopago,paypal,transfer'],
        ]);

        $cart = session('cart', ['items' => []]);
        if (empty($cart['items'])) {
            return redirect()->route('catalog.index')->withErrors([
                'cart' => __('messages.cart_empty'),
            ]);
        }

        $order = DB::transaction(function () use ($cart, $data) {
            $productIds = array_keys($cart['items']);
            $products = Product::query()
                ->whereIn('id', $productIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $total = 0;
            foreach ($cart['items'] as $item) {
                $product = $products->get($item['product_id']);
                if (! $product) {
                    throw ValidationException::withMessages([
                        'cart' => __('messages.cart_item_missing'),
                    ]);
                }
                if ($item['quantity'] > $product->stock) {
                    throw ValidationException::withMessages([
                        'cart' => __('messages.stock_unavailable'),
                    ]);
                }
                $total += $item['price'] * $item['quantity'];
            }

            $order = Order::create([
                'order_number' => 'BOR-' . Str::upper(Str::random(8)),
                'customer_name' => $data['customer_name'],
                'customer_dni' => $data['customer_dni'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
                'total' => $total,
                'status' => 'pending',
                'payment_method' => $data['payment_method'],
            ]);

            foreach ($cart['items'] as $item) {
                $product = $products->get($item['product_id']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total' => $item['price'] * $item['quantity'],
                ]);

                $product->decrementStock($item['quantity']);
            }

            return $order;
        });

        session()->forget('cart');

        if ($data['payment_method'] === 'mercadopago') {
            $preference = $mercadoPago->createPreference(
                $order,
                $order->items()->with('product')->get(),
                route('checkout.success'),
                route('checkout.failure'),
                route('checkout.pending'),
                route('webhooks.mercadopago')
            );

            $order->update([
                'payment_gateway_id' => $preference['id'] ?? null,
            ]);

            return redirect()->away($preference['init_point']);
        }

        if ($data['payment_method'] === 'paypal') {
            $paypalOrder = $payPal->createOrder(
                $order,
                route('checkout.paypal.return'),
                route('checkout.paypal.cancel')
            );

            $order->update([
                'payment_gateway_id' => $paypalOrder['id'] ?? null,
            ]);

            if (! empty($paypalOrder['approval_url'])) {
                return redirect()->away($paypalOrder['approval_url']);
            }

            return view('checkout.paypal', [
                'order' => $order,
                'paypal' => $paypalOrder,
            ]);
        }

        return view('checkout.transfer', [
            'order' => $order,
        ]);
    }

    public function paypalReturn(Request $request, PayPalService $payPal)
    {
        $paypalOrderId = $request->query('token');

        if (! is_string($paypalOrderId) || $paypalOrderId === '') {
            return redirect()->route('checkout.failure');
        }

        $currentStatus = Order::query()
            ->where('payment_method', 'paypal')
            ->where('payment_gateway_id', $paypalOrderId)
            ->value('status');

        if (! $currentStatus) {
            return redirect()->route('checkout.failure');
        }

        if ($currentStatus === 'paid') {
            return redirect()->route('checkout.success');
        }

        if ($currentStatus === 'cancelled') {
            return redirect()->route('checkout.failure');
        }

        try {
            $capture = $payPal->captureOrder($paypalOrderId);
        } catch (RuntimeException) {
            return redirect()->route('checkout.failure');
        }

        $paypalStatus = $capture['status'] ?? null;

        DB::transaction(function () use ($paypalOrderId, $paypalStatus) {
            $order = Order::query()
                ->where('payment_method', 'paypal')
                ->where('payment_gateway_id', $paypalOrderId)
                ->lockForUpdate()
                ->first();

            if (! $order) {
                return;
            }

            if ($paypalStatus === 'COMPLETED') {
                $order->update(['status' => 'paid']);
            }
        });

        return $paypalStatus === 'COMPLETED'
            ? redirect()->route('checkout.success')
            : redirect()->route('checkout.pending');
    }

    public function paypalCancel(Request $request)
    {
        $paypalOrderId = $request->query('token');

        if (is_string($paypalOrderId) && $paypalOrderId !== '') {
            DB::transaction(function () use ($paypalOrderId) {
                $order = Order::query()
                    ->where('payment_method', 'paypal')
                    ->where('payment_gateway_id', $paypalOrderId)
                    ->lockForUpdate()
                    ->first();

                if (! $order || $order->status === 'cancelled') {
                    return;
                }

                $this->restoreOrderStock($order);
                $order->update(['status' => 'cancelled']);
            });
        }

        return redirect()->route('checkout.failure');
    }

    public function success()
    {
        return view('checkout.success');
    }

    public function failure()
    {
        return view('checkout.failure');
    }

    public function pending()
    {
        return view('checkout.pending');
    }

    private function calculateTotals(array $cart): array
    {
        $items = $cart['items'] ?? [];
        $subtotal = collect($items)->sum(fn ($item) => $item['price'] * $item['quantity']);

        return [
            'subtotal' => $subtotal,
            'total' => $subtotal,
            'count' => collect($items)->sum('quantity'),
        ];
    }

    private function restoreOrderStock(Order $order): void
    {
        $order->loadMissing('items');
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
