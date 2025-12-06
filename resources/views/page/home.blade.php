@extends('layouts.app')

@section('title', 'Nyamaw - Taste of Home')

@section('custom-css')
<style>
    /* === 1. PERBAIKAN NAVBAR (KHUSUS HALAMAN HOME) === */
    /* Kita timpa style navbar default agar transparan di Home */
    .navbar {
        background: transparent !important; /* Transparan agar menyatu dengan Hero */
        box-shadow: none !important;
        position: absolute; /* Absolute agar menumpuk di atas Hero */
        top: 0; left: 0; width: 100%;
        border-bottom: none !important;
        padding-top: 30px; /* Jarak dari atas lebih lega */
    }
    
    /* Saat discroll, navbar akan kembali putih (diatur oleh JS di app.blade.php) */
    .navbar.scrolled {
        background: white !important;
        position: fixed;
        padding-top: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05) !important;
    }

    /* Warna Teks Navbar di Home (Putih saat di atas Hero) */
    .navbar:not(.scrolled) .nav-link,
    .navbar:not(.scrolled) .logo, 
    .navbar:not(.scrolled) .welcome-text {
        color: white !important;
        text-shadow: 0 2px 4px rgba(0,0,0,0.5); /* Shadow agar terbaca */
    }
    
    /* Logo Span (Oren) tetap oren */
    .navbar:not(.scrolled) .logo span { color: var(--primary) !important; }

    /* Tombol-tombol di navbar saat transparan */
    .navbar:not(.scrolled) .btn-primary-outline {
        border-color: white !important;
        color: white !important;
    }
    .navbar:not(.scrolled) .btn-primary-outline:hover {
        background: white !important;
        color: var(--primary) !important;
    }


    /* === 2. HERO SECTION DENGAN BACKGROUND GAMBAR === */
    .hero-section {
        /* Ganti 'login-bg.jpg' dengan nama file Anda di public/img */
        background-image: url('{{ asset("img/bg-home.png") }}'); 
        background-size: cover;
        background-position: center;
        height: 100vh; /* Full Layar */
        min-height: 600px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        text-align: center;
        color: white;
        margin-top: 0; /* Reset margin */
    }

    /* Overlay Gelap (Wajib agar tulisan terbaca) */
    .hero-overlay {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.6); /* Gelap 60% */
        z-index: 1;
    }

    .hero-content {
        position: relative; z-index: 2;
        max-width: 900px;
        padding: 20px;
        padding-top: 80px; /* Kompensasi navbar */
    }

    .hero-title {
        font-family: var(--font-head);
        font-size: 5rem; /* Sangat Besar & Mewah */
        font-weight: 900;
        color: white;
        margin-bottom: 20px;
        line-height: 1.1;
        text-shadow: 0 4px 10px rgba(0,0,0,0.3);
    }
    .hero-title span { color: var(--primary); font-style: italic; }

    .hero-subtitle {
        font-size: 1.3rem;
        color: #f0f0f0;
        margin: 0 auto 40px;
        font-weight: 300;
        max-width: 700px;
    }

    /* Tombol Hero Besar */
    .btn-hero {
        background: var(--primary);
        color: white;
        padding: 18px 50px;
        font-size: 1.1rem;
        font-weight: 700;
        text-transform: uppercase;
        border-radius: 50px;
        text-decoration: none;
        display: inline-block;
        transition: 0.3s;
        border: 2px solid var(--primary);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }
    .btn-hero:hover {
        background: transparent;
        color: white;
        transform: translateY(-3px);
    }


    /* === 3. MENU FAVORIT (RAPIG & BERSIH) === */
    .menu-section { padding: 100px 0; background: white; }
    
    .section-header { text-align: center; margin-bottom: 60px; }
    .section-title { 
        font-family: var(--font-head); 
        font-size: 3rem; 
        color: var(--secondary-color); 
        margin-bottom: 10px;
    }
    
    .menu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 40px;
    }
    
    .menu-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
        border: 1px solid #eee;
        display: flex; flex-direction: column;
    }
    .menu-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
    
    .menu-img { width: 100%; height: 250px; object-fit: cover; }
    .menu-body { padding: 25px; flex-grow: 1; display: flex; flex-direction: column; }
    
    .menu-title { font-size: 1.4rem; font-weight: 700; margin-bottom: 5px; color: var(--secondary-color); }
    
    .menu-rating { 
        color: #f59e0b; margin-bottom: 15px; font-weight: 600; font-size: 0.9rem; 
        display: flex; align-items: center; gap: 5px;
    }
    
    .menu-desc { color: #666; font-size: 0.95rem; margin-bottom: 20px; line-height: 1.6; flex-grow: 1; }
    
    .menu-footer { 
        display: flex; justify-content: space-between; align-items: center; 
        margin-top: auto; border-top: 1px solid #f9f9f9; padding-top: 20px;
    }
    .menu-price { font-size: 1.3rem; font-weight: 800; color: var(--primary); }
    
    .btn-pesan-mini {
        background: transparent; border: 2px solid var(--primary);
        color: var(--primary); padding: 8px 20px; border-radius: 50px;
        font-weight: 600; text-decoration: none; transition: 0.2s;
    }
    .btn-pesan-mini:hover { background: var(--primary); color: white; }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .hero-title { font-size: 3.5rem; }
        .hero-section { height: auto; min-height: 100vh; padding: 100px 0; }
        .navbar { background: white !important; position: fixed; } /* Di HP navbar putih aja biar aman */
        .navbar:not(.scrolled) .nav-link, .navbar:not(.scrolled) .logo { color: var(--dark) !important; text-shadow: none; }
    }
