@extends('layouts.dashboard')

@section('title', isset($menuItem) ? 'Edit Menu' : 'Tambah Menu Baru')

@section('content')

  <header class="header">
    <div>
      <h1>{{ isset($menuItem) ? 'Edit Menu' : 'Tambah Menu Baru' }}</h1>
    </div>
    <a href="{{ route('owner.menu.index') }}" class="btn" style="background:var(--muted);">&larr; Kembali</a>
  </header>

  <div class="card">
    <div class="form-grid">

      @if ($errors->any())
        <div class="error-message">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      
      {{-- PERHATIKAN: enctype="multipart/form-data" WAJIB ADA untuk upload foto --}}
      <form action="{{ isset($menuItem) ? route('owner.menu.update', $menuItem->id) : route('owner.menu.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        @if(isset($menuItem))
          @method('PUT')
        @endif

        <div class="field">
          <label>Nama Menu</label>
          <input name="name" class="input" value="{{ old('name', $menuItem->name ?? '') }}" required>
        </div>
        
        <div class="field">
          <label>Kategori</label>
          <input name="category" class="input" value="{{ old('category', $menuItem->category ?? '') }}">
        </div>

        <div class="field">
          <label>Harga (Rp)</label>
          <input name="price" class="input" type="number" value="{{ old('price', $menuItem->price ?? '') }}" required>
        </div>

        <div class="field">
          <label>Deskripsi</label>
          <textarea name="description" class="input" rows="3">{{ old('description', $menuItem->description ?? '') }}</textarea>
        </div>
        
        {{-- === BAGIAN UPLOAD FOTO === --}}
        <div class="field">
          <label>Foto Menu</label>
          
          {{-- Preview foto lama jika ada --}}
          @if(isset($menuItem) && $menuItem->image_url)
            <div style="margin-bottom: 10px;">
                <p style="font-size: 0.8rem; color: #666;">Foto Saat Ini:</p>
                <img src="{{ asset('storage/' . $menuItem->image_url) }}" style="width: 100px; border-radius: 8px;">
            </div>
          @endif

          {{-- Tombol Pilih File --}}
          <input name="image" class="input" type="file" accept="image/*">
          <p class="note">Format: JPG, PNG. Kosongkan jika tidak ingin mengubah foto.</p>
        </div>
        {{-- === AKHIR BAGIAN FOTO === --}}

        <div class="field">
          <label>Status</label>
          <select name="availability_status" class="select">
            <option value="available" {{ old('availability_status', $menuItem->availability_status ?? '') == 'available' ? 'selected' : '' }}>Tersedia</option>
            <option value="sold_out" {{ old('availability_status', $menuItem->availability_status ?? '') == 'sold_out' ? 'selected' : '' }}>Habis</option>
          </select>
        </div>

        <div class="field">
            <label style="display:flex; align-items:center; gap:10px;">
                <input name="is_active" type="checkbox" value="1" {{ old('is_active', $menuItem->is_active ?? true) ? 'checked' : '' }}>
                Tampilkan di Website
            </label>
        </div>
        
        <button class="btn btn-success" type="submit">Simpan</button>
      </form>
    </div>
  </div>

@endsection