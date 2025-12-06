@extends('layouts.dashboard')

@section('title', isset($user) ? 'Edit User' : 'Tambah User Baru')

@section('content')

<style>
    /* KONTAINER FORM */
    .form-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        border: 1px solid #f0f0f0;
        padding: 40px;
        max-width: 800px;
        margin-top: 20px;
    }

    /* HEADER */
    .page-header {
        display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;
    }
    .page-title {
        font-family: var(--font-head); font-size: 2rem; font-weight: 800; color: var(--dark-blue); margin: 0;
    }
    
    /* INPUT */
    .form-group { margin-bottom: 25px; }
    .form-label {
        display: block; font-weight: 600; font-size: 0.9rem; margin-bottom: 8px; color: #475569;
    }
    .form-input, .form-select {
        width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0;
        border-radius: 8px; font-size: 1rem; font-family: var(--font-body);
        transition: 0.3s; outline: none; background: #f8fafc;
    }
    .form-input:focus, .form-select:focus {
        /* PERBAIKAN: Gunakan var(--primary) bukan var(--orange) */
        border-color: var(--primary); 
        background: white;
        box-shadow: 0 0 0 3px rgba(255, 69, 0, 0.1);
    }

    /* TOMBOL (PERBAIKAN WARNA) */
    .btn-submit {
        /* PERBAIKAN: Gunakan var(--primary) agar tombol langsung muncul */
        background: var(--primary); 
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: 0.2s;
    }
    .btn-submit:hover { background: #e53e00; transform: translateY(-2px); }

    .btn-back {
        background: #f1f5f9; color: #64748b; padding: 10px 20px;
        border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.9rem; transition: 0.2s;
    }
    .btn-back:hover { background: #e2e8f0; color: #334155; }
    
    .error-list {
        background: #fef2f2; border: 1px solid #fee2e2; color: #b91c1c; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem;
    }
</style>

<div>
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ isset($user) ? 'Edit Pengguna' : 'Tambah User Baru' }}</h1>
            <p style="color:#64748b; margin-top:5px;">Isi formulir di bawah untuk {{ isset($user) ? 'memperbarui data' : 'mendaftarkan' }} pengguna.</p>
        </div>
        <a href="{{ route('users.index') }}" class="btn-back">← Kembali</a>
    </div>

    <div class="form-container">
        
        @if ($errors->any())
            <div class="error-list">
                <ul style="margin:0; padding-left:20px;">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}" method="POST">
            @csrf
            @if(isset($user)) @method('PUT') @endif

            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-input" value="{{ old('name', $user->name ?? '') }}" required placeholder="Contoh: Budi Santoso">
            </div>

            <div class="form-group">
                <label class="form-label">Alamat Email</label>
                <input type="email" name="email" class="form-input" value="{{ old('email', $user->email ?? '') }}" required placeholder="nama@email.com">
            </div>

            <div class="form-group">
                <label class="form-label">Role (Peran)</label>
                <select name="role" class="form-select" required>
                    <option value="user" {{ old('role', $user->role ?? '') == 'user' ? 'selected' : '' }}>User (Pelanggan)</option>
                    <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>Admin (Pengelola)</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Password {{ isset($user) ? '(Kosongkan jika tidak ingin mengubah)' : '' }}</label>
                <input type="password" name="password" class="form-input" {{ isset($user) ? '' : 'required' }} placeholder="••••••••">
            </div>

            <div class="form-group">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-input" placeholder="••••••••">
            </div>

            <div style="margin-top: 30px;">
                <button type="submit" class="btn-submit">
                    {{ isset($user) ? 'Simpan Perubahan' : 'Simpan User Baru' }}
                </button>
            </div>

        </form>
    </div>
</div>

@endsection