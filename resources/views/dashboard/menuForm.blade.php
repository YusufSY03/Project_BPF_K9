@extends('layouts.dashboard')

@section('title', isset($menuItem) ? 'Edit Menu' : 'Tambah Menu Baru')

@section('content')

<style>
    /* CSS FORM SAMA DENGAN USER FORM (KONSISTENSI) */
    .form-container {
        background: white; border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        border: 1px solid #f0f0f0; padding: 40px;
        max-width: 800px; margin-top: 20px;
    }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
    .page-title { font-family: var(--font-head); font-size: 2rem; font-weight: 800; color: var(--dark-blue); margin: 0; }
    .form-group { margin-bottom: 25px; }
    .form-label { display: block; font-weight: 600; font-size: 0.9rem; margin-bottom: 8px; color: #475569; }
    .form-input, .form-select, .form-textarea {
        width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0;
        border-radius: 8px; font-size: 1rem; font-family: var(--font-body);
        transition: 0.3s; outline: none; background: #f8fafc;
    }
    .form-input:focus, .form-select:focus, .form-textarea:focus {
        border-color: var(--orange); background: white; box-shadow: 0 0 0 3px rgba(255, 69, 0, 0.1);
    }
    .btn-submit {
        background: var(--primary); color: white; padding: 12px 30px;
        border: none; border-radius: 8px; font-weight: 700; font-size: 1rem;
        cursor: pointer; transition: 0.2s;
    }
    .btn-submit:hover { background: #e53e00; transform: translateY(-2px); }
    .btn-back {
        background: #f1f5f9; color: #64748b; padding: 10px 20px;
        border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.9rem;
        transition: 0.2s;
    }
    .btn-back:hover { background: #e2e8f0; color: #334155; }
    .error-list { background: #fef2f2; border: 1px solid #fee2e2; color: #b91c1c; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem; }
    
    /* PREVIEW GAMBAR */
    .img-preview-box {
        width: 150px; height: 150px; border-radius: 12px;
        overflow: hidden; border: 2px dashed #cbd5e1;
        display: flex; align-items: center; justify-content: center;
        background: #f8fafc; margin-bottom: 10px;
    }
    .img-preview-box img { width: 100%; height: 100%; object-fit: cover; }
    .img-placeholder { color: #94a3b8; font-size: 0.8rem; text-align: center; padding: 10px; }
</style>

<div>
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ isset($menuItem) ? 'Edit Menu' : 'Tambah Menu Baru' }}</h1>
            <p style="color:#64748b; margin-top:5px;">Tambahkan menu lezat baru ke dalam daftar.</p>
        </div>
        <a href="{{ route('owner.menu.index') }}" class="btn-back">← Kembali</a>
    </div>

    <div class="form-container">
        
        @if ($errors->any())
            <div class="error-list">
                <ul style="margin:0; padding-left:20px;">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ isset($menuItem) ? route('owner.menu.update', $menuItem->id) : route('owner.menu.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($menuItem)) @method('PUT') @endif

            <div class="form-group">
                <label class="form-label">Nama Menu</label>
                <input type="text" name="name" class="form-input" value="{{ old('name', $menuItem->name ?? '') }}" required placeholder="Contoh: Ayam Geprek Spesial">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="category" class="form-input" value="{{ old('category', $menuItem->category ?? '') }}" placeholder="Makanan / Minuman">
                </div>
                <div class="form-group">
                    <label class="form-label">Harga (Rp)</label>
                    <input type="number" name="price" class="form-input" value="{{ old('price', $menuItem->price ?? '') }}" required placeholder="15000">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi Singkat</label>
                <textarea name="description" class="form-textarea" rows="4" placeholder="Jelaskan rasa dan bahan utamanya...">{{ old('description', $menuItem->description ?? '') }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Foto Menu</label>
                
                {{-- PREVIEW GAMBAR --}}
                <div class="img-preview-box">
                    @if(isset($menuItem) && $menuItem->image_url)
                        <img src="{{ asset('storage/' . $menuItem->image_url) }}" alt="Preview">
                    @else
                        <div class="img-placeholder">Belum ada foto</div>
                    @endif
                </div>

                <input type="file" name="image" class="form-input" accept="image/*">
                <p style="font-size: 0.8rem; color: #94a3b8; margin-top: 5px;">Format: JPG, PNG. Maksimal 2MB.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Status Ketersediaan</label>
                <select name="availability_status" class="form-select" required>
                    <option value="available" {{ old('availability_status', $menuItem->availability_status ?? '') == 'available' ? 'selected' : '' }}>✅ Tersedia (Available)</option>
                    <option value="sold_out" {{ old('availability_status', $menuItem->availability_status ?? '') == 'sold_out' ? 'selected' : '' }}>🚫 Habis (Sold Out)</option>
                </select>
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 10px;">
                <input type="checkbox" name="is_active" id="is_active" value="1" 
                       {{ old('is_active', $menuItem->is_active ?? true) ? 'checked' : '' }} 
                       style="width: 20px; height: 20px; cursor: pointer;">
                <label for="is_active" style="margin:0; cursor: pointer; color: var(--dark-blue); font-weight: 500;">
                    Tampilkan menu ini di website publik?
                </label>
            </div>
            
            <div style="margin-top: 30px;">
                <button type="submit" class="btn-submit">
                    {{ isset($menuItem) ? 'Simpan Perubahan' : 'Simpan Menu' }}
                </button>
            </div>

        </form>
    </div>
</div>

@endsection