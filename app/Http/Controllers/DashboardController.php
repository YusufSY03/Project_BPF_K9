<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\MenuItem; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // --- DATA UMUM (Sama untuk Admin & Owner) ---
        $today = Carbon::today();
        
        // 1. Total Pendapatan (Status 'completed')
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');

        // 2. Pesanan Baru (Pending)
        $pendingOrders = Order::where('status', 'pending')->count();

        // 3. Total Semua Pesanan
        $totalOrders = Order::count();

        // 4. Total Pelanggan
        $totalUsers = User::where('role', 'user')->count();

        // --- LOGIKA FILTER GRAFIK PENJUALAN ---
        $filter = $request->query('filter', 'weekly'); // Default mingguan
        $chartData = [];

        if ($filter == 'daily') {
            // Per Jam (Hari Ini)
            $data = Order::select(DB::raw('HOUR(created_at) as label'), DB::raw('SUM(total_amount) as total'))
                ->where('status', 'completed')->whereDate('created_at', $today)
                ->groupBy('label')->orderBy('label', 'asc')->get();
            $chartData = $data->map(fn($item) => ['label' => sprintf('%02d:00', $item->label), 'total' => $item->total]);

        } elseif ($filter == 'monthly') {
            // Per Bulan (Tahun Ini)
            $data = Order::select(DB::raw('MONTH(created_at) as label'), DB::raw('SUM(total_amount) as total'))
                ->where('status', 'completed')->whereYear('created_at', Carbon::now()->year)
                ->groupBy('label')->orderBy('label', 'asc')->get();
            $chartData = $data->map(fn($item) => ['label' => Carbon::create()->month($item->label)->isoFormat('MMMM'), 'total' => $item->total]);

        } else {
            // Per Hari (7 Hari Terakhir)
            $data = Order::select(DB::raw('DATE(created_at) as label'), DB::raw('SUM(total_amount) as total'))
                ->where('status', 'completed')->where('created_at', '>=', Carbon::now()->subDays(7))
                ->groupBy('label')->orderBy('label', 'asc')->get();
            $chartData = $data->map(fn($item) => ['label' => Carbon::parse($item->label)->isoFormat('dddd'), 'total' => $item->total]);
        }

        // --- DATA STATUS ORDER (GRAFIK DONAT) ---
        $orderStatusData = [
            'pending'    => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'completed'  => Order::where('status', 'completed')->count(),
            'cancelled'  => Order::where('status', 'cancelled')->count(),
        ];

        $user = Auth::user();

        // --- TAMPILAN ADMIN ---
        if ($user->role === 'admin') {
            // Admin butuh data pesanan terbaru untuk dipantau
            $recentOrders = Order::with('user')->orderBy('created_at', 'desc')->take(5)->get();

            return view('dashboard.adminDashboard', [
                'totalRevenue'    => $totalRevenue,
                'pendingOrders'   => $pendingOrders,
                'totalOrders'     => $totalOrders,
                'totalUsers'      => $totalUsers,
                'salesChartData'  => $chartData,       // Data Grafik Batang
                'orderStatusData' => $orderStatusData, // Data Grafik Donat
                'currentFilter'   => $filter,
                'recentOrders'    => $recentOrders     // Tabel Aktivitas
            ]);
        }

        // --- TAMPILAN OWNER (Sudah ada sebelumnya) ---
        return view('dashboard.ownerDashboard', [
            'user' => $user,
            'totalRevenue' => $totalRevenue,
            'menusTotal' => MenuItem::count(),
            'customersToday' => User::where('role', 'user')->whereDate('created_at', $today)->count(),
            'totalEmployees' => User::where('role', 'admin')->count(),
            'salesChartData' => $chartData,
            'currentFilter' => $filter,
            'orderStatusData' => $orderStatusData, 
            'bestSellers' => MenuItem::take(3)->get(), // Owner lihat menu terlaris
            'loyalCustomers' => [], 
        ]);
    }
}