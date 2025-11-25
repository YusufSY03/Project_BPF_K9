<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderManagementController extends Controller
{
    public function index(Request $request)
    {
        // 1. Siapkan Query Dasar
        $query = Order::with('user')->orderBy('created_at', 'desc');

        // 2. Logika SEARCH (Pencarian)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Cari berdasarkan ID Order
                $q->where('id', 'like', "%$search%")
                  // ATAU Cari berdasarkan Nama User
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'like', "%$search%");
                  });
            });
        }

        // 3. Logika FILTER (Status)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 4. Logika PAGINATION (10 data per halaman)
        // withQueryString() penting agar saat ganti halaman, filter tidak hilang
        $orders = $query->paginate(10)->withQueryString();

        return view('dashboard.orderManagement', [
            'orders' => $orders
        ]);
    }
}
