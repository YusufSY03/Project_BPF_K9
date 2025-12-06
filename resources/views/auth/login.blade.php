@extends('layouts.auth')

@section('title', 'Masuk - Nyamaw')

@section('content')

    <div class="auth-header">
        <h1>MASUK.</h1>
        <p>Selamat datang kembali! Silakan masukkan detail Anda.</p>
    </div>

    {{-- Pesan Error --}}
    @if ($errors->any())
        <div style="color: #e11d48; margin-bottom: 20px; font-weight: 500;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        
        <div class="form-group">
            <label class="form-label">Alamat Email</label>
            <input type="email" name="email" class="form-input" placeholder="contoh@email.com" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="form-group">
            <label class="form-label">Kata Sandi</label>
            <input type="password" name="password" class="form-input" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn-auth">Masuk</button>
    </form>

    {{-- Tombol Google --}}
    <a href="{{ route('redirect.google') }}" class="btn-google">
        <img src="{{ asset('img/google.png') }}" style="width: 20px;" alt="Logo Google">
        Masuk dengan Google
    </a>

    <div class="auth-footer">
        Belum punya akun? <a href="{{ route('register') }}">Daftar Gratis</a>
    </div>

@endsection