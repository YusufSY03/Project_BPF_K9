@extends('layouts.dashboard')

@section('title', 'Owner Dashboard')

@section('content')
{{-- Load Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    :root {
        --primary-orange: #FF6B00;
        --primary-purple: #6B46C1;
        --text-dark: #2D3748;
        --text-muted: #718096;
        --bg-body: #F7FAFC;
    }

    .lezato-dashboard {
        font-family: 'Poppins', sans-serif;
        color: var(--text-dark);
        padding-top: 10px;
    }

    /* HEADER */
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }
    .dashboard-header h2 { font-weight: 700; font-size: 24px; margin: 0; }

    /* GRID KARTU ATAS */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }
    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        border: 1px solid #f0f0f0;
    }
    .stat-info h4 {
        margin: 0;
        font-size: 13px;
        color: var(--text-muted);
        font-weight: 500;
    }
    .stat-info h3 {
        margin: 5px 0 0 0;
        font-size: 26px;
        font-weight: 700;
    }

    /* LINGKARAN PROGRESS */
    .circle-progress {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: conic-gradient(var(--color) var(--percent), #F0F0F0 0deg);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    .circle-progress::before {
        content: "";
        width: 48px;
        height: 48px;
        background: white;
        border-radius: 50%;
        position: absolute;
    }
    .circle-icon {
        position: relative;
        z-index: 1;
        font-size: 18px;
    }

    /* GRID MAIN CONTENT */
    .main-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 25px;
    }
    .chart-card {
        background: white;
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        margin-bottom: 25px;
        border: 1px solid #f0f0f0;
    }
    .section-title {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* List Item Styles */
    .list-item {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }
    .list-item:last-child { border-bottom: none; margin-bottom: 0; }
    .item-img {
        width: 50px; height: 50px; border-radius: 12px;
        background: #eee; margin-right: 15px; display:flex; 
        align-items:center; justify-content:center;
    }
    .item-info h5 { margin: 0; font-size: 15px; font-weight: 600; }
    .item-info p { margin: 2px 0 0 0; font-size: 12px; color: #718096; }
    .item-price { margin-left: auto; font-weight: 700; color: #FF6B00; }

    @media (max-width: 992px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .main-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 576px) {
        .stats-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="lezato-dashboard">
    
    {{-- HEADER --}}
    <div class="dashboard-header">
        <div>
            <h2>Analisa</h2>
            <p style="color: var(--text-muted); margin-top: 5px;">Analisa performa restoran.</p>
        </div>
        <button style="background: white; border: 1px solid #ddd; padding: 10px 20px; border-radius: 30px; font-weight: 600;">
            📅 {{ date('d M Y') }}
        </button>
    </div>

    {{-- KARTU STATISTIK --}}
    <div class="stats-grid">
        {{-- Card 1 --}}
        <div class="stat-card">
            <div class="stat-info">
                <h4>Menus Total</h4>
                <h3>{{ $menusTotal ?? 0 }}</h3>
            </div>
            <div class="circle-progress" style="--color: #FF6B00; --percent: 85%;">
                <span class="circle-icon">🍔</span>
            </div>
        </div>
        {{-- Card 2 --}}
        <div class="stat-card">
            <div class="stat-info">
                <h4>Customer Today</h4>
                <h3>{{ $customersToday ?? 0 }}</h3>
            </div>
            <div class="circle-progress" style="--color: #6B46C1; --percent: 60%;">
                <span class="circle-icon">👥</span>
            </div>
        </div>
        {{-- Card 3 --}}
        <div class="stat-card">
            <div class="stat-info">
                <h4>Total Revenue</h4>
                <h3 style="font-size: 22px;">{{ number_format(($totalRevenue ?? 0) / 1000, 0) }}k</h3> 
            </div>
            <div class="circle-progress" style="--color: #3CD856; --percent: 45%;">
                <span class="circle-icon">💰</span>
            </div>
        </div>
        {{-- Card 4 --}}
        <div class="stat-card">
            <div class="stat-info">
                <h4>Employees</h4>
                <h3>{{ $totalEmployees ?? 0 }}</h3>
            </div>
            <div class="circle-progress" style="--color: #F6AD55; --percent: 90%;">
                <span class="circle-icon">👔</span>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="main-grid">
        {{-- KOLOM KIRI --}}
        <div class="left-column">
            {{-- Sales Chart --}}
            <div class="chart-card">
                <div class="section-title">
                    <span>Statistik Penjualan</span>
                    
                    {{-- DROPDOWN FILTER YANG BERFUNGSI --}}
                    <select id="salesFilter" onchange="updateFilter(this.value)" 
                            style="border: none; background: #f1f1f1; padding: 8px 12px; border-radius: 8px; font-size: 12px; cursor: pointer; outline: none;">
                        <option value="daily" {{ ($currentFilter ?? '') == 'daily' ? 'selected' : '' }}>Harian (Hari Ini)</option>
                        <option value="weekly" {{ ($currentFilter ?? '') == 'weekly' ? 'selected' : '' }}>Mingguan (7 Hari)</option>
                        <option value="monthly" {{ ($currentFilter ?? '') == 'monthly' ? 'selected' : '' }}>Bulanan (Tahun Ini)</option>
                    </select>
                </div>
                <div style="height: 300px;">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="section-title">Sales Summary</div>
                <p style="color: #888; font-size: 14px;">
                    @if(($currentFilter ?? 'weekly') == 'daily')
                        Menampilkan penjualan per jam untuk hari ini.
                    @elseif(($currentFilter ?? 'weekly') == 'monthly')
                        Menampilkan tren penjualan bulanan tahun ini.
                    @else
                        Menampilkan performa 7 hari terakhir.
                    @endif
                </p>
            </div>
        </div>

        {{-- KOLOM KANAN --}}
        <div class="right-column">
            {{-- Order Status Chart --}}
            <div class="chart-card">
                <div class="section-title">Order Status</div>
                <div style="height: 200px; display:flex; justify-content:center;">
                    <canvas id="orderStatusChart"></canvas>
                </div>
            </div>

            {{-- Best Seller Menus --}}
            <div class="chart-card">
                <div class="section-title">Best Seller Menus</div>
                @forelse($bestSellers ?? [] as $menu)
                <div class="list-item">
                    <div class="item-img" style="font-size: 20px;">🥘</div>
                    <div class="item-info">
                        <h5>{{ $menu->name }}</h5>
                        <p>Favorit</p>
                    </div>
                    <div class="item-price">Rp {{ number_format($menu->price, 0, ',', '.') }}</div>
                </div>
                @empty
                <p style="color:#999; font-size:12px;">Belum ada data menu.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT JAVASCRIPT --}}
<script>
    // Fungsi untuk reload halaman saat filter diganti
    function updateFilter(value) {
        // Ambil URL saat ini
        const url = new URL(window.location.href);
        // Set parameter ?filter=...
        url.searchParams.set('filter', value);
        // Reload halaman ke URL baru
        window.location.href = url.toString();
    }

    document.addEventListener("DOMContentLoaded", function() {
        // --- 1. DATA SALES (GRAFIK BATANG) ---
        var rawSalesData = <?php echo json_encode($salesChartData ?? []); ?>;
        // Pastikan format array object {label, total}
        const salesData = Array.isArray(rawSalesData) ? rawSalesData : [];

        // Ambil label dan total dari data yang sudah diformat di Controller
        const labels = salesData.map(item => item.label);
        const totals = salesData.map(item => item.total);

        const ctxSalesElement = document.getElementById('salesChart');
        if (ctxSalesElement) {
            new Chart(ctxSalesElement.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Revenue',
                        data: totals,
                        backgroundColor: '#FF6B00',
                        borderRadius: 5,
                        barThickness: 20
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: {display:false} },
                    scales: {
                        y: { beginAtZero: true, grid: {borderDash:[5,5]} },
                        x: { grid: {display:false} }
                    }
                }
            });
        }

        // --- 2. DATA STATUS ORDER (GRAFIK DONAT) ---
        var rawStatusData = <?php echo json_encode($orderStatusData ?? []); ?>;
        const statusData = rawStatusData || {};

        const ctxStatusElement = document.getElementById('orderStatusChart');
        if (ctxStatusElement) {
            new Chart(ctxStatusElement.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Selesai', 'Proses', 'Menunggu', 'Batal'], 
                    datasets: [{
                        data: [
                            statusData.completed || 0, 
                            statusData.processing || 0, 
                            statusData.pending || 0, 
                            statusData.cancelled || 0
                        ],
                        backgroundColor: ['#3CD856', '#6B46C1', '#F6AD55', '#FF5252'],
                        borderWidth: 0
                    }]
                },
                options: {
                    cutout: '70%', 
                    plugins: { 
                        legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } },
                        tooltip: { callbacks: { label: function(c) { return ' ' + c.label + ': ' + c.raw; } } }
                    }
                }
            });
        }
    });
</script>

@endsection