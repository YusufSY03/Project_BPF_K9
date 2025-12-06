@extends('layouts.app')

@section('title', 'Beri Ulasan - Nyamaw')

@section('custom-css')
<style>
    /* HEADER HALAMAN */
    .page-header {
        text-align: center;
        padding-bottom: 40px;
        background-color: var(--light);
    }
    .page-title {
        font-family: var(--font-head);
        font-size: 3rem;
        color: var(--dark);
        margin-bottom: 10px;
        line-height: 1.2;
    }
    .page-subtitle { color: var(--gray); font-size: 1.1rem; }

    /* REVIEW FORM */
    .review-section {
        padding: 60px 0 100px;
        display: flex;
        justify-content: center;
    }
    .review-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border: 1px solid #eee;
        padding: 40px;
        width: 100%;
        max-width: 600px;
        text-align: center;
    }

    /* FORM ELEMENTS */
    .field { margin-bottom: 25px; text-align: left; }
    .field label { display: block; margin-bottom: 10px; font-weight: 700; color: var(--dark); }
    
    .input-select, .input-textarea {
        width: 100%; padding: 15px;
        border: 1px solid #ddd; border-radius: 8px;
        font-family: var(--font-body); font-size: 1rem;
        outline: none; transition: 0.3s;
    }
    .input-select:focus, .input-textarea:focus { border-color: var(--primary); }

    /* TOMBOL */
    .btn-submit {
        width: 100%; background: var(--primary); color: white;
        padding: 15px; border-radius: 8px; font-weight: 700;
        text-transform: uppercase; border: none; cursor: pointer;
        margin-top: 10px; transition: 0.3s;
    }
    .btn-submit:hover { background: #e55337; transform: translateY(-2px); }

    .btn-back {
        display: block; margin-top: 20px;
        color: var(--gray); text-decoration: underline;
        font-size: 0.9rem;
    }
    .btn-back:hover { color: var(--dark); }
</style>
@endsection

@section('content')

    {{-- SPACER (Agar tidak ketimpa navbar) --}}
    <div style="height: 120px; width: 100%; background: var(--light);"></div>

    {{-- HEADER --}}
    <section class="page-header">
      <div class="container">
        <h1 class="page-title">Beri Ulasan</h1>
        <p class="page-subtitle">Bagikan pengalaman makan Anda untuk pesanan <strong>#{{ $order->id }}</strong></p>
      </div>
    </section>

    {{-- FORMULIR REVIEW --}}
    <section class="review-section container">
        <div class="review-card">
            
            <form action="{{ route('reviews.store', $order->id) }}" method="POST">
                @csrf
                
                <div class="field">
                    <label>Berikan Rating Bintang</label>
                    <select name="rating_stars" class="input-select" required>
                        <option value="5">⭐⭐⭐⭐⭐ (Sangat Puas)</option>
                        <option value="4">⭐⭐⭐⭐ (Puas)</option>
                        <option value="3">⭐⭐⭐ (Biasa Saja)</option>
                        <option value="2">⭐⭐ (Kurang)</option>
                        <option value="1">⭐ (Kecewa)</option>
                    </select>
                </div>

                <div class="field">
                    <label>Tulis Komentar Anda (Opsional)</label>
                    <textarea name="comment" class="input-textarea" rows="5" placeholder="Ceritakan rasa makanannya, pengirimannya, atau pelayanan kami..."></textarea>
                </div>

                <button type="submit" class="btn-submit">Kirim Ulasan Saya</button>
            </form>

            <a href="{{ route('orders.history') }}" class="btn-back">Kembali ke Riwayat</a>
        </div>
    </section>

@endsection