<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  {{-- Judul halaman akan diisi oleh halaman 'child' --}}
  <title>@yield('title') | Nyamaw Dashboard</title>
  
  {{-- CSS ini kita ambil dari file dasbor Anda --}}
  <style>
    :root {
      --bg: #f3f4f6; --card: #ffffff; --text: #111827; --muted: #6b7280;
      --primary: #3b82f6; --danger: #ef4444; --success: #10b981;
      --radius: 10px; --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    }
    * { box-sizing: border-box; }
    body { margin:0; font-family: system-ui,-apple-system, Segoe UI, Roboto, Helvetica, Arial; background:var(--bg); color:var(--text); line-height:1.6; display: flex; }

    /* Layout */
    .sidebar { width: 240px; background: #1f2937; color: #e5e7eb; padding: 24px; height: 100vh; position: fixed; }
    .main-content { margin-left: 240px; width: calc(100% - 240px); padding: 24px; }

    /* Sidebar */
    .sidebar-header { font-size: 1.5rem; font-weight: 700; color: #fff; margin-bottom: 24px; }
    .sidebar-nav a { display: block; color: #d1d5db; text-decoration: none; padding: 12px; border-radius: 8px; margin-bottom: 8px; transition: background 0.2s; }
    .sidebar-nav a.active, .sidebar-nav a:hover { background: #4b5563; color: #fff; }
    .sidebar-footer { position: absolute; bottom: 24px; }

    /* Komponen */
    .header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; }
    .header h1 { margin: 0; font-size: 1.8rem; }
    .btn { display:inline-block; background:var(--primary); color:#fff; border:none; border-radius:8px; padding:10px 14px; text-decoration:none; cursor:pointer; font-weight:600; }
    .btn-danger { background:var(--danger); }
    .btn-success { background:var(--success); }
    .card { background: var(--card); border-radius: var(--radius); padding: 24px; box-shadow: var(--shadow); }

    /* Status Message (untuk 'user created', 'deleted', dll) */
    .status-message {
        padding: 16px;
        background: var(--success);
        color: #fff;
        border-radius: var(--radius);
        margin-bottom: 24px;
        box-shadow: var(--shadow);
    }
    .error-message {
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 16px;
        background-color: #fee2e2;
        color: #b91c1c;
    }

    /* Style Tabel (dari userManagement) */
    .user-table { width: 100%; border-collapse: collapse; }
    .user-table th, .user-table td {
      padding: 12px 16px;
      border-bottom: 1px solid #e5e7eb;
      text-align: left;
    }
    .user-table th { background-color: #f9fafb; font-weight: 600; }
    .user-table .badge { padding: 4px 8px; border-radius: 12px; font-size: 0.8rem; font-weight: 600; }
    .badge.role-owner { background-color: #fef3c7; color: #92400e; }
    .badge.role-admin { background-color: #dbeafe; color: #1e40af; }
    .badge.role-user { background-color: #e5e7eb; color: #374151; }
    .action-buttons a, .action-buttons button { margin-right: 8px; text-decoration: none; padding: 6px 10px; border-radius: 6px; font-weight: 600; }
    .btn-edit { background: #dbeafe; color: #1e40af; }
    .btn-delete { background: #fee2e2; color: #991b1b; border: none; cursor: pointer; }

    /* Style Form (dari userForm) */
    .form-grid { max-width: 700px; }
    .field { margin-bottom: 20px; }
    .field label { display: block; margin-bottom: 8px; font-weight: 600; }
    .input, .select { width: 100%; padding: 12px 16px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 1rem; background: #fff; }
    .input:focus, .select:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2); }
    .note { font-size: 0.9rem; color: var(--muted); margin-top: 8px; }
  </style>
</head>
<body>

  {{-- SIDEBAR INI AKAN SAMA UNTUK SEMUA HALAMAN DASBOR --}}
  <aside class="sidebar">
    {{-- Kita cek role untuk ganti judul panel --}}
    <div class="sidebar-header">
      {{ Auth::user()->role === 'admin' ? 'Admin Panel' : 'Owner Panel' }}
    </div>
    
    <nav class="sidebar-nav">
      {{-- Link Dashboard (Cek role untuk route) --}}
      <a href="{{ Auth::user()->role === 'admin' ? route('admin') : route('owner') }}" 
         class="{{ request()->routeIs('admin', 'owner') ? 'active' : '' }}">
         Dashboard
      </a>
      
      {{-- Link Manajemen User (Cek route 'users.*' untuk 'active') --}}
      <a href="{{ route('users.index') }}" 
         class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
         Manajemen User
      </a>
      
      {{-- Link lain (sesuaikan nanti) --}}
      <a href="#">Laporan</a>
      <a href="#">Pengaturan</a>
      <a href="{{ route('home') }}">Lihat Situs</a>
    </nav>
    
    <div class="sidebar-footer">
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button class="btn btn-danger" style="width:100%;">Logout</button>
      </form>
    </div>
  </aside>

  {{-- KONTEN UTAMA AKAN DIISI OLEH HALAMAN 'CHILD' --}}
  <main class="main-content">
    @yield('content')
  </main>
  
</body>
</html>