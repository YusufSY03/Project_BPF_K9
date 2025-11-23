@extends('layouts.dashboard')

{{-- Judulnya dinamis --}}
@section('title', isset($user) ? 'Edit User' : 'Tambah User Baru')

@section('content')

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
      
      <form action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}" method="POST">
        @csrf
        
        @if(isset($user))
          @method('PUT')
        @endif

        <div class="field">
          <label for="name">Nama Lengkap</label>
          <input id="name" name="name" class="input" value="{{ old('name', $user->name ?? '') }}" required>
        </div>
        <div class="field">
          <label for="email">Alamat Email</label>
          <input id="email" name="email" class="input" type="email" value="{{ old('email', $user->email ?? '') }}" required>
        </div>
        <div class="field">
          <label for="role">Role</label>
          <select id="role" name="role" class="select" required>
            <option value="user" {{ old('role', $user->role ?? '') == 'user' ? 'selected' : '' }}>User</option>
            <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
          </select>
        </div>
        
        <div class="field">
          <label for="password">Password</label>
          <input id="password" name="password" class="input" type="password" {{ isset($user) ? '' : 'required' }}>
          @if(isset($user))
            <p class="note">Kosongkan jika tidak ingin mengubah password.</p>
          @endif
        </div>
        <div class="field">
          <label for="password_confirmation">Konfirmasi Password</label>
          <input id="password_confirmation" name="password_confirmation" class="input" type="password">
        </div>
        
        <button class="btn btn-success" type="submit">
          {{ isset($user) ? 'Simpan Perubahan' : 'Simpan User Baru' }}
        </button>
      </form>
    </div>
  </div>

@endsection