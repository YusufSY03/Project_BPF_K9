<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  {{-- Judulnya dinamis: Cek apakah variabel $user ada --}}
  <title>{{ isset($user) ? 'Edit' : 'Tambah' }} User - Dashboard Owner</title>
  <style>
    /* ... CSS Anda (sama persis, tidak perlu diubah) ... */
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
    .form-grid { max-width: 700px; }
    .field { margin-bottom: 20px; }
    .field label { display: block; margin-bottom: 8px; font-weight: 600; }
    .input, .select {
      width: 100%;
      padding: 12px 16px;
      border: 1px solid #e5e7eb;
      border-radius: 8px;
      font-size: 1rem;
      background: #fff;
    }
    .input:focus, .select:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
    }
    .error-message {
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 16px;
        background-color: #fee2e2;
        color: #b91c1c;
    }
    .note { font-size: 0.9rem; color: var(--muted); margin-top: 8px; }
  </style>
</head>
<body>

  <aside class="sidebar">
    {{-- ... Sidebar Anda (sama persis, tidak perlu diubah) ... --}}
    <div class="sidebar-header">Owner Panel</div>
    <nav class="sidebar-nav">
      <a href="{{ route('owner') }}">Dashboard</a>
      <a href="{{ route('owner.users.index') }}" class="active">Manajemen User</a>
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
        <h1>{{ isset($user) ? 'Edit User' : 'Tambah User Baru' }}</h1>
      </div>
      <a href="{{ route('users.index') }}" class="btn" style="background:var(--muted);">&larr; Kembali ke Daftar User</a>
    </header>

    <div class="card">
      <div class="form-grid">

        @if ($errors->any())
          <div class="error-message">
            <ul style="margin:0; padding-left: 20px;">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
        
        {{-- ==== PERUBAHAN BESAR DI SINI ==== --}}
        
        {{-- Tentukan route action: jika ada $user, arahkan ke 'update', jika tidak, ke 'store' --}}
        <form action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}" method="POST">
          @csrf
          
          {{-- Jika ini mode edit, tambahkan method PUT --}}
          @if(isset($user))
            @method('PUT')
          @endif

          <div class="field">
            <label for="name">Nama Lengkap</label>
            {{-- Isi 'value' dengan data lama jika ada --}}
            <input id="name" name="name" class="input" value="{{ old('name', $user->name ?? '') }}" required>
          </div>
          <div class="field">
            <label for="email">Alamat Email</label>
            <input id="email" name="email" class="input" type="email" value="{{ old('email', $user->email ?? '') }}" required>
          </div>
          <div class="field">
            <label for="role">Role</label>
            <select id="role" name="role" class="select" required>
              {{-- Pilih 'selected' berdasarkan data lama jika ada --}}
              <option value="user" {{ old('role', $user->role ?? '') == 'user' ? 'selected' : '' }}>User</option>
              <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
          </div>
          
          {{-- Bagian Password sekarang opsional saat edit --}}
          <div class="field">
            <label for="password">Password</label>
            <input id="password" name="password" class="input" type="password" {{-- 'required' dihapus untuk mode edit --}}>
            @if(isset($user))
              <p class="note">Kosongkan jika tidak ingin mengubah password.</p>
            @endif
          </div>
          <div class="field">
            <label for="password_confirmation">Konfirmasi Password</label>
            <input id="password_confirmation" name="password_confirmation" class="input" type="password">
          </div>
          
          {{-- Teks tombol berubah --}}
          <button class="btn btn-success" type="submit">
            {{ isset($user) ? 'Simpan Perubahan' : 'Simpan User Baru' }}
          </button>
        </form>
        
        {{-- ==== AKHIR PERUBAHAN ==== --}}
      </div>
    </div>

  </main>
</body>
</html>