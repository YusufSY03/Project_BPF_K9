@extends('layouts.auth')

@section('title', 'Daftar - Nyamaw')

@section('content')

    <div class="auth-header">
        <h1>GABUNG.</h1>
        <p>Buat akun baru dan mulailah memesan makanan lezat.</p>
    </div>

    @if ($errors->any())
        <div style="color: #e11d48; margin-bottom: 20px; font-weight: 500;">
            <ul style="padding-left: 20px; margin: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register.post') }}">
        @csrf
        
        <div class="form-group">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="name" class="form-input" placeholder="Nama Anda" value="{{ old('name') }}" required autofocus>
        </div>

        <div class="form-group">
            <label class="form-label">Alamat Email</label>
            <input type="email" name="email" class="form-input" placeholder="contoh@email.com" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label class="form-label">Kata Sandi</label>
            <input type="password" name="password" class="form-input" placeholder="Buat kata sandi" required>
        </div>

        <div class="form-group">
            <label class="form-label">Konfirmasi Kata Sandi</label>
            <input type="password" name="password_confirmation" class="form-input" placeholder="Ulangi kata sandi" required>
        </div>

        <button type="submit" class="btn-auth">Buat Akun</button>
    </form>

    <div class="auth-footer">
        Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
    </div>

@endsection