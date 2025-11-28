@extends('layouts.dashboard')

@section('title', 'Detail Pesanan #' . $order->id)

@section('content')

  <header class="header">
    <div>
      <h1>Detail Pesanan #{{ $order->id }}</h1>
      <p style="margin:0; color:var(--muted);">
        Pemesan: <strong>{{ $order->user->name }}</strong> | 
        Tanggal: {{ $order->created_at->format('d M Y, H:i') }}
      </p>
    </div>
    <a href="{{ route('orders.index') }}" class="btn" style="background:var(--muted);">← Kembali</a>
  </header>

  @if (session('status'))
    <div class="status-message">{{ session('status') }}</div>
  @endif

  <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
    
    <div style="display: flex; flex-direction: column; gap: 24px;">
        
        <div class="card">
            <h3>Rincian Pesanan</h3>
            <table class="user-table">
                <thead>
                    <tr><th>Menu</th><th>Harga</th><th>Qty</th><th>Subtotal</th></tr>
                </thead>
                <tbody>
                    @php $subtotal = 0; @endphp
                    @foreach($order->items as $item)
                    @php $subtotal += $item->price * $item->quantity; @endphp
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                @if($item->menu && $item->menu->image_url)
                                    <img src="{{ asset('storage/' . $item->menu->image_url) }}" style="width: 40px; height: 40px; border-radius: 4px; object-fit: cover;">
                                @endif
                                <span>{{ $item->menu->name ?? 'Menu dihapus' }}</span>
                            </div>
                        </td>
                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td>x {{ $item->quantity }}</td>
                        <td><strong>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</strong></td>
                    </tr>
                    @endforeach
                    <tr style="background: #fdfaf8;">
                        <td colspan="3" style="text-align: right; color: #666;">Subtotal Produk</td>
                        <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                    </tr>
                    <tr style="background: #fdfaf8;">
                        <td colspan="3" style="text-align: right; color: #666;">Biaya Pengiriman</td>
                        <td>Rp {{ number_format($order->shipping_price ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr style="background: #f0fdf4;">
                        <td colspan="3" style="text-align: right;"><strong>Total Tagihan</strong></td>
                        <td><strong style="color: var(--primary); font-size: 1.2rem;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if($order->latitude && $order->longitude)
        <div class="card">
            <h3>Info Pengiriman</h3>
            <div style="background: #f3f4f6; padding: 15px; border-radius: 8px; display: flex; align-items: center; justify-content: space-between;">
                <div><strong>Koordinat:</strong><br><span style="font-family: monospace; color: #555;">{{ $order->latitude }}, {{ $order->longitude }}</span></div>
                <a href="https://www.google.com/maps/search/?api=1&query={{ $order->latitude }},{{ $order->longitude }}" target="_blank" class="btn" style="background: #fff; border: 1px solid #ddd; color: #333; text-decoration: none;">📍 Buka Google Maps</a>
            </div>
        </div>
        @endif

    </div>

    <div class="card" style="height: fit-content;">
        <h3>Status & Pembayaran</h3>
        
        <div style="margin-bottom: 20px;">
            <label style="display:block; margin-bottom: 5px; color:var(--muted);">Status Saat Ini:</label>
            @if($order->status == 'pending') <span class="badge" style="background: #fef3c7; color: #92400e;">Menunggu Konfirmasi</span>
            @elseif($order->status == 'processing') <span class="badge" style="background: #dbeafe; color: #1e40af;">Sedang Diproses</span>
            @elseif($order->status == 'completed') <span class="badge" style="background: #dcfce7; color: #166534;">Selesai</span>
            @else <span class="badge" style="background: #fee2e2; color: #991b1b;">Dibatalkan</span> @endif
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display:block; margin-bottom: 5px; color:var(--muted);">Metode Pembayaran:</label>
            <strong style="font-size: 1.1rem;">{{ ucfirst($order->payment_method) }}</strong>
        </div>

        {{-- BUKTI PEMBAYARAN (ADMIN VIEW) --}}
        @if($order->payment_method == 'transfer')
            <div style="margin-bottom: 20px; padding: 10px; border: 1px solid #eee; border-radius: 8px;">
                <label style="display:block; margin-bottom: 5px; color:var(--muted);">Bukti Transfer:</label>
                @if($order->payment_proof)
                    <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank">
                        <img src="{{ asset('storage/' . $order->payment_proof) }}" style="width: 100%; border-radius: 6px; border: 1px solid #ddd;">
                    </a>
                    <small style="display:block; margin-top:5px; text-align:center;">Klik gambar untuk memperbesar</small>
                @else
                    <span style="color: #b91c1c;">Belum ada bukti pembayaran.</span>
                @endif
            </div>
        @endif

        <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

        <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST">
            @csrf @method('PATCH')
            <label style="display:block; margin-bottom: 8px; font-weight:600;">Ubah Status:</label>
            <select name="status" class="select" style="width: 100%; margin-bottom: 15px; padding: 10px;">
                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Sedang Diproses (Masak)</option>
                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai (Diantar/Ambil)</option>
                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Batalkan Pesanan</option>
            </select>
            <button type="submit" class="btn-success" style="width: 100%; border:none; padding: 12px; cursor: pointer; font-weight: bold;">Update Status</button>
        </form>
    </div>

  </div>

@endsection