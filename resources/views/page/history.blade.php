<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Riwayat Pesanan - Nyamaw</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
  <style>
    :root { --primary-color: #FF6347; --secondary-color: #333; --bg-color: #fdfaf8; --text-color: #4a4a4a; --font-primary: 'Poppins', sans-serif; --font-secondary: 'Playfair Display', serif; }
    body { margin: 0; font-family: var(--font-primary); background: var(--bg-color); color: var(--text-color); }
    .container { max-width: 1100px; margin: 0 auto; padding: 0 24px; }
    
    .navbar { background: rgba(255,255,255,0.8); backdrop-filter: blur(10px); padding: 16px 0; border-bottom: 1px solid #eee; position: sticky; top: 0; z-index: 100; }
    .navbar .container { display: flex; justify-content: space-between; align-items: center; }
    .navbar-brand { font-family: var(--font-secondary); font-size: 1.8rem; text-decoration: none; color: var(--primary-color); }
    .navbar-nav { list-style: none; margin: 0; padding: 0; display: flex; gap: 24px; }
    .nav-link { text-decoration: none; color: var(--text-color); font-weight: 600; }
    .nav-link:hover { color: var(--primary-color); }
    .btn-primary-outline { background: transparent; color: var(--primary-color); border: 2px solid var(--primary-color); padding: 8px 16px; text-decoration: none; border-radius: 50px; font-weight: 600; }
    .navbar-auth { display: flex; align-items: center; gap: 16px; }

    .history-page { padding: 60px 0; }
    .page-title { margin-bottom: 30px; color: var(--secondary-color); font-family: var(--font-secondary); }
    
    .order-card { background: #fff; border-radius: 12px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid #eee; }
    .order-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid #f3f4f6; padding-bottom: 16px; }
    .order-id { font-weight: 700; color: var(--secondary-color); font-size: 1.1rem; }
    .order-date { font-size: 0.9rem; color: #888; }
    
    .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; }
    .status-pending { background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; }
    .status-processing { background: #eff6ff; color: #1d4ed8; border: 1px solid #dbeafe; }
    .status-completed { background: #f0fdf4; color: #15803d; border: 1px solid #dcfce7; }
    .status-cancelled { background: #fef2f2; color: #b91c1c; border: 1px solid #fee2e2; }

    .order-items { display: flex; flex-direction: column; gap: 12px; }
    .item-row { display: flex; justify-content: space-between; align-items: center; font-size: 0.95rem; }
    .item-name { display: flex; align-items: center; gap: 10px; }
    .item-name img { width: 40px; height: 40px; border-radius: 6px; object-fit: cover; }
    
    .order-footer { margin-top: 20px; padding-top: 20px; border-top: 1px solid #f3f4f6; }
    .payment-section { margin-top: 15px; padding: 15px; background: #f9fafb; border-radius: 8px; font-size: 0.9rem; }
    
    /* Tombol Upload */
    .file-input { margin-bottom: 10px; width: 100%; }
    .btn-upload { background: var(--primary-color); color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-size: 0.9rem; }
  </style>
</head>
<body>

  <nav class="navbar">
    <div class="container">
      <a href="{{ route('home') }}" class="navbar-brand">Nyamaw 🐾</a>
      <ul class="navbar-nav">
        <li><a href="{{ route('home') }}" class="nav-link">Home</a></li>
        <li><a href="{{ route('menu') }}" class="nav-link">Menu</a></li>
        <li><a href="{{ route('orders.history') }}" class="nav-link" style="color:var(--primary-color);">Riwayat Pesanan</a></li>
      </ul>
      <div class="navbar-auth">
        <a href="{{ route('cart') }}" class="btn-primary-outline">
            🛒 {{ count((array) session('cart')) }}
        </a>
        <span style="font-weight:600;">{{ Auth::user()->name }}</span>
      </div>
    </div>
  </nav>

  <section class="history-page">
    <div class="container">
      <h2 class="page-title">Riwayat Pesanan Saya</h2>

      @if(session('status'))
        <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 24px;">{{ session('status') }}</div>
      @endif

      @forelse($orders as $order)
        <div class="order-card">
            <div class="order-header">
                <div>
                    <div class="order-id">Order #{{ $order->id }}</div>
                    <div class="order-date">{{ $order->created_at->format('d M Y, H:i') }} WIB</div>
                </div>
                <div>
                    @if($order->status == 'pending')
                        <span class="status-badge status-pending">Menunggu Konfirmasi</span>
                    @elseif($order->status == 'processing')
                        <span class="status-badge status-processing">Sedang Dimasak</span>
                    @elseif($order->status == 'completed')
                        <span class="status-badge status-completed">Selesai</span>
                    @else
                        <span class="status-badge status-cancelled">Dibatalkan</span>
                    @endif
                </div>
            </div>

            <div class="order-items">
                @foreach($order->items as $item)
                    <div class="item-row">
                        <div class="item-name">
                            <img src="{{ $item->menu && $item->menu->image_url ? asset('storage/' . $item->menu->image_url) : 'https://via.placeholder.com/40' }}" alt="">
                            <span>{{ $item->menu->name ?? 'Menu dihapus' }} <strong style="color:#888;">x{{ $item->quantity }}</strong></span>
                        </div>
                        <div>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</div>
                    </div>
                @endforeach
            </div>

            <div class="order-footer">
                <div style="display:flex; justify-content:space-between; font-weight:bold;">
                    <div>Total Bayar</div>
                    <div style="color: var(--primary-color);">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                </div>

                {{-- FITUR UPLOAD BUKTI BAYAR --}}
                @if($order->payment_method == 'transfer')
                    <div class="payment-section">
                        <p style="margin-top:0; font-weight:bold;">Pembayaran Transfer</p>
                        
                        @if($order->payment_proof)
                            <div style="color: #166534; margin-bottom: 10px;">✅ Bukti pembayaran sudah diupload.</div>
                            <img src="{{ asset('storage/' . $order->payment_proof) }}" style="height: 100px; border-radius: 6px; border: 1px solid #ddd;">
                        @else
                            <p style="color:#c2410c;">⚠️ Silakan upload bukti transfer agar pesanan diproses.</p>
                        @endif

                        {{-- Form Upload (Muncul jika Pending dan Transfer) --}}
                        @if($order->status == 'pending')
                            <form action="{{ route('orders.uploadProof', $order->id) }}" method="POST" enctype="multipart/form-data" style="margin-top: 10px;">
                                @csrf
                                <input type="file" name="payment_proof" class="file-input" required accept="image/*">
                                <button type="submit" class="btn-upload">Upload Bukti</button>
                            </form>
                        @endif
                    </div>
                @else
                    <div class="payment-section">
                        Metode: <strong>Bayar Tunai (Cash)</strong>
                    </div>
                @endif
                {{-- TOMBOL REVIEW --}}
                @if($order->status == 'completed')
                    <div style="margin-top: 15px; text-align: right;">
                        @if($order->review)
                            <span style="color: #166534; font-weight: 600;">
                                ★ Anda memberi {{ $order->review->rating_stars }} Bintang
                            </span>
                        @else
                            <a href="{{ route('reviews.create', $order->id) }}" style="background: #f59e0b; color: white; text-decoration: none; padding: 8px 16px; border-radius: 6px; font-weight: 600; font-size: 0.9rem;">
                                ⭐ Beri Ulasan
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
      @empty
        <div style="text-align: center; padding: 60px; background: #fff; border-radius: 12px; border: 1px solid #eee;">
            <h3>Belum ada riwayat pesanan.</h3>
            <a href="{{ route('menu') }}" class="btn-primary-outline" style="background:var(--primary-color); color:white; margin-top:16px; display:inline-block;">Pesan Sekarang</a>
        </div>
      @endforelse
    </div>
  </section>
</body>
</html>