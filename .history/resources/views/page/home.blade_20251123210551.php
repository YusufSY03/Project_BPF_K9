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

    .navbar-auth {
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .welcome-text {
      font-weight: 600;
      color: var(--text-color);
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

    .btn-primary-outline {
      background: transparent;
      color: var(--primary-color);
      border: 2px solid var(--primary-color);
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 50px;
      font-weight: 600;
      transition: all 0.2s;
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
      font-size: 4rem;
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
    }

    .menu-card:hover {
      transform: translateY(-10px);
    }

    .menu-card img {
      width: 100%;
      height: 220px;
      object-fit: cover;
    }

    .menu-card-body {
      padding: 24px;
    }

    .menu-card h3 {
      margin: 0 0 8px 0;
      font-size: 1.5rem;
    }

    .menu-card p {
      margin: 0;
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
        <li><a href="{{ route('about') }}" class="nav-link">About</a></li>
      </ul>
      <div class="navbar-auth">
        {{-- Logika baru menggunakan Auth::check() --}}
        @auth
        {{-- Tampilkan nama user yang login dari database --}}
        <span class="welcome-text">Halo, {{ Auth::user()->name }}</span>
        <form action="{{ route('logout') }}" method="POST" style="margin:0;">
          @csrf
          <button type="submit" class="btn-primary-outline">Logout</button>
        </form>
        @else
        {{-- INI YANG BARU: Tombol Login + Register untuk tamu --}}
        <a href="{{ route('login') }}" class="nav-link" style="font-weight:600;">Login</a>
        <a href="{{ route('register') }}" class="btn-primary-outline">Register</a>
        @endauth
      </div>
    </div>
  </nav>

  <main>
    <section class="hero">
      <div class="container">
        <h1>Sajian Lezat, Momen Hangat.</h1>
        <p>Rasakan kelezatan masakan rumahan otentik dari Nyamaw, dibuat dari bahan-bahan segar pilihan dan resep warisan keluarga.</p>
        <a href="#" class="btn-primary-outline">Lihat Semua Menu</a>
        <img src="https://via.placeholder.com/800x500/FF6347/FFFFFF?text=Hidangan+Spesial+Nyamaw" alt="Hidangan Spesial Nyamaw">
      </div>
    </section>

    <section class="featured-menu">
      <div class="container">
        <h2>Menu Favorit Pelanggan</h2>
        <div class="menu-grid">
          <div class="menu-card">
            <img src="https://via.placeholder.com/300x220/fde68a/000000?text=Chicken+Steak" alt="Chicken Steak">
            <div class="menu-card-body">
              <h3>Chicken Steak</h3>
              <p>Steak ayam juicy disajikan dengan saus barbekyu dan kentang goreng.</p>
            </div>
          </div>
          <div class="menu-card">
            <img src="https://via.placeholder.com/300x220/fca5a5/000000?text=Katsu+Lada+Hitam" alt="Katsu Lada Hitam">
            <div class="menu-card-body">
              <h3>Katsu Lada Hitam</h3>
              <p>Potongan ayam katsu renyah dibalut saus lada hitam yang hangat.</p>
            </div>
          </div>
          <div class="menu-card">
            <img src="https://via.placeholder.com/300x220/a7f3d0/000000?text=Ayam+Suwir+Pedas" alt="Ayam Suwir Pedas">
            <div class="menu-card-body">
              <h3>Ayam Suwir Pedas</h3>
              <p>Suwiran ayam dengan bumbu pedas khas nusantara yang bikin nagih.</p>
            </div>
          </div>
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