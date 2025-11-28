<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth; // <-- Kita tambahkan ini biar jelas

class DashboardController extends Controller
{
    /**
     * Menampilkan Dashboard Admin/Owner
     */
    public function index()
    {
        // 1. Total Pendapatan (Hanya hitung yang statusnya 'completed')
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');

        // 2. Pesanan Baru (Pending)
        $pendingOrders = Order::where('status', 'pending')->count();

        // 3. Total Semua Pesanan
        $totalOrders = Order::count();

        // 4. Total Pelanggan (User biasa)
        $totalUsers = User::where('role', 'user')->count();

        // 5. 5 Pesanan Terakhir
        $recentOrders = Order::with('user')->orderBy('created_at', 'desc')->take(5)->get();

        // LOGIKA PENENTUAN VIEW (DIPERBAIKI AGAR TIDAK MERAH)
        /** @var \App\Models\User $user */
        $user = Auth::user(); // Kita simpan user ke variabel dulu
        
        $view = $user->role === 'admin' ? 'dashboard.adminDashboard' : 'dashboard.ownerDashboard';

        return view($view, [
            'totalRevenue' => $totalRevenue,
            'pendingOrders' => $pendingOrders,
            'totalOrders' => $totalOrders,
            'totalUsers' => $totalUsers,
            'recentOrders' => $recentOrders
        ]);
    }
}