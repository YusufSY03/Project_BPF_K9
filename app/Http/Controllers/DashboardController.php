<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; // Pastikan ini ada
use App\Models\Order;
use App\Models\User;
use App\Models\MenuItem; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Menampilkan Dashboard Admin/Owner
     */
    public function index(Request $request) // Tambahkan Request $request disini
    {
        // --- DATA UMUM ---
        $today = Carbon::today();
        
        // 1. Total Pendapatan (Hanya status 'completed')
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');

        // 2. Pesanan Baru (Pending)
        $pendingOrders = Order::where('status', 'pending')->count();

        // 3. Total Semua Pesanan
        $totalOrders = Order::count();

        // 4. Total Pelanggan & Karyawan
        $totalUsers = User::where('role', 'user')->count();
        $totalEmployees = User::where('role', 'admin')->count(); 

        // 5. Statistik Hari Ini
        $menusTotal = MenuItem::count(); 
        $customersToday = User::where('role', 'user')->whereDate('created_at', $today)->count();

        // --- LOGIKA FILTER GRAFIK (HARIAN / MINGGUAN / BULANAN) ---
        $filter = $request->query('filter', 'weekly'); // Default 'weekly'
        $chartData = [];

        if ($filter == 'daily') {
            // HARIAN: Tampilkan data per JAM hari ini
            $data = Order::select(
                DB::raw('HOUR(created_at) as label'), 
                DB::raw('SUM(total_amount) as total')
            )
            ->where('status', 'completed')
            ->whereDate('created_at', $today)
            ->groupBy('label')
            ->orderBy('label', 'asc')
            ->get();
            
            // Format label jadi "09:00", "10:00", dst
            $chartData = $data->map(function($item) {
                return [
                    'label' => sprintf('%02d:00', $item->label),
                    'total' => $item->total
                ];
            });

        } elseif ($filter == 'monthly') {
            // BULANAN: Tampilkan data per BULAN tahun ini
            $data = Order::select(
                DB::raw('MONTH(created_at) as label'), 
                DB::raw('SUM(total_amount) as total')
            )
            ->where('status', 'completed')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('label')
            ->orderBy('label', 'asc')
            ->get();

            // Ubah angka bulan (1) jadi nama bulan (Jan)
            $chartData = $data->map(function($item) {
                return [
                    'label' => Carbon::create()->month($item->label)->locale('id')->isoFormat('MMMM'),
                    'total' => $item->total
                ];
            });

        } else {
            // MINGGUAN (Default): 7 Hari Terakhir
            $data = Order::select(
                DB::raw('DATE(created_at) as label'), 
                DB::raw('SUM(total_amount) as total')
            )
            ->where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('label')
            ->orderBy('label', 'asc')
            ->get();

            $chartData = $data->map(function($item) {
                return [
                    'label' => Carbon::parse($item->label)->locale('id')->isoFormat('dddd'), // Senin, Selasa...
                    'total' => $item->total
                ];
            });
        }

        // --- DATA UNTUK GRAFIK STATUS ORDER (Donat) ---
        $orderStatusData = [
            'pending'    => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'completed'  => Order::where('status', 'completed')->count(),
            'cancelled'  => Order::where('status', 'cancelled')->count(),
        ];

        // --- DATA BEST SELLER & LOYAL CUSTOMERS ---
        $bestSellers = MenuItem::take(3)->get(); 
        $loyalCustomers = Order::select('user_id', DB::raw('count(*) as total'))
            ->with('user')
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->orderBy('total', 'desc')
            ->take(3)
            ->get();

        // --- LOGIKA PEMILIHAN VIEW ---
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            return view('dashboard.adminDashboard', [
                'totalRevenue' => $totalRevenue,
                'pendingOrders' => $pendingOrders,
                'totalOrders' => $totalOrders,
                'totalUsers' => $totalUsers,
                'recentOrders' => Order::with('user')->latest()->take(5)->get()
            ]);
        }

        return view('dashboard.ownerDashboard', [
            'user' => $user,
            'totalRevenue' => $totalRevenue,
            'menusTotal' => $menusTotal,
            'customersToday' => $customersToday,
            'totalEmployees' => $totalEmployees,
            'salesChartData' => $chartData, // Data grafik dinamis sesuai filter
            'currentFilter' => $filter,     // Kirim filter aktif ke view
            'orderStatusData' => $orderStatusData, 
            'bestSellers' => $bestSellers,
            'loyalCustomers' => $loyalCustomers,
        ]);
    }
}