<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    private const STATUSES = ['pending', 'paid', 'shipped', 'delivered', 'cancelled'];

    public function index()
    {
        return view('admin.orders.index', [
            'orders' => Order::latest()->paginate(15),
        ]);
    }

    public function show(Order $order)
    {
        $order->load(['items.product']);

        return view('admin.orders.show', [
            'order' => $order,
            'statuses' => self::STATUSES,
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'in:' . implode(',', self::STATUSES)],
        ]);

        DB::transaction(function () use ($order, $data) {
            $lockedOrder = Order::query()
                ->with('items')
                ->lockForUpdate()
                ->findOrFail($order->id);

            $previousStatus = $lockedOrder->status;
            $nextStatus = $data['status'];

            if ($previousStatus !== 'cancelled' && $nextStatus === 'cancelled') {
                $this->restoreOrderStock($lockedOrder);
            }

            if ($previousStatus === 'cancelled' && $nextStatus !== 'cancelled') {
                $this->reserveOrderStock($lockedOrder);
            }

            $lockedOrder->update(['status' => $nextStatus]);
        });

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', __('messages.order_updated'));
    }

    private function restoreOrderStock(Order $order): void
    {
        foreach ($this->lockedProductsFor($order) as $item) {
            $item->product?->incrementStock($item->quantity);
        }
    }

    private function reserveOrderStock(Order $order): void
    {
        foreach ($this->lockedProductsFor($order) as $item) {
            if (! $item->product || $item->quantity > $item->product->stock) {
                throw ValidationException::withMessages([
                    'status' => __('messages.stock_unavailable'),
                ]);
            }

            $item->product->decrementStock($item->quantity);
        }
    }

    private function lockedProductsFor(Order $order)
    {
        $order->loadMissing('items');

        $products = Product::withTrashed()
            ->whereIn('id', $order->items->pluck('product_id')->filter()->unique())
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        return $order->items->each(function ($item) use ($products): void {
            $item->setRelation('product', $products->get($item->product_id));
        });
    }
}
