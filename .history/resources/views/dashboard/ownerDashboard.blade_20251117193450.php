@extends('layouts.dashboard')

{{-- Tentukan judul halaman --}}
@section('title', 'Dashboard Owner')

{{-- Ini adalah konten utama halaman --}}
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
    <div class="card">
      <h3 class="card-title">Pendapatan</h3>
      <p class="card-value">Rp 12.5M</p>
      <div class="card-change positive">+8.2% dari bulan lalu</div>
    </div>
    <div class="card">
      <h3 class="card-title">Pesanan Baru</h3>
      <p class="card-value">340</p>
      <div class="card-change negative">-2.1% dari bulan lalu</div>
    </div>
    <div class="card">
      <h3 class="card-title">Pengunjung Online</h3>
      <p class="card-value">78</p>
    </div>
  </div>

  <div class="card" style="margin-top: 24px;">
      <h3 style="margin-top:0;">Aktivitas Terbaru</h3>
      <p class="muted">Tabel atau daftar aktivitas pengguna terbaru akan ditampilkan di sini.</p>
  </div>

@endsection