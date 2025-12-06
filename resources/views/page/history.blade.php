@extends('layouts.app')

@section('title', 'Riwayat Pesanan - Nyamaw')

@section('custom-css')
<style>
    /* PAGE HEADER */
    .page-header {
        text-align: center;
        padding-bottom: 40px;
        background-color: var(--light);
    }
    .page-title {
        font-family: var(--font-head);
        font-size: 3rem;
        color: var(--dark);
        margin-bottom: 10px;
        line-height: 1.2;
    }
    .page-subtitle { color: var(--gray); font-size: 1.1rem; }

    /* GRID SYSTEM */
    .history-section {
        padding: 60px 20px 100px;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .orders-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(450px, 1fr)); 
        gap: 30px;
    }

    /* KARTU ORDER */
    .order-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        border: 1px solid #eee;
        transition: transform 0.3s ease;
        display: flex;
        flex-direction: column;
        position: relative;
    }
    
    /* Garis Aksen Kiri */
    .order-card::before {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 6px;
        background: var(--primary);
    }

    .order-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.08); }

    /* HEADER KARTU */
    .card-header {
        padding: 20px 25px;
        border-bottom: 2px dashed #f0f0f0;
        display: flex; justify-content: space-between; align-items: center;
        background: #fffcfb;
    }
    .order-id { font-family: var(--font-head); font-size: 1.4rem; font-weight: 800; color: var(--dark); }
    .order-date { font-size: 0.85rem; color: #999; font-weight: 500; }

    /* BADGE STATUS */
    .status-badge { padding: 6px 12px; border-radius: 8px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; }
    .status-pending { background: #fff7ed; color: #ea580c; border: 1px solid #fed7aa; }
    .status-processing { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
    .status-completed { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
    .status-cancelled { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }

    /* ISI KARTU */
    .card-body { padding: 25px; flex-grow: 1; }
    
    .item-row { display: flex; align-items: center; gap: 15px; margin-bottom: 15px; }
    .item-img { width: 60px; height: 60px; border-radius: 10px; object-fit: cover; border: 1px solid #eee; }
    .item-details { flex: 1; }
    .item-name { font-weight: 700; font-size: 1rem; color: var(--dark); display: block; }
    .item-meta { font-size: 0.85rem; color: #777; }

    /* FOOTER KARTU */
    .card-footer { padding: 20px 25px; background: #fcfcfc; border-top: 1px solid #eee; }
    .total-section { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 20px; }
    .label-total { font-size: 0.9rem; color: #777; text-transform: uppercase; }
    .price-total { font-family: var(--font-head); font-size: 1.8rem; font-weight: 900; color: var(--dark); line-height: 1; }

    /* TOMBOL AKSI */
    .action-box { background: white; border: 1px solid #e5e5e5; padding: 15px; border-radius: 10px; }
    .btn-action { display: block; width: 100%; text-align: center; padding: 12px; border-radius: 8px; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: 0.2s; }
    .btn-upload { background: var(--dark); color: white; }
    .btn-review { background: #f59e0b; color: white; }
    
    @media (max-width: 600px) { .orders-grid { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')

    {{-- SPACER KHUSUS: Agar judul turun ke bawah --}}
    <div style="height: 120px; width: 100%; background: var(--light);"></div>

    <section class="page-header">
      <div class="container">
        <h1 class="page-title">Riwayat Pesanan</h1>
        <p class="page-subtitle">Pantau status pesananmu di sini.</p>
      </div>
    </section>

    <section class="history-section">
        <div class="container">
            @if(session('status'))
                <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 30px; font-weight: 600; text-align: center;">
                    {{ session('status') }}
                </div>
            @endif

            <div class="orders-grid">
                @forelse($orders as $order)
                <div class="order-card">
                    
                    {{-- HEADER --}}
                    <div class="card-header">
                        <div>
                            <div class="order-id">#{{ $order->id }}</div>
                            <div class="order-date">{{ $order->created_at->format('d M Y • H:i') }}</div>
                        </div>
                        <div>
                            @if($order->status == 'pending') <span class="status-badge status-pending">Menunggu</span>
                            @elseif($order->status == 'processing') <span class="status-badge status-processing">Dimasak</span>
                            @elseif($order->status == 'completed') <span class="status-badge status-completed">Selesai</span>
                            @else <span class="status-badge status-cancelled">Batal</span> @endif
                        </div>
                    </div>

                    {{-- BODY --}}
                    <div class="card-body">
                        @foreach($order->items as $item)
                        <div class="item-row">
                            <img src="{{ $item->menu && $item->menu->image_url ? asset('storage/' . $item->menu->image_url) : 'https://via.placeholder.com/60' }}" class="item-img" alt="">
                            <div class="item-details">
                                <span class="item-name">{{ $item->menu->name ?? 'Menu dihapus' }}</span>
                                <div class="item-meta">{{ $item->quantity }}x Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- FOOTER --}}
                    <div class="card-footer">
                        <div class="total-section">
                            <div>
                                <div class="label-total">Metode</div>
                                <strong style="color:var(--dark);">{{ ucfirst($order->payment_method) }}</strong>
                            </div>
                            <div style="text-align: right;">
                                <div class="label-total">Total Bayar</div>
                                <div class="price-total">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                            </div>
                        </div>

                        {{-- AKSI --}}
                        @if($order->payment_method == 'transfer')
                            <div class="action-box">
                                <div style="display:flex; justify-content:space-between; margin-bottom:10px;">
                                    <span style="font-weight:700; font-size:0.9rem;">Bukti Transfer</span>
                                    @if($order->payment_proof) <span style="color:#16a34a; font-weight:700; font-size:0.8rem;">✓ Terupload</span>
                                    @else <span style="color:#dc2626; font-weight:700; font-size:0.8rem;">! Belum Upload</span> @endif
                                </div>

                                @if($order->status == 'pending')
                                    <form action="{{ route('orders.uploadProof', $order->id) }}" method="POST" enctype="multipart/form-data" style="display: flex; gap: 5px;">
                                        @csrf
                                        <input type="file" name="payment_proof" required accept="image/*" style="width: 100%; font-size:0.9rem;">
                                        <button type="submit" class="btn-action btn-upload" style="width: auto; padding: 8px 15px;">Kirim</button>
                                    </form>
                                @elseif($order->payment_proof)
                                    <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" style="font-size:0.9rem; text-decoration:underline; color:var(--primary);">Lihat Gambar</a>
                                @endif
                            </div>
                        @endif

                        @if($order->status == 'completed')
                            <div style="margin-top: 15px;">
                                @if($order->review)
                                    <div class="btn-action" style="background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; cursor:default;">★ {{ $order->review->rating_stars }} Bintang</div>
                                @else
                                    <a href="{{ route('reviews.create', $order->id) }}" class="btn-action btn-review">⭐ Beri Ulasan</a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
                @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 100px 20px;">
                    <h2 style="color: #ccc;">Belum ada pesanan.</h2>
                    <a href="{{ route('menu') }}" class="btn-main" style="background:var(--primary); color:white; padding:12px 25px; border-radius:50px; text-decoration:none;">LIHAT MENU</a>
                </div>
                @endforelse
            </div>
        </div>
    </section>

@endsection