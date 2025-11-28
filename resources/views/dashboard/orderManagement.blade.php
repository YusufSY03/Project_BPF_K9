@extends('layouts.dashboard')

@section('title', 'Daftar Pesanan')

@section('content')

  <header class="header">
    <div>
      <h1>Daftar Pesanan Masuk</h1>
      <p style="margin:0; color:var(--muted);">Pantau semua transaksi yang masuk.</p>
    </div>
  </header>

  {{-- FORM SEARCH & FILTER --}}
  <div class="card" style="margin-bottom: 24px; padding: 0;">
    <form action="{{ route('orders.index') }}" method="GET" class="search-filter-container">
        
        <div style="flex: 1; min-width: 250px;">
            <input type="text" name="search" class="input" 
                   placeholder="🔍 Cari ID Pesanan atau Nama Pelanggan..." 
                   value="{{ request('search') }}">
        </div>

        <div style="width: 200px;">
            <select name="status" class="select">
                <option value="">-- Semua Status --</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Sedang Diproses</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
        </div>

        <button type="submit" class="btn-search">Cari</button>
        
        @if(request('search') || request('status'))
            <a href="{{ route('orders.index') }}" class="btn-reset">Reset</a>
        @endif
    </form>
  </div>

  {{-- TABEL DATA --}}
  <div class="card">
    <table class="user-table">
      <thead>
        <tr>
          <th>ID Order</th>
          <th>Nama Pemesan</th>
          <th>Total Bayar</th>
          <th>Metode</th>
          <th>Status</th>
          <th>Tanggal</th>
          <th>Aksi</th> {{-- KOLOM INI YANG KEMARIN HILANG --}}
        </tr>
      </thead>
      <tbody>
        @forelse ($orders as $order)
          <tr>
            <td>#{{ $order->id }}</td>
            <td>{{ $order->user->name }}</td>
            <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
            <td>{{ ucfirst($order->payment_method) }}</td>
            <td>
              @if($order->status == 'pending')
                <span class="badge" style="background: #fef3c7; color: #92400e;">Menunggu Konfirmasi</span>
              @elseif($order->status == 'processing')
                <span class="badge" style="background: #dbeafe; color: #1e40af;">Sedang Diproses</span>
              @elseif($order->status == 'completed')
                <span class="badge" style="background: #dcfce7; color: #166534;">Selesai</span>
              @else
                <span class="badge" style="background: #fee2e2; color: #991b1b;">Dibatalkan</span>
              @endif
            </td>
            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
            <td>
                {{-- TOMBOL INI YANG AKAN MEMBUKA HALAMAN DETAIL --}}
                <a href="{{ route('orders.show', $order->id) }}" class="btn-edit" style="text-decoration:none; font-size: 0.9rem;">
                    Lihat Detail
                </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" style="text-align: center; padding: 40px;">
                <p style="color: var(--muted);">Tidak ada pesanan yang ditemukan.</p>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>

    {{-- Pagination --}}
    <div class="custom-pagination" style="margin-top: 20px; display: flex; justify-content: center;">
        {{ $orders->links() }} 
    </div>
  </div>

  <style>
      .search-filter-container { display: flex; gap: 16px; flex-wrap: wrap; align-items: center; padding: 16px; }
      .search-filter-container .input, .search-filter-container .select { margin-bottom: 0; height: 42px; }
      .btn-search { background: var(--primary); color: white; border: none; border-radius: 8px; padding: 0 20px; height: 42px; font-weight: 600; cursor: pointer; }
      .btn-reset { background: #e5e7eb; color: var(--text); text-decoration: none; border-radius: 8px; padding: 0 20px; height: 42px; font-weight: 600; display: inline-flex; align-items: center; }
      
      .custom-pagination nav svg { height: 20px; width: 20px; }
      .custom-pagination nav .flex { display: flex; align-items: center; gap: 10px; }
      .custom-pagination nav span, .custom-pagination nav a { padding: 8px 12px; border: 1px solid #eee; border-radius: 4px; text-decoration: none; color: var(--text-color); }
      .custom-pagination nav span[aria-current="page"] { background-color: var(--primary); color: white; border-color: var(--primary); }
  </style>

@endsection