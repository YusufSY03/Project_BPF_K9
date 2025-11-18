@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')

@section('content')

  <header class="header">
    <div>
      <h1>Dashboard</h1>
      <p style="margin:0; color:var(--muted);">Selamat datang kembali, {{ Auth::user()->name }}!</p>
    </div>
  </header>

  @if (session('status'))
    <div class="status-message">{{ session('status') }}</div>
  @endif

  <div class="card-grid">
    <div class="card">
      <h3 class="card-title">Total Pengguna</h3>
      <p class="card-value">1,250</p>
      <div class="card-change positive">+15.8% dari bulan lalu</div>
    </div>
    {{-- ... sisa card Anda ... --}}
  </div>

  <div class="card" style="margin-top: 24px;">
      <h3 style="margin-top:0;">Aktivitas Terbaru</h3>
      <p class="muted">Tabel atau daftar aktivitas pengguna terbaru akan ditampilkan di sini.</p>
  </div>

@endsection
