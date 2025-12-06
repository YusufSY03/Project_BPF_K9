@extends('layouts.app')

@section('title', 'Menu Lengkap - Nyamaw')

@section('custom-css')
<style>
    /* HEADER HALAMAN */
    .page-header {
        text-align: center;
        padding-bottom: 40px;
        background-color: var(--light);
    }
    .page-title {
        font-family: var(--font-head);
        font-size: 3.5rem;
        color: var(--dark);
        margin-bottom: 10px;
        line-height: 1.2;
    }
    .page-subtitle {
        color: var(--gray);
        font-size: 1.1rem;
    }

    /* GRID MENU */
    .menu-section {
        padding: 60px 0 100px;
    }
    
    .menu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 30px;
    }

    /* KARTU MENU */
    .menu-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
        border: 1px solid #eee;
        display: flex;
        flex-direction: column;
    }
    .menu-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }

    .menu-img {
        width: 100%;
        height: 220px;
        object-fit: cover;
    }

    .menu-body {
        padding: 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .menu-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 10px;
    }

    .menu-name {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--dark);
        margin: 0;
        line-height: 1.3;
    }

    .menu-price {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary);
        white-space: nowrap;
        margin-left: 10px;
    }

    .menu-rating {
        font-size: 0.9rem;
        color: #f59e0b; /* Warna Emas */
        margin-bottom: 10px;
        font-weight: 600;
        display: flex; 
        align-items: center;
        gap: 5px;
    }

    .menu-desc {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 20px;
        flex-grow: 1;
    }

    /* AREA PESAN */
    .order-area {
        margin-top: auto;
    }

    .order-form {
        display: flex;
        gap: 10px;
    }

    .qty-input {
        width: 60px;
        padding: 8px;
        border: 2px solid #eee;
        border-radius: 8px;
        text-align: center;
        font-weight: bold;
        color: var(--dark);
    }

    .btn-pesan {
        flex: 1;
        background: var(--primary);
        color: white;
        border: none;
        padding: 10px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s;
    }
    .btn-pesan:hover {
        background: #e55337;
    }

    .btn-habis {
        width: 100%;
        background: #eee;
        color: #999;
        border: none;
        padding: 10px;
        border-radius: 8px;
        font-weight: 600;
        cursor: not-allowed;
    }

    .btn-login-msg {
        display: block;
        width: 100%;
        text-align: center;
        text-decoration: none;
        background: #f3f4f6;
        color: #666;
        padding: 10px;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 600;
    }
    
    .status-badge {
        margin-top: 15px;
        display: inline-block;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 4px;
    }
    .status-available { background: #dcfce7; color: #166534; }
    .status-soldout { background: #fee2e2; color: #991b1b; }

</style>
@endsection

@section('content')

    {{-- SPACER KHUSUS: MEMAKSA KONTEN TURUN DI BAWAH NAVBAR --}}
    <div style="height: 120px; width: 100%; background: var(--light);"></div>

    {{-- HEADER --}}
    <section class="page-header">
      <div class="container">
        <h1 class="page-title">Menu Lengkap</h1>
        <p class="page-subtitle">Semua hidangan spesial kami siap untuk Anda nikmati.</p>
      </div>
    </section>

    {{-- KONTEN MENU --}}
    <section class="menu-section">
      <div class="container">
        
        <div class="menu-grid">
          @forelse($menuItems as $item)
            <div class="menu-card">
              {{-- GAMBAR --}}
              <img src="{{ $item->image_url ? asset('storage/' . $item->image_url) : 'https://via.placeholder.com/300x220?text=No+Image' }}" 
                   class="menu-img" alt="{{ $item->name }}">
              
              <div class="menu-body">
                {{-- NAMA & HARGA --}}
                <div class="menu-header">
                  <h3 class="menu-name">{{ $item->name }}</h3>
                  <span class="menu-price">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                </div>

                {{-- RATING --}}
                <div class="menu-rating">
                    @php $rating = $item->getRating(); @endphp
                    @if($rating > 0)
                        <span>⭐ {{ $rating }}</span> 
                        <span style="color:#999; font-weight:normal; font-size:0.8rem;">({{ $item->getReviewCount() }} ulasan)</span>
                    @else
                        <span style="color:#999; font-weight:normal;">Belum ada ulasan</span>
                    @endif
                </div>
                
                {{-- DESKRIPSI --}}
                <p class="menu-desc">{{ Str::limit($item->description, 100) }}</p>
                
                {{-- TOMBOL AKSI --}}
                <div class="order-area">
                    @auth
                        @if($item->availability_status != 'sold_out')
                            <form action="{{ route('cart.add', $item->id) }}" method="POST" class="order-form">
                                @csrf
                                <input type="number" name="quantity" value="1" min="1" class="qty-input">
                                <button type="submit" class="btn-pesan">🛒 Pesan</button>
                            </form>
                        @else
                             <button class="btn-habis">Habis Terjual</button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn-login-msg">Login untuk Memesan</a>
                    @endauth

                    {{-- LABEL STATUS --}}
                    <div style="margin-top: 10px;">
                        @if($item->availability_status == 'sold_out')
                            <span class="status-badge status-soldout">Habis</span>
                        @else
                            <span class="status-badge status-available">Tersedia</span>
                        @endif
                        <span style="font-size:0.8rem; color:#888; margin-left:5px;">{{ $item->category }}</span>
                    </div>
                </div>

              </div>
            </div>
          @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 60px; color: #777;">
                <h3>Belum ada menu yang tersedia saat ini.</h3>
                <p>Silakan kembali lagi nanti!</p>
            </div>
          @endforelse
        </div>

      </div>
    </section>

@endsection