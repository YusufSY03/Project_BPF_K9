<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title') | Nyamaw Dashboard</title>

  {{-- 1. ICON & FONT (SAMA DENGAN HALAMAN PUBLIK) --}}
  <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&family=Playfair+Display:ital,wght@0,700;0,900;1,700&display=swap" rel="stylesheet">

  {{-- 2. CSS VARIABLES (SAMA DENGAN HALAMAN PUBLIK) --}}
  <style>
    :root {
      --primary: #FF4500; /* Oranye Nyamaw */
      --dark: #121212;    /* Hitam Pekat */
      --light: #f8f5f2;   /* Background Cream Tipis */
      --white: #ffffff;
      --gray: #888888;
      --sidebar-width: 260px;
      
      --font-body: 'Poppins', sans-serif;
      --font-head: 'Playfair Display', serif;
      
      --radius: 12px;
      --shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body { 
      font-family: var(--font-body); 
      background-color: var(--light); 
      color: var(--dark); 
      display: flex; 
      min-height: 100vh;
    }

    /* === 3. SIDEBAR (GAYA DARK ELEGANT) === */
    .sidebar {
      width: var(--sidebar-width);
      background: var(--dark); /* Hitam Pekat */
      color: var(--white);
      height: 100vh;
      position: fixed;
      top: 0; left: 0;
      display: flex;
      flex-direction: column;
      padding: 30px 20px;
      box-shadow: 5px 0 15px rgba(0,0,0,0.05);
      z-index: 100;
    }

    .sidebar-header {
      font-family: var(--font-head);
      font-size: 1.8rem;
      font-weight: 900;
      color: var(--white);
      margin-bottom: 40px;
      padding-bottom: 20px;
      border-bottom: 1px solid rgba(255,255,255,0.1);
      text-align: center;
    }
    .sidebar-header span { color: var(--primary); }

    .sidebar-nav {
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .sidebar-nav a {
      display: flex;
      align-items: center;
      padding: 12px 20px;
      color: rgba(255,255,255,0.7);
      text-decoration: none;
      border-radius: 8px;
      font-weight: 500;
      transition: 0.3s;
      font-size: 0.95rem;
    }

    /* Efek Hover & Active di Sidebar */
    .sidebar-nav a:hover {
      background: rgba(255,255,255,0.05);
      color: var(--white);
      padding-left: 25px; /* Efek geser sedikit */
    }

    .sidebar-nav a.active {
      background: var(--primary); /* Oranye */
      color: var(--white);
      font-weight: 600;
      box-shadow: 0 4px 10px rgba(255, 69, 0, 0.3);
    }

    .sidebar-footer {
      border-top: 1px solid rgba(255,255,255,0.1);
      padding-top: 20px;
    }

    /* === 4. MAIN CONTENT === */
    .main-content {
      margin-left: var(--sidebar-width);
      flex-grow: 1;
      padding: 40px;
      width: calc(100% - var(--sidebar-width));
    }

    /* Header Halaman */
    .header { 
      display: flex; 
      justify-content: space-between; 
      align-items: flex-end; 
      margin-bottom: 30px; 
    }
    .header h1 { 
      font-family: var(--font-head); 
      font-size: 2.5rem; 
      color: var(--dark); 
      margin-bottom: 5px;
    }
    .header p { color: var(--gray); font-size: 1rem; }

    /* === 5. KOMPONEN CARD (KARTU) === */
    .card {
      background: var(--white);
      border-radius: var(--radius);
      padding: 25px;
      box-shadow: var(--shadow);
      border: 1px solid #eee;
      margin-bottom: 24px;
    }

    /* Grid Statistik (4 Kolom) */
    .card-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 25px;
      margin-bottom: 30px;
    }

    .card-title {
      font-size: 0.85rem;
      color: var(--gray);
      text-transform: uppercase;
      letter-spacing: 1px;
      font-weight: 600;
      margin-bottom: 10px;
    }
    
    .card-value {
      font-family: var(--font-head);
      font-size: 2.2rem;
      font-weight: 700;
      color: var(--dark);
      margin: 0;
    }

    .card-change { margin-top: 10px; font-size: 0.85rem; color: var(--gray); }

    /* === 6. BUTTONS === */
    .btn { 
      display: inline-block; padding: 10px 20px; 
      border-radius: 6px; text-decoration: none; 
      font-weight: 600; font-size: 0.9rem; transition: 0.2s; border: none; cursor: pointer;
    }
    .btn-success { background: var(--primary); color: white; box-shadow: 0 4px 10px rgba(255,69,0,0.2); }
    .btn-success:hover { background: #e53e00; transform: translateY(-2px); }
    
    .btn-danger { background: #ef4444; color: white; width: 100%; text-align: center; }
    .btn-danger:hover { background: #dc2626; }

    .btn-edit { background: #f3f4f6; color: var(--dark); }
    .btn-edit:hover { background: #e5e7eb; }
    
    .btn-delete { background: #fee2e2; color: #b91c1c; }
    .btn-delete:hover { background: #fecaca; }

    /* === 7. TABEL === */
    .user-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .user-table th { 
        text-align: left; padding: 15px; 
        background: #f9fafb; color: var(--gray); 
        font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;
        border-bottom: 1px solid #eee;
    }
    .user-table td { padding: 15px; border-bottom: 1px solid #f3f4f6; vertical-align: middle; }
    .user-table tr:last-child td { border-bottom: none; }

    .badge { padding: 6px 12px; border-radius: 50px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
    
    /* Pagination Custom */
    .custom-pagination nav svg { height: 20px; width: 20px; }
    .custom-pagination nav .flex { display: flex; align-items: center; gap: 5px; }
    .custom-pagination nav span, .custom-pagination nav a { 
        padding: 6px 12px; border: 1px solid #eee; border-radius: 6px; 
        text-decoration: none; color: var(--dark); font-size: 0.9rem;
    }
    .custom-pagination nav span[aria-current="page"] {
        background-color: var(--primary); color: white; border-color: var(--primary);
    }

    /* Search Filter */
    .search-filter-container { display: flex; gap: 15px; flex-wrap: wrap; }
    .input, .select { 
        padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; 
        font-family: var(--font-body); font-size: 0.95rem; outline: none;
    }
    .input:focus, .select:focus { border-color: var(--primary); }
  </style>
</head>
<body>

  {{-- SIDEBAR --}}
  <aside class="sidebar">
    <div class="sidebar-header">Nya<span>maw</span>.</div>
    
    <nav class="sidebar-nav">
      {{-- Link Dashboard --}}
      <a href="{{ Auth::user()->role === 'admin' ? route('admin') : route('owner') }}" 
         class="{{ request()->routeIs('admin', 'owner') ? 'active' : '' }}">
         📊 Dashboard
      </a>
      
      {{-- Pesanan Masuk --}}
      <a href="{{ route('orders.index') }}" 
         class="{{ request()->routeIs('orders.*') ? 'active' : '' }}">
         📦 Pesanan Masuk
      </a>

      {{-- Link Khusus Owner --}}
      @if (Auth::user()->role === 'owner')
        <a href="{{ route('owner.menu.index') }}" 
           class="{{ request()->routeIs('owner.menu.*') ? 'active' : '' }}">
           🍽️ Manajemen Menu
        </a>
      @endif
      
      {{-- Manajemen User (Admin & Owner) --}}
      <a href="{{ route('users.index') }}" 
         class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
         👥 Manajemen User
      </a>

      {{-- Link Balik ke Web --}}
      <a href="{{ route('home') }}" target="_blank" style="margin-top: 20px; border: 1px solid rgba(255,255,255,0.2);">
         🌐 Lihat Website
      </a>
    </nav>
    
    <div class="sidebar-footer">
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button class="btn btn-danger" style="width:100%;">LOGOUT</button>
      </form>
    </div>
  </aside>

  {{-- MAIN CONTENT --}}
  <main class="main-content">
    @yield('content')
  </main>
  
</body>
</html>