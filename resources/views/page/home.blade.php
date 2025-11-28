<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nyamaw - Cita Rasa Otentik Rumahan</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

  <style>
    /* =================================
       PENGATURAN DASAR & SKEMA WARNA
       ================================= */
    :root {
      --primary-color: #FF6347;
      /* Tomato */
      --secondary-color: #333333;
      /* Dark Gray */
      --bg-color: #fdfaf8;
      /* Soft Cream */
      --text-color: #4a4a4a;
      --card-bg: #ffffff;
      --font-primary: 'Poppins', sans-serif;
      --font-secondary: 'Playfair Display', serif;
      --shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
      --radius: 12px;
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: var(--font-primary);
      background: var(--bg-color);
      color: var(--text-color);
      line-height: 1.7;
    }

    .container {
      max-width: 1100px;
      margin: 0 auto;
      padding: 0 24px;
    }

    h1,
    h2,
    h3 {
      font-family: var(--font-secondary);
      color: var(--secondary-color);
    }

    /* =================================
       NAVBAR
       ================================= */
    .navbar {
      background: rgba(255, 255, 255, 0.8);
      backdrop-filter: blur(10px);
      padding: 16px 0;
      border-bottom: 1px solid #eee;
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .navbar .container {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .navbar-brand {
      font-family: var(--font-secondary);
      font-size: 1.8rem;
      text-decoration: none;
      color: var(--primary-color);
    }

    .navbar-nav {
      list-style: none;
      margin: 0;
      padding: 0;
      display: flex;
      gap: 24px;
    }

    .nav-link {
      text-decoration: none;
      color: var(--text-color);
      font-weight: 600;
      transition: color 0.2s;
    }

    .nav-link:hover,
    .nav-link.active {
      color: var(--primary-color);
    }

    .navbar-auth {
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .welcome-text {
      font-weight: 600;
      color: var(--text-color);
    }

    .btn-primary-outline {
      background: transparent;
      color: var(--primary-color);
      border: 2px solid var(--primary-color);
      padding: 8px 20px;
      text-decoration: none;
      border-radius: 50px;
      font-weight: 600;
      transition: all 0.2s;
      display: inline-block;
      cursor: pointer;
      /* Tambahan agar kursor berubah saat hover */
    }

    .btn-primary-outline:hover {
      background: var(--primary-color);
      color: #fff;
    }

    /* =================================
       HERO SECTION
       ================================= */
    .hero {
      text-align: center;
      padding: 100px 0;
    }

    .hero h1 {
      font-size: 3.5rem;
      line-height: 1.2;
      margin: 0 0 16px 0;
    }

    .hero p {
      font-size: 1.2rem;
      max-width: 600px;
      margin: 0 auto 32px auto;
      color: #777;
    }

    .hero img {
      width: 100%;
      max-width: 800px;
      margin-top: 48px;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
    }

    /* =================================
       FEATURED MENU SECTION
       ================================= */
    .featured-menu {
      padding: 80px 0;
      text-align: center;
    }

    .featured-menu h2 {
      font-size: 2.5rem;
      margin-bottom: 10px;
    }

    .featured-menu p.subtitle {
      color: #777;
      margin-bottom: 48px;
    }

    .menu-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 32px;
    }

    .menu-card {
      background: var(--card-bg);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      overflow: hidden;
      text-align: left;
      transition: transform 0.3s;
      display: flex;
      flex-direction: column;
    }

    .menu-card:hover {
      transform: translateY(-5px);
    }

    .menu-card img {
      width: 100%;
      height: 220px;
      object-fit: cover;
    }

    .menu-card-body {
      padding: 24px;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
    }

    .menu-card h3 {
      margin: 0 0 8px 0;
      font-size: 1.4rem;
    }

    .menu-card .description {
      color: #666;
      font-size: 0.95rem;
      margin-bottom: 16px;
      flex-grow: 1;
    }

    .menu-card .price {
      font-weight: 700;
      color: var(--primary-color);
      font-size: 1.2rem;
      display: block;
      margin-bottom: 16px;
    }

    /* =================================
       FOOTER
       ================================= */
    .footer {
      background: var(--secondary-color);
      color: #fff;
      text-align: center;
      padding: 48px 0;
    }

    .footer p {
      margin: 0;
    }

    .footer .copyright {
      opacity: 0.7;
      margin-top: 8px;
      font-size: 0.9rem;
    }
  </style>
</head>

<body>

  <nav class="navbar">
    <div class="container">
      <a href="{{ route('home') }}" class="navbar-brand">Nyamaw 🐾</a>
      <ul class="navbar-nav">
        <li><a href="{{ route('home') }}" class="nav-link active">Home</a></li>
        <li><a href="{{ route('menu') }}" class="nav-link">Menu</a></li>
        @auth
        <li><a href="{{ route('orders.history') }}" class="nav-link">Riwayat</a></li>
        @endauth
      </ul>
      <div class="navbar-auth">
        @auth
        {{-- Tombol Keranjang --}}
        <a href="{{ route('cart') }}" class="btn-primary-outline" style="position: relative; margin-right: 8px;">
          🛒 <span style="font-size: 0.9rem;">{{ count((array) session('cart')) }}</span>
        </a>

        <span class="welcome-text">Halo, {{ Auth::user()->name }}</span>

        {{-- TOMBOL LOGOUT DIPERBAIKI (Konsisten dengan Menu) --}}
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
    {{-- HERO SECTION --}}
    <section class="hero">
      <div class="container">
        <h1>Sajian Lezat, <br>Momen Hangat.</h1>
        <p>Rasakan kelezatan masakan rumahan otentik dari Nyamaw, dibuat dari bahan-bahan segar pilihan dan resep warisan keluarga.</p>

        <a href="{{ route('menu') }}" class="btn-primary-outline" style="padding: 12px 32px; font-size: 1.1rem;">Lihat Semua Menu &rarr;</a>

        <img src="https://via.placeholder.com/800x500/fde68a/000000?text=Hidangan+Spesial+Nyamaw" alt="Hidangan Spesial Nyamaw">
      </div>
    </section>

    {{-- FEATURED MENU SECTION --}}
    <section class="featured-menu">
      <div class="container">
        <h2>Menu Favorit Pelanggan</h2>
        <p class="subtitle">Pilihan terbaik minggu ini yang wajib kamu coba!</p>

        <div class="menu-grid">
          {{-- LOOPING DATA REAL DARI DATABASE --}}
          @forelse($featuredMenus as $item)
          <div class="menu-card">
            {{-- Gambar dari Storage --}}
            <img src="{{ $item->image_url ? asset('storage/' . $item->image_url) : 'https://via.placeholder.com/300x220?text=No+Image' }}"
              alt="{{ $item->name }}">

            <div class="menu-card-body">
              <h3>{{ $item->name }}</h3>
              <p class="description">{{ Str::limit($item->description, 80) }}</p>
              <span class="price">Rp {{ number_format($item->price, 0, ',', '.') }}</span>

              <a href="{{ route('menu') }}" class="btn-primary-outline" style="text-align: center; display: block;">Pesan Sekarang</a>
            </div>
          </div>
          @empty
          <div style="grid-column: 1 / -1; padding: 40px; background: #fff; border-radius: 12px;">
            <p>Belum ada menu unggulan yang ditampilkan.</p>
            @if(Auth::check() && Auth::user()->role === 'owner')
            <a href="{{ route('owner.menu.create') }}" class="btn-primary-outline">Tambah Menu Sekarang</a>
            @endif
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