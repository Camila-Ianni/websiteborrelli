<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Comic;
use App\Services\MercadoPagoService;
use App\Services\PayPalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    protected MercadoPagoService $mercadoPago;
    protected PayPalService $paypal;

    public function __construct(MercadoPagoService $mercadoPago, PayPalService $paypal)
    {
        $this->mercadoPago = $mercadoPago;
        $this->paypal = $paypal;
    }

    public function show()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $cartItems = [];
        $subtotal = 0;

        foreach ($cart as $id => $item) {
            $comic = Comic::find($id);
            if ($comic) {
                $cartItems[] = [
                    'comic' => $comic,
                    'quantity' => $item['quantity']
                ];
                $subtotal += $comic->price * $item['quantity'];
            }
        }

        $mercadoPagoEnabled = $this->mercadoPago->isConfigured();
        $paypalEnabled = $this->paypal->isConfigured();

        return view('checkout.show', compact('cartItems', 'subtotal', 'mercadoPagoEnabled', 'paypalEnabled'));
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_dni' => 'required|string|max:20',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:30',
            'payment_method' => 'required|in:transfer,mercadopago,paypal',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        DB::beginTransaction();

        try {
            $total = 0;
            $orderItems = [];

            // Validate stock and calculate total
            foreach ($cart as $id => $item) {
                $comic = Comic::lockForUpdate()->find($id);
                
                if (!$comic || $comic->stock < $item['quantity']) {
                    DB::rollBack();
                    return redirect()->route('cart.index')
                        ->with('error', 'Insufficient stock for ' . ($comic ? $comic->title : 'one of the items'));
                }

                $subtotal = $comic->price * $item['quantity'];
                $total += $subtotal;

                $orderItems[] = [
                    'comic' => $comic,
                    'quantity' => $item['quantity'],
                    'price' => $comic->price,
                    'subtotal' => $subtotal,
                ];
            }

            // Create order
            $order = Order::create([
                'customer_name' => $validated['customer_name'],
                'customer_dni' => $validated['customer_dni'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'] ?? null,
                'order_number' => Order::generateOrderNumber(),
                'total' => $total,
                'status' => 'pending',
                'payment_method' => $validated['payment_method'],
            ]);

            // Create order items and update stock
            foreach ($orderItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'comic_id' => $item['comic']->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);

                // Decrease stock
                $item['comic']->decrement('stock', $item['quantity']);
            }

            DB::commit();

            // Clear cart
            session()->forget('cart');

            // If MercadoPago, create preference and redirect
            if ($validated['payment_method'] === 'mercadopago' && $this->mercadoPago->isConfigured()) {
                return $this->processWithMercadoPago($order, $orderItems, $validated);
            }

            // If PayPal, create order and redirect
            if ($validated['payment_method'] === 'paypal' && $this->paypal->isConfigured()) {
                return $this->processWithPayPal($order, $orderItems, $validated);
            }

            // Transfer: redirect to success page
            return redirect()->route('checkout.success', ['order' => $order->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.index')
                ->with('error', 'An error occurred while processing your order. Please try again.');
        }
    }

    protected function processWithMercadoPago(Order $order, array $orderItems, array $customerData)
    {
        $items = array_map(function ($item) {
            return [
                'comic_id' => $item['comic']->id,
                'title' => $item['comic']->title,
                'description' => substr($item['comic']->description ?? 'Comic', 0, 100),
                'image_url' => $item['comic']->image_url,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ];
        }, $orderItems);

        $payer = [
            'name' => $customerData['customer_name'],
            'email' => $customerData['customer_email'],
            'dni' => $customerData['customer_dni'],
        ];

        $backUrls = [
            'success' => route('checkout.success', ['order' => $order->id]),
            'failure' => route('checkout.failure', ['order' => $order->id]),
            'pending' => route('checkout.pending', ['order' => $order->id]),
        ];

        $preference = $this->mercadoPago->createPreference(
            $items,
            $payer,
            $backUrls,
            $order->order_number
        );

        if ($preference && isset($preference['init_point'])) {
            $order->update([
                'payment_preference_id' => $preference['id'],
            ]);

            return redirect()->away($preference['init_point']);
        }

        // Si falla MP, mostrar página con datos de transferencia como fallback
        return redirect()->route('checkout.success', ['order' => $order->id])
            ->with('warning', 'No se pudo conectar con MercadoPago. Puede realizar el pago por transferencia.');
    }

    public function success(Request $request, Order $order)
    {
        $order->load('items.comic');
        
        // Check for MercadoPago callback params
        $paymentId = $request->query('payment_id');
        $status = $request->query('status');
        $externalReference = $request->query('external_reference');

        if ($paymentId && $status === 'approved') {
            $order->update([
                'status' => 'paid',
                'payment_id' => $paymentId,
                'payment_status' => $status,
            ]);
        }

        return view('checkout.success', compact('order'));
    }

    public function failure(Request $request, Order $order)
    {
        $order->load('items.comic');
        return view('checkout.failure', compact('order'));
    }

    public function pending(Request $request, Order $order)
    {
        $order->load('items.comic');
        
        $paymentId = $request->query('payment_id');
        if ($paymentId) {
            $order->update([
                'payment_id' => $paymentId,
                'payment_status' => 'pending',
            ]);
        }

        return view('checkout.pending', compact('order'));
    }

    protected function processWithPayPal(Order $order, array $orderItems, array $validated): \Illuminate\Http\RedirectResponse
    {
        $items = array_map(function ($item) {
            return [
                'name' => $item['comic']->title,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ];
        }, $orderItems);

        $returnUrl = route('checkout.paypal.success');
        $cancelUrl = route('checkout.paypal.cancel');

        $paypalOrder = $this->paypal->createOrder($items, $order->total_amount, $order->order_number, $returnUrl, $cancelUrl);

        if (!$paypalOrder) {
            return redirect()->route('checkout.show')->with('error', 'PayPal error. Try another payment method.');
        }

        $order->update([
            'paypal_order_id' => $paypalOrder['id'],
        ]);

        $approveLink = collect($paypalOrder['links'])->firstWhere('rel', 'approve')['href'];

        return redirect()->away($approveLink);
    }

    public function paypalSuccess(Request $request)
    {
        $token = $request->query('token');
        
        $order = Order::where('paypal_order_id', $token)->firstOrFail();

        $capture = $this->paypal->captureOrder($token);

        if (!$capture) {
            return redirect()->route('checkout.paypal.cancel', ['order' => $order->order_number]);
        }

        $captureId = $capture['purchase_units'][0]['payments']['captures'][0]['id'] ?? null;
        $status = $capture['status'];

        if ($status === 'COMPLETED') {
            $order->update([
                'status' => 'paid',
                'payment_status' => 'paid',
                'paypal_capture_id' => $captureId,
            ]);

            $order->load('items.comic');
            return view('checkout.success', compact('order'));
        }

        return redirect()->route('checkout.paypal.cancel', ['order' => $order->order_number]);
    }

    public function paypalCancel(Request $request)
    {
        $orderNumber = $request->query('order');
        $order = Order::where('order_number', $orderNumber)->first();

        if ($order) {
            $order->load('items.comic');
            return view('checkout.failure', compact('order'));
        }

        return redirect()->route('home')->with('error', 'Order not found.');
    }
}