</style>
@endsection

@section('content')

    {{-- HERO SECTION (GAMBAR BACKGROUND) --}}
    <div class="hero-section">
        <div class="hero-overlay"></div> {{-- Lapisan Gelap --}}
        
        <div class="hero-content">
            {{-- Judul Besar --}}
            <h1 class="hero-title">Rasa <span>Otentik</span> <br> Masakan ala Rumah</h1>
            
            {{-- Subjudul --}}
            <p class="hero-subtitle">
                Rasakan kelezatan masakan rumahan otentik dari Nyamaw. 
                Dibuat dari bahan segar pilihan, tanpa pengawet, penuh cinta.
            </p>
            
            {{-- Tombol CTA --}}
            <a href="{{ route('menu') }}" class="btn-hero">LIHAT SEMUA MENU</a>
        </div>
    </div>

    {{-- MENU FAVORIT SECTION --}}
    <section class="menu-section container">
        <div class="section-header">
            <h2 class="section-title">Menu Favorit Pelanggan</h2>
            <p style="color:#777;">Pilihan terbaik minggu ini yang wajib kamu coba!</p>
        </div>

        <div class="menu-grid">
            @forelse($featuredMenus as $item)
                <div class="menu-card">
                    <a href="{{ route('menu') }}">
                        <img src="{{ $item->image_url ? asset('storage/' . $item->image_url) : 'https://via.placeholder.com/300' }}" 
                             class="menu-img" alt="{{ $item->name }}">
                    </a>
                    
                    <div class="menu-body">
                        <h3 class="menu-title">{{ $item->name }}</h3>
                        
                        {{-- Rating --}}
                        <div class="menu-rating">
                            @php $rating = $item->getRating(); @endphp
                            @if($rating > 0)
                                <span>⭐ {{ $rating }}</span> 
                                <span style="color:#999; font-weight:normal; font-size:0.8rem;">({{ $item->getReviewCount() }} ulasan)</span>
                            @else
                                <span style="color:#999; font-weight:normal;">Belum ada ulasan</span>
                            @endif
                        </div>

                        <p class="menu-desc">{{ Str::limit($item->description, 80) }}</p>

                        <div class="menu-footer">
                            <span class="menu-price">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                            <a href="{{ route('menu') }}" class="btn-pesan-mini">Pesan</a>
                        </div>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 60px; color: #777;">
                    <p>Belum ada menu unggulan yang ditampilkan.</p>
                </div>
            @endforelse
        </div>
    </section>

@endsection