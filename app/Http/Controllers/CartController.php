<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getCart();

        return view('cart.index', [
            'cart' => $cart,
            'totals' => $this->calculateTotals($cart),
        ]);
    }

    public function add(Request $request, Product $product)
    {
        $data = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $quantity = (int) ($data['quantity'] ?? 1);

        $cart = $this->getCart();
        $existing = $cart['items'][$product->id]['quantity'] ?? 0;

        if ($existing + $quantity > $product->stock) {
            throw ValidationException::withMessages([
                'quantity' => __('messages.stock_unavailable'),
            ]);
        }

        $cart['items'][$product->id] = [
            'product_id' => $product->id,
            'title' => $product->title,
            'slug' => $product->slug,
            'price' => (float) $product->price,
            'quantity' => $existing + $quantity,
            'image_url' => $product->image_url,
        ];

        session(['cart' => $cart]);
        session()->flash('success', __('messages.cart_item_added'));

        return back();
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $quantity = (int) $data['quantity'];
        $cart = $this->getCart();

        if (! isset($cart['items'][$product->id])) {
            throw ValidationException::withMessages([
                'quantity' => __('messages.cart_item_missing'),
            ]);
        }

        if ($quantity === 0) {
            unset($cart['items'][$product->id]);
        } elseif ($quantity > $product->stock) {
            throw ValidationException::withMessages([
                'quantity' => __('messages.stock_unavailable'),
            ]);
        } else {
            $cart['items'][$product->id]['quantity'] = $quantity;
        }

        session(['cart' => $cart]);
        session()->flash('success', __('messages.cart_updated'));

        return back();
    }

    public function remove(Product $product)
    {
        $cart = $this->getCart();
        unset($cart['items'][$product->id]);
        session(['cart' => $cart]);
        session()->flash('success', __('messages.cart_item_removed'));

        return back();
    }

    public function clear()
    {
        session()->forget('cart');
        session()->flash('success', __('messages.cart_cleared'));

        return back();
    }

    private function getCart(): array
    {
        return session('cart', ['items' => []]);
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
}
