@extends('layouts.app')

@section('title', 'Keranjang Belanja - Nyamaw')

@section('custom-css')
<style>
    /* HEADER */
    .page-header {
        text-align: center;
        padding-top: 140px !important;
        padding-bottom: 40px;
        background-color: var(--light);
    }
    .page-title {
        font-family: var(--font-head);
        font-size: 3rem;
        color: var(--dark);
        margin-bottom: 10px;
        line-height: 1;
    }

    /* CART LAYOUT */
    .cart-section {
        padding: 60px 0 100px;
    }
    .cart-container {
        display: grid;
        grid-template-columns: 2fr 1fr; /* Kiri: Tabel, Kanan: Ringkasan */
        gap: 40px;
    }

    /* TABEL ITEM */
    .cart-box {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        border: 1px solid #eee;
        overflow: hidden;
    }
    
    .cart-table {
        width: 100%;
        border-collapse: collapse;
    }
    .cart-table th {
        background: #f9fafb;
        padding: 15px 20px;
        text-align: left;
        font-size: 0.9rem;
        color: var(--gray);
        font-weight: 600;
        text-transform: uppercase;
    }
    .cart-table td {
        padding: 20px;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }
    .cart-table tr:last-child td { border-bottom: none; }

    /* ITEM INFO */
    .item-flex {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .item-img {
        width: 70px; height: 70px;
        border-radius: 10px;
        object-fit: cover;
        border: 1px solid #eee;
    }
    .item-name {
        font-weight: 700;
        color: var(--dark);
        font-size: 1rem;
        display: block;
        margin-bottom: 5px;
    }
    .item-price-mobile { display: none; font-size: 0.9rem; color: var(--primary); }

    /* SUMMARY BOX (KANAN) */
    .summary-box {
        background: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border: 1px solid #eee;
        position: sticky;
        top: 100px; /* Agar ikut scroll */
    }
    .summary-title {
        font-family: var(--font-head);
        font-size: 1.5rem;
        margin-bottom: 20px;
        border-bottom: 2px dashed #eee;
        padding-bottom: 15px;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        font-size: 1rem;
        color: var(--dark);
    }
    .summary-total {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid #eee;
        display: flex;
        justify-content: space-between;
        font-weight: 800;
        font-size: 1.3rem;
        color: var(--primary);
    }

    /* TOMBOL */
    .btn-checkout {
        display: block;
        width: 100%;
        background: var(--dark);
        color: white;
        text-align: center;
        padding: 15px;
        border-radius: 8px;
        font-weight: 700;
        text-transform: uppercase;
        margin-top: 25px;
        text-decoration: none;
        transition: 0.3s;
    }
    .btn-checkout:hover { background: var(--primary); transform: translateY(-3px); }

    .btn-remove {
        color: #ef4444;
        background: #fee2e2;
        border: none;
        padding: 8px 12px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.8rem;
        font-weight: 600;
        transition: 0.2s;
    }
    .btn-remove:hover { background: #dc2626; color: white; }

    /* KOSONG */
    .empty-cart {
        text-align: center;
        padding: 80px 20px;
    }

    /* RESPONSIVE */
    @media (max-width: 900px) {
        .cart-container { grid-template-columns: 1fr; } /* 1 Kolom */
        .summary-box { position: static; }
    }
    @media (max-width: 600px) {
        .hide-mobile { display: none; } /* Sembunyikan Header Tabel */
        .item-price-desktop { display: none; }
        .item-price-mobile { display: block; }
        .cart-table td { display: block; text-align: right; padding: 10px 20px; }
        .cart-table td:first-child { text-align: left; border-bottom: none; }
        .item-flex { flex-direction: row; align-items: flex-start; }
    }
</style>
@endsection

@section('content')

    {{-- SPACER --}}
    <div style="height: 120px; width: 100%; background: var(--light);"></div>

    <section class="page-header">
      <div class="container">
        <h1 class="page-title">Keranjang Belanja</h1>
        <p style="color:var(--gray);">Periksa kembali pesanan Anda sebelum checkout.</p>
      </div>
    </section>

    <section class="cart-section container">
        
        {{-- Pesan Sukses Hapus --}}
        @if(session('status'))
            <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 30px; font-weight: 600;">
                {{ session('status') }}
            </div>
        @endif

        @if(session('cart') && count(session('cart')) > 0)
            <div class="cart-container">
                
                {{-- KOLOM KIRI: TABEL --}}
                <div class="cart-box">
                    <table class="cart-table">
                        <thead class="hide-mobile">
                            <tr>
                                <th>Menu</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @foreach(session('cart') as $id => $details)
                                @php $total += $details['price'] * $details['quantity'] @endphp
                                <tr>
                                    <td>
                                        <div class="item-flex">
                                            <img src="{{ $details['image'] ? asset('storage/' . $details['image']) : 'https://via.placeholder.com/70' }}" 
                                                 class="item-img" alt="{{ $details['name'] }}">
                                            <div>
                                                <span class="item-name">{{ $details['name'] }}</span>
                                                <span class="item-price-mobile">Rp {{ number_format($details['price'], 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="item-price-desktop">Rp {{ number_format($details['price'], 0, ',', '.') }}</td>
                                    <td>
                                        <span style="background: #f3f4f6; padding: 5px 12px; border-radius: 50px; font-weight: 600;">
                                            x{{ $details['quantity'] }}
                                        </span>
                                    </td>
                                    <td style="font-weight: 700; color: var(--dark);">
                                        Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <form action="{{ route('cart.remove') }}" method="POST">
                                            @csrf @method('DELETE')
                                            <input type="hidden" name="id" value="{{ $id }}">
                                            <button type="submit" class="btn-remove">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- KOLOM KANAN: RINGKASAN --}}
                <div>
                    <div class="summary-box">
                        <h3 class="summary-title">Ringkasan</h3>
                        
                        <div class="summary-row">
                            <span>Total Item</span>
                            <span>{{ count(session('cart')) }} Menu</span>
                        </div>
                        <div class="summary-row" style="color: #888; font-size: 0.9rem;">
                            <span>Pengiriman</span>
                            <span>Dihitung saat Checkout</span>
                        </div>

                        <div class="summary-total">
                            <span>Total</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>

                        <a href="{{ route('checkout') }}" class="btn-checkout">
                            Lanjut Checkout &rarr;
                        </a>
                        
                        <a href="{{ route('menu') }}" style="display:block; text-align:center; margin-top:15px; color:var(--gray); text-decoration:underline; font-size:0.9rem;">
                            Tambah Menu Lain
                        </a>
                    </div>
                </div>

            </div>
        @else
            {{-- JIKA KOSONG --}}
            <div class="empty-cart">
                <img src="https://cdn-icons-png.flaticon.com/512/11329/11329060.png" style="width: 150px; opacity: 0.5; margin-bottom: 20px;">
                <h2 style="font-family: var(--font-head); color: var(--dark);">Keranjang Anda Kosong</h2>
                <p style="color: var(--gray); margin-bottom: 30px;">Sepertinya Anda belum memesan makanan lezat hari ini.</p>
                <a href="{{ route('menu') }}" class="btn-main">LIHAT MENU</a>
            </div>
        @endif

    </section>

@endsection