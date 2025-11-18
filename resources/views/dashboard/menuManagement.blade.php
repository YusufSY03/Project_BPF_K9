@extends('layouts.dashboard')

@section('title', 'Manajemen Menu')

@section('content')

  <header class="header">
    <div>
      <h1>Manajemen Menu</h1>
      <p style="margin:0; color:var(--muted);">Kelola semua menu yang dijual di Nyamaw.</p>
    </div>
    <a href="{{ route('owner.menu.create') }}" class="btn btn-success">+ Tambah Menu Baru</a>
  </header>

  {{-- Tampilkan pesan sukses (dari store, update, delete) --}}
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
    <table class="user-table"> {{-- Kita pakai style .user-table yang sudah ada --}}
      <thead>
        <tr>
          <th>ID</th>
          <th>Nama Menu</th>
          <th>Kategori</th>
          <th>Harga</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($menuItems as $item)
          <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->category }}</td>
            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
            <td>
              @if ($item->is_active)
                <span class="badge" style="background-color: #dcfce7; color: #166534;">Aktif</span>
              @else
                <span class="badge" style="background-color: #fee2e2; color: #991b1b;">Nonaktif</span>
              @endif
            </td>
            <td class="action-buttons">

              {{-- ==== PERBAIKAN DI SINI ==== --}}
              <a href="{{ route('owner.menu.edit', $item->id) }}" class="btn-edit">Edit</a>

              {{-- ==== DAN PERBAIKAN DI SINI ==== --}}
              <form action="{{ route('owner.menu.destroy', $item->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus menu ini?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn-delete">Hapus</button>
              </form>

            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" style="text-align: center; padding: 24px;">Belum ada data menu.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

@endsection
