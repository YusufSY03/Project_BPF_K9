@extends('layouts.dashboard')

@section('title', 'Manajemen User')

@section('content')

  <header class="header">
    <div>
      <h1>Manajemen User</h1>
      <p style="margin:0; color:var(--muted);">Kelola semua user yang terdaftar di sistem.</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-success">+ Tambah User Baru</a>
  </header>

  @if (session('status'))
    <div class="status-message">
      {{ session('status') }}
    </div>
  @endif

  @if ($errors->any())
    <div class="error-message">
        {{ $errors->first() }}
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
              <span class="badge role-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
            </td>
            <td class="action-buttons">
              <a href="{{ route('users.edit', $user->id) }}" class="btn-edit">Edit</a>
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

@endsection
