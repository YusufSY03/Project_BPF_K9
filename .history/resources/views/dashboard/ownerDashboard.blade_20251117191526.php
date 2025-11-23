<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Owner</title>
  <style>
    :root {
      --bg: #f3f4f6;
      --card: #ffffff;
      --text: #111827;
      --muted: #6b7280;
      --primary: #3b82f6;
      --danger: #ef4444;
      --success: #10b981;
      --radius: 10px;
      --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    }
    * { box-sizing: border-box; }
    body { margin:0; font-family: system-ui,-apple-system, Segoe UI, Roboto, Helvetica, Arial; background:var(--bg); color:var(--text); line-height:1.6; display: flex; }

    .sidebar { width: 240px; background: #1f2937; color: #e5e7eb; padding: 24px; height: 100vh; position: fixed; }
    .main-content { margin-left: 240px; width: calc(100% - 240px); padding: 24px; }

    .sidebar-header { font-size: 1.5rem; font-weight: 700; color: #fff; margin-bottom: 24px; }
    .sidebar-nav a { display: block; color: #d1d5db; text-decoration: none; padding: 12px; border-radius: 8px; margin-bottom: 8px; transition: background 0.2s; }
    .sidebar-nav a.active, .sidebar-nav a:hover { background: #4b5563; color: #fff; }
    .sidebar-footer { position: absolute; bottom: 24px; }

    .header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; }
    .header h1 { margin: 0; font-size: 1.8rem; }
    .btn { display:inline-block; background:var(--primary); color:#fff; border:none; border-radius:8px; padding:10px 14px; text-decoration:none; cursor:pointer; font-weight:600; }
    .btn-danger { background:var(--danger); }

    .card-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px; }
    .card { background: var(--card); border-radius: var(--radius); padding: 24px; box-shadow: var(--shadow); }
    .card-title { font-size: 1rem; color: var(--muted); margin: 0 0 8px 0; }
    .card-value { font-size: 2.25rem; font-weight: 700; margin: 0; }
    .card-change { margin-top: 16px; display: flex; align-items: center; }
    .card-change.positive { color: var(--success); }
    .card-change.negative { color: var(--danger); }

    .status-message { padding: 16px; background: var(--success); color: #fff; border-radius: var(--radius); margin-bottom: 24px; box-shadow: var(--shadow); }
  </style>
</head>
<body>

  <aside class="sidebar">
    <div class="sidebar-header">Owner Panel</div>
    <nav class="sidebar-nav">
      <a href="{{ route('owner') }}" class="active">Dashboard</a>
      <a href="{{ route('users.index') }}">Manajemen User</a>
      <a href="#">Laporan Keuangan</a>
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

  <main class="main-content">
    <header class="header">
      <div>
        <h1>Dashboard</h1>
        <p style="margin:0; color:var(--muted);">Selamat datang kembali, {{ session('user_name') ?? 'Owner' }}!</p>
      </div>
    </header>

    @if (session('status'))
      <div class="status-message">{{ session('status') }}</div>
    @endif

    <div class="card-grid">
      <div class="card">
        <h3 class="card-title">Total Pengguna</h3>
        <p class="card-value">1,250</p>
        <div class="card-change positive">+15.8% dari bulan lalu</div>
      </div>
      <div class="card">
        <h3 class="card-title">Pendapatan</h3>
        <p class="card-value">Rp 12.5M</p>
        <div class="card-change positive">+8.2% dari bulan lalu</div>
      </div>
      <div class="card">
        <h3 class="card-title">Pesanan Baru</h3>
        <p class="card-value">340</p>
        <div class="card-change negative">-2.1% dari bulan lalu</div>
      </div>
      <div class="card">
        <h3 class="card-title">Pengunjung Online</h3>
        <p class="card-value">78</p>
      </div>
    </div>

    <div class="card" style="margin-top: 24px;">
        <h3 style="margin-top:0;">Aktivitas Terbaru</h3>
        <p class="muted">Tabel atau daftar aktivitas pengguna terbaru akan ditampilkan di sini.</p>
    </div>
  </main>
</body>
</html>
