@extends('layouts.auth')

@section('title', 'Login')

@section('content')

    <div class="auth-header">
        <h1>LOGIN.</h1>
        <p>Welcome back! Please enter your details.</p>
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
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-input" placeholder="example@email.com" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-input" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn-auth">Sign In</button>
    </form>

   {{-- Tombol Google --}}
    <a href="{{ route('redirect.google') }}" class="btn-google">
        {{-- Panggil google.png dari folder public/img --}}
        <img src="{{ asset('img/google.png') }}" style="width: 20px;" alt="Google Logo">
        Sign in with Google
    </a>

    <div class="auth-footer">
        Don't have an account? <a href="{{ route('register') }}">Create free account</a>
    </div>

@endsection