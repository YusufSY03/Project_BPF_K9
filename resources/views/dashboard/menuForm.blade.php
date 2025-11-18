@extends('layouts.dashboard')

{{-- Judulnya dinamis --}}
@section('title', isset($menuItem) ? 'Edit Menu' : 'Tambah Menu Baru')

@section('content')

  <header class="header">
    <div>
      <h1>{{ isset($menuItem) ? 'Edit Menu' : 'Tambah Menu Baru' }}</h1>
    </div>
    <a href="{{ route('owner.menu.index') }}" class="btn" style="background:var(--muted);">&larr; Kembali ke Daftar Menu</a>
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

      {{-- Tentukan route action: jika ada $menuItem, arahkan ke 'update', jika tidak, ke 'store' --}}
      <form action="{{ isset($menuItem) ? route('owner.menu.update', $menuItem->id) : route('owner.menu.store') }}" method="POST">
        @csrf

        {{-- Jika ini mode edit, tambahkan method PUT --}}
        @if(isset($menuItem))
          @method('PUT')
        @endif

        <div class="field">
          <label for="name">Nama Menu</label>
          {{-- Isi 'value' dengan data lama jika ada --}}
          <input id="name" name="name" class="input" value="{{ old('name', $menuItem->name ?? '') }}" required>
        </div>

        <div class="field">
          <label for="category">Kategori</label>
          <input id="category" name="category" class="input" value="{{ old('category', $menuItem->category ?? '') }}" placeholder="Contoh: Makanan Berat, Minuman, Snack">
        </div>

        <div class="field">
          <label for="price">Harga (Rp)</label>
          <input id="price" name="price" class="input" type="number" step="100" value="{{ old('price', $menuItem->price ?? '') }}" required placeholder="Contoh: 15000">
        </div>

        <div class="field">
          <label for="description">Deskripsi Singkat</label>
          <textarea id="description" name="description" class="input" rows="4">{{ old('description', $menuItem->description ?? '') }}</textarea>
        </div>

        <div class="field">
          <label for="image_url">URL Gambar</label>
          <input id="image_url" name="image_url" class="input" type="text" value="{{ old('image_url', $menuItem->image_url ?? '') }}" placeholder="Contoh: https://.../gambar.jpg">
          <p class="note">Untuk saat ini, kita masukkan link gambar dari internet.</p>
        </div>

        <div class="field">
          <label for="availability_status">Status Ketersediaan</label>
          <select id="availability_status" name="availability_status" class="select" required>
            <option value="available" {{ old('availability_status', $menuItem->availability_status ?? '') == 'available' ? 'selected' : '' }}>Tersedia (Available)</option>
            <option value="sold_out" {{ old('availability_status', $menuItem->availability_status ?? '') == 'sold_out' ? 'selected' : '' }}>Habis (Sold Out)</option>
          </select>
        </div>

        <div class="field">
            <label for="is_active" style="display:inline-flex; align-items: center; gap: 10px;">
                {{-- Cek 'checked' berdasarkan data lama --}}
                <input id="is_active" name="is_active" type="checkbox" value="1"
                       {{ old('is_active', $menuItem->is_active ?? true) ? 'checked' : '' }}
                       style="width: 20px; height: 20px;">
                Aktifkan menu ini (tampilkan di website)
            </label>
        </div>

        {{-- Teks tombol berubah --}}
        <button class="btn btn-success" type="submit">
          {{ isset($menuItem) ? 'Simpan Perubahan' : 'Simpan Menu Baru' }}
        </button>
      </form>
    </div>
  </div>

@endsection
