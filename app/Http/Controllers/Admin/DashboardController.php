<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRevenue = Order::where('status', 'paid')->sum('total');
        $totalOrders = Order::count();
        $activeCustomers = Order::select('customer_email')->distinct()->count('customer_email');
        $lowStock = Product::where('stock', '<=', 10)->count();

        return view('admin.dashboard', [
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'activeCustomers' => $activeCustomers,
            'lowStock' => $lowStock,
            'recentOrders' => Order::latest()->take(5)->get(),
        ]);
    }
}
