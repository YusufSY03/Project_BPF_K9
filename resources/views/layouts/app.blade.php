<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title', 'Nyamaw - Taste of Home')</title>

  <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&family=Playfair+Display:ital,wght@0,700;0,900;1,700&display=swap" rel="stylesheet">

  @stack('styles')

  <style>
    :root {
      --primary: #FF6347;
      --dark: #222222;
      --light: #f8f5f2;
      --white: #ffffff;

      --font-body: 'Poppins', sans-serif;
      --font-head: 'Playfair Display', serif;

      --container-width: 1200px;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: var(--font-body);
      background-color: var(--light);
      color: var(--dark);
      line-height: 1.6;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      overflow-x: hidden;
    }

    a {
      text-decoration: none;
      color: inherit;
      transition: 0.3s;
    }

    ul {
      list-style: none;
    }

    .container {
      max-width: var(--container-width);
      margin: 0 auto;
      padding: 0 20px;
      width: 100%;
    }

    /* TYPOGRAPHY */
    h1,
    h2,
    h3 {
      font-family: var(--font-head);
      font-weight: 900;
    }

    /* NAVBAR YANG LEBIH CANTIK */
    .navbar {
      padding: 20px 0;
      background: transparent;
      /* Awalnya transparan agar background hero terlihat */
      position: fixed;
      /* Fixed agar melayang */
      top: 0;
      width: 100%;
      z-index: 1000;
      transition: 0.4s ease;
    }

    /* Saat discroll, navbar jadi putih dan ada shadow */
    .navbar.scrolled {
      background: var(--white);
      padding: 15px 0;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    .nav-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .logo {
      font-family: var(--font-head);
      font-size: 2rem;
      font-weight: 900;
      color: var(--primary);
      /* Logo selalu oren */
      letter-spacing: -1px;
    }

    .nav-menu {
      display: flex;
      gap: 30px;
      align-items: center;
    }

    .nav-link {
      font-weight: 500;
      font-size: 1rem;
      color: var(--dark);
      position: relative;
    }

    .nav-link:hover,
    .nav-link.active {
      color: var(--primary);
    }

    .cart-btn {
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .cart-count {
      background: var(--primary);
      color: white;
      font-size: 0.7rem;
      padding: 2px 6px;
      border-radius: 50%;
    }

    .btn-auth {
      background: var(--primary);
      color: white;
      padding: 8px 20px;
      border-radius: 50px;
      font-weight: 600;
      font-size: 0.9rem;
    }

    .btn-auth:hover {
      background: #e55337;
      transform: translateY(-2px);
    }

    .hamburger {
      display: none;
      font-size: 1.5rem;
      cursor: pointer;
    }

    /* FOOTER */
    footer {
      background-color: var(--dark);
      color: var(--white);
      padding: 60px 0 30px;
      margin-top: auto;
    }

    .footer-content {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 40px;
      margin-bottom: 40px;
    }

    .footer h3 {
      color: var(--primary);
      margin-bottom: 20px;
      font-size: 1.8rem;
    }

    .copyright {
      text-align: center;
      font-size: 0.8rem;
      opacity: 0.6;
      border-top: 1px solid #333;
      padding-top: 20px;
    }

    /* CSS PER PAGE */
    @yield('custom-css')
  </style>
</head>

<body>

  <nav class="navbar" id="navbar">
    <div class="container nav-container">
      <a href="{{ route('home') }}" class="logo">Nyamaw.</a>

      <div class="hamburger" onclick="toggleMenu()">☰</div>

      <ul class="nav-menu" id="navMenu">
        <li style="position:absolute; top:20px; right:20px; font-size:1.5rem; cursor:pointer; display:none;" class="mobile-close" onclick="toggleMenu()">✕</li>

        <li><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
        <li><a href="{{ route('menu') }}" class="nav-link {{ request()->routeIs('menu') ? 'active' : '' }}">Menu</a></li>
        @auth
        <li><a href="{{ route('orders.history') }}" class="nav-link {{ request()->routeIs('orders.history') ? 'active' : '' }}">Riwayat</a></li>
        @endauth
        <li><a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">Tentang</a></li>
        @auth
        <span style="margin-right: 15px; font-weight: 600; color: var(--primary);">
          Halo, {{ Auth::user()->name }}
        </span>
        <li>
          <a href="{{ route('cart') }}" class="cart-btn">
            🛒 <span class="cart-count">{{ count((array) session('cart')) }}</span>
          </a>
        </li>
        <li>
          <form action="{{ route('logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" style="background:none; border:none; cursor:pointer; font-weight:600; color:var(--primary);">KELUAR</button>
          </form>
        </li>
        @else
        <li><a href="{{ route('login') }}" class="btn-auth">Login</a></li>
        <li><a href="{{ route('register') }}" class="btn-auth">Sign In</a></li>
        @endauth
      </ul>
    </div>
  </nav>

  <main>
    @yield('content')
  </main>

  <footer>
    <div class="container">
      <div class="footer-content">
        <div>
          <h3>Nyamaw.</h3>
          <p style="opacity: 0.7;">Sajian lezat dari dapur rumahan.</p>
        </div>
        <div>
          <h4>Menu</h4>
          <ul>
            <li><a href="{{ route('menu') }}">Lihat Menu</a></li>
            <li><a href="{{ route('about') }}">Tentang Kami</a></li>
          </ul>
        </div>
        <div>
          <h4>Kontak</h4>
          <ul>
            <li>0853-5572-3330</li>
            <li>@nyam.aw</li>
          </ul>
        </div>
      </div>
      <div class="copyright">&copy; {{ date('Y') }} Nyamaw. All Rights Reserved.</div>
    </div>
  </footer>

  <script>
    function toggleMenu() {
      const menu = document.getElementById('navMenu');
      const closeBtn = document.querySelector('.mobile-close');
      menu.classList.toggle('active');
      if (menu.classList.contains('active')) {
        closeBtn.style.display = 'block';
      } else {
        closeBtn.style.display = 'none';
      }
    }

    // Efek Navbar Transparan ke Putih
    window.addEventListener('scroll', function() {
      const navbar = document.getElementById('navbar');
      if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });
  </script>
  @stack('scripts')
</body>

</html>