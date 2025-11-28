@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')

  <header class="header">
    <div>
      <h1>Dashboard</h1>
      <p style="margin:0; color:var(--muted);">
        Selamat datang kembali, {{ Auth::user()->name }}!
        <br>Berikut adalah ringkasan performa Nyamaw hari ini.
      </p>
    </div>
  </header>

  {{-- KARTU STATISTIK --}}
  <div class="card-grid">
    
    {{-- Kartu 1: Total Pendapatan --}}
    <div class="card">
      <h3 class="card-title">Total Pendapatan</h3>
      <p class="card-value" style="color: var(--success);">
        Rp {{ number_format($totalRevenue, 0, ',', '.') }}
      </p>
      <div class="card-change positive">
        <small>Dari pesanan selesai</small>
      </div>
    </div>

    {{-- Kartu 2: Pesanan Baru --}}
    <div class="card">
      <h3 class="card-title">Pesanan Baru (Pending)</h3>
      
      {{-- PERBAIKAN: Kita pisahkan logikanya agar tidak merah/error --}}
      @if($pendingOrders > 0)
          <p class="card-value" style="color: var(--danger);">
              {{ $pendingOrders }}
          </p>
      @else
          <p class="card-value" style="color: var(--text);">
              {{ $pendingOrders }}
          </p>
      @endif

      <div class="card-change">
        <small>Perlu diproses segera</small>
      </div>
    </div>

    {{-- Kartu 3: Total Order --}}
    <div class="card">
      <h3 class="card-title">Total Transaksi</h3>
      <p class="card-value">{{ number_format($totalOrders) }}</p>
      <div class="card-change">
        <small>Semua riwayat pesanan</small>
      </div>
    </div>

    {{-- Kartu 4: Total User --}}
    <div class="card">
      <h3 class="card-title">Total Pelanggan</h3>
      <p class="card-value">{{ number_format($totalUsers) }}</p>
      <div class="card-change">
        <small>User terdaftar</small>
      </div>
    </div>
  </div>

  {{-- TABEL AKTIVITAS TERBARU --}}
  <div class="card" style="margin-top: 24px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h3 style="margin:0;">Pesanan Terbaru</h3>
        <a href="{{ route('orders.index') }}" class="btn" style="background: var(--bg); color: var(--text); font-size: 0.9rem;">Lihat Semua &rarr;</a>
      </div>
      
      <table class="user-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Pelanggan</th>
                <th>Total</th>
                <th>Status</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentOrders as $order)
            <tr>
                <td>#{{ $order->id }}</td>
                <td>{{ $order->user->name }}</td>
                <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                <td>
                    @if($order->status == 'pending')
                        <span class="badge" style="background: #fef3c7; color: #92400e;">Menunggu</span>
                    @elseif($order->status == 'processing')
                        <span class="badge" style="background: #dbeafe; color: #1e40af;">Diproses</span>
                    @elseif($order->status == 'completed')
                        <span class="badge" style="background: #dcfce7; color: #166534;">Selesai</span>
                    @else
                        <span class="badge" style="background: #fee2e2; color: #991b1b;">Batal</span>
                    @endif
                </td>
                <td style="font-size: 0.9rem; color: #666;">
                    {{ $order->created_at->diffForHumans() }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 20px; color: #999;">
                    Belum ada aktivitas pesanan.
                </td>
            </tr>
            @endforelse
        </tbody>
      </table>
  </div>

@endsection