<?php

namespace App\Http\Controllers;

use App\Models\Comic;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $cartItems = [];
        $subtotal = 0;
        $totalItems = 0;

        foreach ($cart as $id => $item) {
            $comic = Comic::with('publisher')->find($id);
            if ($comic) {
                $cartItems[] = [
                    'comic' => $comic,
                    'quantity' => $item['quantity']
                ];
                $subtotal += $comic->price * $item['quantity'];
                $totalItems += $item['quantity'];
            }
        }

        return view('cart.index', compact('cartItems', 'subtotal', 'totalItems'));
    }

    public function add(Request $request, Comic $comic)
    {
        $cart = session()->get('cart', []);
        $quantity = $request->get('quantity', 1);

        if (isset($cart[$comic->id])) {
            $cart[$comic->id]['quantity'] += $quantity;
        } else {
            $cart[$comic->id] = [
                'quantity' => $quantity,
            ];
        }

        // Validate stock
        if ($cart[$comic->id]['quantity'] > $comic->stock) {
            $cart[$comic->id]['quantity'] = $comic->stock;
            session()->flash('warning', 'Only ' . $comic->stock . ' items available in stock.');
        } else {
            session()->flash('success', $comic->title . ' added to cart!');
        }

        session()->put('cart', $cart);
        return redirect()->back();
    }

    public function update(Request $request, Comic $comic)
    {
        $cart = session()->get('cart', []);
        $quantity = $request->get('quantity', 1);

        if ($quantity <= 0) {
            return $this->remove($comic);
        }

        if ($quantity > $comic->stock) {
            $quantity = $comic->stock;
            session()->flash('warning', 'Only ' . $comic->stock . ' items available in stock.');
        }

        if (isset($cart[$comic->id])) {
            $cart[$comic->id]['quantity'] = $quantity;
            session()->put('cart', $cart);
            session()->flash('success', 'Cart updated!');
        }

        return redirect()->route('cart.index');
    }

    public function remove(Comic $comic)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$comic->id])) {
            unset($cart[$comic->id]);
            session()->put('cart', $cart);
            session()->flash('success', $comic->title . ' removed from cart.');
        }

        return redirect()->route('cart.index');
    }

    public function clear()
    {
        session()->forget('cart');
        session()->flash('success', 'Cart cleared!');
        return redirect()->route('cart.index');
    }
}
