@extends('layouts.dashboard')

@section('title', 'Daftar Pesanan')

@section('content')

  <header class="header">
    <div>
      <h1>Daftar Pesanan Masuk</h1>
      <p style="margin:0; color:var(--muted);">Pantau semua transaksi yang masuk.</p>
    </div>
  </header>

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
              @elseif($order->status == 'completed')
                <span class="badge" style="background: #dcfce7; color: #166534;">Selesai</span>
              @else
                <span class="badge">{{ ucfirst($order->status) }}</span>
              @endif
            </td>
            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="6" style="text-align: center; padding: 24px;">Belum ada pesanan masuk.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

@endsection