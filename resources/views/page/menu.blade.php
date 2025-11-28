<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Menu Lengkap - Nyamaw</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

  <style>
    :root {
      --primary-color: #FF6347;
      --secondary-color: #333333;
      --bg-color: #fdfaf8;
      --text-color: #4a4a4a;
      --card-bg: #ffffff;
      --font-primary: 'Poppins', sans-serif;
      --font-secondary: 'Playfair Display', serif;
      --shadow: 0 10px 20px rgba(0,0,0,0.05);
      --radius: 12px;
    }
    .navbar-auth { display: flex; align-items: center; gap: 16px; }
    .welcome-text { font-weight: 600; color: var(--text-color); }
    * { box-sizing: border-box; }
    body { margin: 0; font-family: var(--font-primary); background: var(--bg-color); color: var(--text-color); line-height: 1.7; }
    .container { max-width: 1100px; margin: 0 auto; padding: 0 24px; }
    h1, h2, h3 { font-family: var(--font-secondary); color: var(--secondary-color); }

    .navbar { background: rgba(255,255,255,0.8); backdrop-filter: blur(10px); padding: 16px 0; border-bottom: 1px solid #eee; position: sticky; top: 0; z-index: 100; }
    .navbar .container { display: flex; justify-content: space-between; align-items: center; }
    .navbar-brand { font-family: var(--font-secondary); font-size: 1.8rem; text-decoration: none; color: var(--primary-color); }
    .navbar-nav { list-style: none; margin: 0; padding: 0; display: flex; gap: 24px; }
    .nav-link { text-decoration: none; color: var(--text-color); font-weight: 600; transition: color 0.2s; }
    .nav-link:hover, .nav-link.active { color: var(--primary-color); }
    .btn-primary-outline { background: transparent; color: var(--primary-color); border: 2px solid var(--primary-color); padding: 8px 16px; text-decoration: none; border-radius: 50px; font-weight: 600; transition: all 0.2s; display: inline-block; cursor: pointer; }
    .btn-primary-outline:hover { background: var(--primary-color); color: #fff; }

    .page-header { text-align: center; padding: 80px 0; background-color: var(--secondary-color); color: #fff; }
    .page-header h1 { color: #fff; font-size: 3.5rem; }

    .full-menu { padding: 80px 0; }
    .menu-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 32px; }
    .menu-card { background: var(--card-bg); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; text-align: left; display: flex; flex-direction: column; }
    .menu-card img { width: 100%; height: 220px; object-fit: cover; }
    .menu-card-body { padding: 24px; flex-grow: 1; display: flex; flex-direction: column; }
    .menu-card-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px; }
    .menu-card h3 { margin: 0; font-size: 1.4rem; }
    .menu-card .price { font-size: 1.2rem; font-weight: 700; color: var(--primary-color); white-space: nowrap; margin-left: 16px; }
    .menu-card p { margin: 8px 0 0 0; flex-grow: 1; }

    .footer { background: var(--secondary-color); color: #fff; text-align: center; padding: 48px 0; }
    .footer p { margin: 0; }
    .footer .copyright { opacity: 0.7; margin-top: 8px; font-size: 0.9rem; }
  </style>
</head>
<body>

  <nav class="navbar">
  <div class="container">
    <a href="{{ route('home') }}" class="navbar-brand">Nyamaw 🐾</a>
    <ul class="navbar-nav">
      <li><a href="{{ route('home') }}" class="nav-link">Home</a></li>
      <li><a href="{{ route('menu') }}" class="nav-link active">Menu</a></li>
      @auth
        <li><a href="{{ route('orders.history') }}" class="nav-link">Riwayat</a></li>
      @endauth
      <li><a href="{{ route('about') }}" class="nav-link">About</a></li>
    </ul>
    <div class="navbar-auth">
      @auth
        <a href="{{ route('cart') }}" class="btn-primary-outline" style="position: relative;">
            🛒 <span style="font-size: 0.9rem;">{{ count((array) session('cart')) }}</span>
        </a>
        <span class="welcome-text">Halo, {{ Auth::user()->name }}</span>
        <form action="{{ route('logout') }}" method="POST" style="margin:0;">
          @csrf
          <button type="submit" class="btn-primary-outline">Logout</button>
        </form>
      @else
        <a href="{{ route('login') }}" class="nav-link" style="font-weight:600;">Login</a>
        <a href="{{ route('register') }}" class="btn-primary-outline">Register</a>
      @endauth
    </div>
  </div>
</nav>

  <main>
    <section class="page-header">
      <div class="container">
        <h1>Menu Lengkap Kami</h1>
        <p>Semua hidangan spesial kami siap untuk Anda nikmati.</p>
      </div>
    </section>

    <div class="container" style="margin-top: 20px;">
        @if(session('status'))
            <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                {{ session('status') }}
            </div>
        @endif
    </div>

    <section class="full-menu">
      <div class="container">
        <div class="menu-grid">

          @forelse($menuItems as $item)
          <div class="menu-card">
            {{-- Gambar --}}
            <img src="{{ $item->image_url ? asset('storage/' . $item->image_url) : 'https://via.placeholder.com/300x220?text=No+Image' }}" alt="{{ $item->name }}">
            
            <div class="menu-card-body">
              {{-- Header: Nama & Harga --}}
              <div class="menu-card-header">
                <h3>{{ $item->name }}</h3>
                <span class="price">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
              </div>

              {{-- Rating Bintang --}}
              <div style="margin-bottom: 12px; color: #f59e0b; font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 5px;">
                @php $rating = $item->getRating(); @endphp
                @if($rating > 0)
                    <span>⭐ {{ $rating }}</span>
                    <span style="color: #888; font-weight: normal; font-size: 0.8rem;">({{ $item->getReviewCount() }} ulasan)</span>
                @else
                    <span style="color: #888; font-weight: normal; font-size: 0.8rem;">Belum ada ulasan</span>
                @endif
              </div>

              <p>{{ $item->description }}</p>

              {{-- Form Pesan --}}
              <div style="margin-top: 16px; margin-bottom: 16px;">
                @auth
                    @if($item->availability_status != 'sold_out')
                        <form action="{{ route('cart.add', $item->id) }}" method="POST" style="display: flex; gap: 8px; align-items: center;">
                            @csrf
                            {{-- Input Jumlah --}}
                            <input type="number" name="quantity" value="1" min="1" 
                                   style="width: 60px; padding: 8px; border: 2px solid var(--primary-color); border-radius: 8px; text-align: center; font-weight: bold; color: var(--secondary-color);">
                            {{-- Tombol Pesan --}}
                            <button type="submit" class="btn-primary-outline" style="flex: 1; cursor: pointer; background: var(--primary-color); color: white;">
                                🛒 Pesan
                            </button>
                        </form>
                    @else
                         <button disabled class="btn-primary-outline" style="width: 100%; opacity: 0.5; cursor: not-allowed; background: #eee; border-color: #ccc; color: #999;">
                            Habis Terjual
                        </button>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn-primary-outline" style="width: 100%; display: block; text-align: center; background-color: #f3f4f6; border-color: #d1d5db; color: #6b7280;">
                        Login untuk Memesan
                    </a>
                @endauth
              </div>

              {{-- Status Ketersediaan --}}
              <div style="margin-top: auto;">
                @if($item->availability_status == 'sold_out')
                    <span style="background: #fee2e2; color: #991b1b; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: bold;">Habis</span>
                @else
                    <span style="background: #dcfce7; color: #166534; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: bold;">Tersedia</span>
                @endif
                <small style="color: #666; margin-left: 8px;">{{ $item->category }}</small>
              </div>

            </div>
          </div>
          @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                <h3>Belum ada menu yang tersedia saat ini.</h3>
                <p>Silakan kembali lagi nanti!</p>
            </div>
          @endforelse

        </div>
      </div>
    </section>
  </main>

  <footer class="footer">
    <div class="container">
      <p>Dibuat dengan ❤️ untuk para pecinta kuliner.</p>
      <p class="copyright">&copy; {{ date('Y') }} Nyamaw. All Rights Reserved.</p>
    </div>
  </footer>

</body>
</html>