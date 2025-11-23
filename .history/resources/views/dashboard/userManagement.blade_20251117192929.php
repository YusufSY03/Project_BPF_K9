<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manajemen User - Dashboard Owner</title>
  <style>
    /* CSS ini SAMA PERSIS dengan ownerDashboard.blade.php 
       Kita tambahkan sedikit style untuk Tabel
    */
    :root {
      --bg: #f3f4f6; --card: #ffffff; --text: #111827; --muted: #6b7280;
      --primary: #3b82f6; --danger: #ef4444; --success: #10b981;
      --radius: 10px; --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
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
    .btn-success { background:var(--success); }

    .card { background: var(--card); border-radius: var(--radius); padding: 24px; box-shadow: var(--shadow); }
    
    /* == STYLE TABEL BARU == */
    .user-table { width: 100%; border-collapse: collapse; }
    .user-table th, .user-table td {
      padding: 12px 16px;
      border-bottom: 1px solid #e5e7eb;
      text-align: left;
    }
    .user-table th { background-color: #f9fafb; font-weight: 600; }
    .user-table .badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    .badge.role-owner { background-color: #fef3c7; color: #92400e; }
    .badge.role-admin { background-color: #dbeafe; color: #1e40af; }
    .badge.role-user { background-color: #e5e7eb; color: #374151; }
    .action-buttons a, .action-buttons button {
        margin-right: 8px;
        text-decoration: none;
        padding: 6px 10px;
        border-radius: 6px;
        font-weight: 600;
    }
    .btn-edit { background: #dbeafe; color: #1e40af; }
    .btn-delete { background: #fee2e2; color: #991b1b; border: none; cursor: pointer; }
    /* == AKHIR STYLE TABEL == */
  </style>
</head>
<body>

  <aside class="sidebar">
    <div class="sidebar-header">l</div>
    <nav class="sidebar-nav">
      {{-- Link Dashboard kembali ke route 'owner' --}}
      <a href="{{ route('owner') }}">Dashboard</a> 
      {{-- Link ini sekarang 'active' --}}
      <a href="{{ route('users.index') }}" class="active">Manajemen User</a>
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
        <h1>Manajemen User</h1>
        <p style="margin:0; color:var(--muted);">Kelola semua user yang terdaftar di sistem.</p>
      </div>
      {{-- Nanti di Langkah 7.B, kita akan buat link ini berfungsi --}}
      <a href="{{ route('users.create') }}" class="btn btn-success">+ Tambah User Baru</a>
    </header>
    {{-- Tampilkan pesan sukses (dari store atau update) --}}
@if (session('status'))
  <div class.="status-message" style="padding: 16px; background: var(--success); color: #fff; border-radius: var(--radius); margin-bottom: 24px; box-shadow: var(--shadow);">
    {{ session('status') }}
  </div>
@endif
    <div class="card">
      <table class="user-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($users as $user)
            <tr>
              <td>{{ $user->id }}</td>
              <td>{{ $user->name }}</td>
              <td>{{ $user->email }}</td>
              <td>
                {{-- Badge (penanda) untuk role --}}
                <span class="badge role-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
              </td>
              <td class="action-buttons">
                {{-- Nanti di Langkah 7.C & 7.D, tombol ini akan berfungsi --}}
                <a href="{{ route('users.edit', $user->id) }}" class="btn-edit">Edit</a>
                {{-- Arahkan action ke route 'destroy' dan kirim $user->id --}}
                  <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini? Data tidak bisa dikembalikan.');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn-delete">Hapus</button>
                  </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" style="text-align: center; padding: 24px;">Belum ada data user.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

  </main>
</body>
</html>