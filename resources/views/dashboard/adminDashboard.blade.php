@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

@section('content')
{{-- Load Chart.js untuk grafik (jika diperlukan nanti) --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    /* VARIABLES (Warna Khusus Dashboard) */
    :root {
        --orange: #FF4500;
        --purple: #6B46C1;
        --green: #10b981;
        --red: #ef4444;
        --dark-blue: #1e293b;
    }

    /* HEADER DASHBOARD */
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }
    .dashboard-header h2 { font-weight: 800; font-size: 1.8rem; margin: 0; color: var(--dark-blue); }
    .dashboard-header p { color: #64748b; margin-top: 5px; font-size: 0.95rem; }

    /* STATS GRID (KARTU ATAS) */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        transition: transform 0.2s;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); }

    .stat-info h4 { margin: 0; font-size: 0.85rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
    .stat-info h3 { margin: 5px 0 0 0; font-size: 1.8rem; font-weight: 800; color: var(--dark-blue); }
    
    .circle-icon {
        width: 50px; height: 50px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; color: white;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    }

    /* MAIN CONTENT GRID */
    .main-grid {
        display: grid; grid-template-columns: 2fr 1fr; gap: 25px;
    }

    /* CHART & TABLE CARD */
    .chart-card {
        background: white; border-radius: 16px; padding: 25px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); 
        border: 1px solid #f1f5f9; margin-bottom: 25px;
    }
    .section-title {
        font-size: 1.1rem; font-weight: 700; margin-bottom: 20px;
        display: flex; justify-content: space-between; align-items: center;
        color: var(--dark-blue);
    }

    /* LIST ITEM (PESANAN TERBARU) */
    .order-item {
        display: flex; align-items: center; justify-content: space-between;
        padding: 15px 0; border-bottom: 1px solid #f1f5f9;
    }
    .order-item:last-child { border-bottom: none; }
    
    .order-user { font-weight: 700; font-size: 0.95rem; color: var(--dark-blue); display: block; }
    .order-meta { font-size: 0.8rem; color: #94a3b8; }
    
    .order-status {
        padding: 4px 12px; border-radius: 50px; font-size: 0.75rem; font-weight: 700;
        text-transform: uppercase;
    }

    /* DROPDOWN FILTER */
    .filter-select {
        border: 1px solid #e2e8f0; background: #f8fafc;
        padding: 6px 12px; border-radius: 8px;
        font-size: 0.85rem; cursor: pointer; outline: none;
        color: #475569; font-weight: 500;
    }
    
    @media (max-width: 992px) { .main-grid { grid-template-columns: 1fr; } }
</style>

<div>
    {{-- HEADER --}}
    <div class="dashboard-header">
        <div>
            <h2>Dashboard</h2>
            <p>Halo Admin, berikut ringkasan aktivitas toko hari ini.</p>
        </div>
        <div style="text-align: right;">
            <div style="font-weight: 700; font-size: 1.1rem; color: var(--dark-blue);">{{ date('d M Y') }}</div>
            <div style="font-size: 0.9rem; color: #64748b;">{{ date('l') }}</div>
        </div>
    </div>

    {{-- KARTU STATISTIK --}}
    <div class="stats-grid">
        
        {{-- Card 1: Pendapatan --}}
        <div class="stat-card">
            <div class="stat-info">
                <h4>Total Pendapatan</h4>
                <h3 style="color: var(--green);">Rp {{ number_format(($totalRevenue ?? 0)/1000, 0) }}k</h3>
            </div>
            <div class="circle-icon" style="background: var(--green);">💰</div>
        </div>

        {{-- Card 2: Pesanan Baru (DIPERBAIKI AGAR AMAN DARI ERROR) --}}
            <div class="stat-card">
            <div class="stat-info">
                <h4>Pesanan Baru</h4>
                {{-- Jika ada pesanan, warnanya oranye. Jika 0, hitam biasa --}}
                @if(($pendingOrders ?? 0) > 0)
                    <h3 style="color: var(--orange);">{{ $pendingOrders }}</h3>
                @else
                    <h3>0</h3>
                @endif
            </div>
            <div class="circle-icon" style="background: var(--orange);">🔔</div>
        </div>
        

        {{-- Card 3: Total Transaksi --}}
        <div class="stat-card">
            <div class="stat-info">
                <h4>Total Order</h4>
                <h3>{{ $totalOrders ?? 0 }}</h3>
            </div>
            <div class="circle-icon" style="background: var(--purple);">📦</div>
        </div>

        {{-- Card 4: Pelanggan --}}
        <div class="stat-card">
            <div class="stat-info">
                <h4>Pelanggan</h4>
                <h3>{{ $totalUsers ?? 0 }}</h3>
            </div>
            <div class="circle-icon" style="background: #3b82f6;">👥</div>
        </div>
    </div>

    {{-- KONTEN UTAMA (GRAFIK & TABEL) --}}
    <div class="main-grid">
        
        {{-- KIRI: GRAFIK PENJUALAN --}}
        <div>
            <div class="chart-card">
                <div class="section-title">
                    <span>Grafik Penjualan</span>
                    <select onchange="updateFilter(this.value)" class="filter-select">
                        <option value="weekly" {{ ($currentFilter ?? '') == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                        <option value="daily" {{ ($currentFilter ?? '') == 'daily' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="monthly" {{ ($currentFilter ?? '') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                    </select>
                </div>
                <div style="height: 300px;">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        {{-- KANAN: STATUS & AKTIVITAS TERBARU --}}
        <div>
            {{-- Grafik Donat --}}
            <div class="chart-card">
                <div class="section-title">Status Pesanan</div>
                <div style="height: 180px; display:flex; justify-content:center;">
                    <canvas id="orderStatusChart"></canvas>
                </div>
            </div>

            {{-- Daftar Pesanan Terbaru --}}
            <div class="chart-card">
                <div class="section-title">
                    <span>Pesanan Terbaru</span>
                    <a href="{{ route('orders.index') }}" style="font-size:0.8rem; font-weight:600; color:var(--purple); text-decoration:none;">Lihat Semua &rarr;</a>
                </div>
                
                @forelse($recentOrders ?? [] as $order)
                <div class="order-item">
                    <div>
                        <span class="order-user">{{ $order->user->name }}</span>
                        <span class="order-meta">#{{ $order->id }} • {{ $order->created_at->diffForHumans() }}</span>
                    </div>
                    <div>
                        @if($order->status == 'pending')
                            <span class="order-status" style="background:#fff7ed; color:#c2410c;">Menunggu</span>
                        @elseif($order->status == 'completed')
                            <span class="order-status" style="background:#f0fdf4; color:#15803d;">Selesai</span>
                        @else
                            <span class="order-status" style="background:#eff6ff; color:#1d4ed8;">Proses</span>
                        @endif
                    </div>
                </div>
                @empty
                    <div style="text-align:center; padding: 20px; color:#94a3b8; font-size:0.9rem;">
                        Belum ada pesanan masuk.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT CHART JS --}}
<script>
    function updateFilter(value) {
        const url = new URL(window.location.href);
        url.searchParams.set('filter', value);
        window.location.href = url.toString();
    }

    document.addEventListener("DOMContentLoaded", function() {
        // 1. CHART PENJUALAN
        var rawSalesData = <?php echo json_encode($salesChartData ?? []); ?>;
        const salesData = Array.isArray(rawSalesData) ? rawSalesData : [];
        
        const salesCtx = document.getElementById('salesChart');
        if (salesCtx) {
            new Chart(salesCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: salesData.map(i => i.label),
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: salesData.map(i => i.total),
                        backgroundColor: '#6B46C1',
                        borderRadius: 6,
                        barThickness: 25
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: {display:false} },
                    scales: { 
                        y: { beginAtZero: true, grid: { borderDash: [2, 4] } }, 
                        x: { grid: { display: false } } 
                    }
                }
            });
        }

        // 2. CHART STATUS
        var rawStatusData = <?php echo json_encode($orderStatusData ?? []); ?>;
        const statusData = rawStatusData || {};
        
        const statusCtx = document.getElementById('orderStatusChart');
        if (statusCtx) {
            new Chart(statusCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Selesai', 'Proses', 'Menunggu', 'Batal'],
                    datasets: [{
                        data: [
                            statusData.completed || 0, statusData.processing || 0, 
                            statusData.pending || 0, statusData.cancelled || 0
                        ],
                        backgroundColor: ['#10b981', '#3b82f6', '#f97316', '#ef4444'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: { 
                    cutout: '75%', 
                    plugins: { 
                        legend: { position: 'right', labels: { boxWidth: 12, usePointStyle: true, font: { size: 11 } } } 
                    } 
                }
            });
        }
    });
</script>

@endsection