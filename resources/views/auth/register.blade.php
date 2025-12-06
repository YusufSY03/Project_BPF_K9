@extends('layouts.auth')

@section('title', 'Register')

@section('content')

    <div class="auth-header">
        <h1>JOIN US.</h1>
        <p>Create your account and start ordering.</p>
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
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-input" placeholder="John Doe" value="{{ old('name') }}" required autofocus>
        </div>

        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-input" placeholder="example@email.com" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-input" placeholder="Create a password" required>
        </div>

        <div class="form-group">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-input" placeholder="Repeat password" required>
        </div>

        <button type="submit" class="btn-auth">Create Account</button>
    </form>

    <div class="auth-footer">
        Already have an account? <a href="{{ route('login') }}">Login Here</a>
    </div>

@endsection