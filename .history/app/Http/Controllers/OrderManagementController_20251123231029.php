<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order; // Import Model Order

class OrderManagementController extends Controller
{
    public function index()
    {
        // Ambil semua order, urutkan dari yang terbaru
        // 'with('user')' gunanya agar kita bisa langsung ambil nama pemesan
        $orders = Order::with('user')->orderBy('created_at', 'desc')->get();

        return view('dashboard.orderManagement', [
            'orders' => $orders
        ]);
    }
}