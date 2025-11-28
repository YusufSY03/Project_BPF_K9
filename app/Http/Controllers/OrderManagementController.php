<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderManagementController extends Controller
{
    /**
     * Menampilkan Daftar Pesanan (Tabel)
     */
    public function index(Request $request)
    {
        $query = Order::with('user')->orderBy('created_at', 'desc');

        // Logika Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%$search%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'like', "%$search%");
                  });
            });
        }

        // Logika Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('dashboard.orderManagement', [
            'orders' => $orders
        ]);
    }

    // === FUNGSI INI YANG HILANG DARI FILE ANDA ===

    /**
     * Menampilkan Detail Pesanan
     */
    public function show(Order $order)
    {
        // Muat relasi user dan menu agar bisa ditampilkan
        $order->load(['user', 'items.menu']);

        return view('dashboard.orderDetail', [
            'order' => $order
        ]);
    }

    /**
     * Mengubah Status Pesanan
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('status', 'Status pesanan berhasil diperbarui.');
    }
}