@extends('layouts.app')

@section('title', 'Tentang Kami - Nyamaw')

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
        font-size: 3.5rem;
        color: var(--dark);
        margin-bottom: 10px;
        line-height: 1.2;
    }
    .page-subtitle { color: var(--gray); font-size: 1.1rem; }

    /* ISI ABOUT */
    .about-content { padding: 60px 0; }
    .about-grid { 
        display: grid; 
        grid-template-columns: 1fr 1fr; 
        gap: 50px; 
        align-items: center; 
    }
    
    .about-text h2 { font-size: 2.5rem; margin-bottom: 20px; line-height: 1.2; }
    .about-text p { color: #666; font-size: 1.1rem; line-height: 1.8; margin-bottom: 20px; }
    
    .about-image img { 
        width: 100%; 
        border-radius: 20px; 
        box-shadow: 0 20px 50px rgba(0,0,0,0.1); 
        object-fit: cover;
        height: 500px;
    }

    /* RESPONSIF */
    @media (max-width: 900px) {
        .about-grid { grid-template-columns: 1fr; }
        .about-image img { height: 300px; margin-top: 30px; }
    }
</style>
@endsection

@section('content')

    {{-- SPACER KHUSUS: Agar judul turun ke bawah --}}
    <div style="height: 120px; width: 100%; background: var(--light);"></div>

    {{-- HEADER --}}
    <section class="page-header">
      <div class="container">
        <h1 class="page-title">Tentang Kami</h1>
        <p class="page-subtitle">Cerita singkat di balik lezatnya setiap hidangan Nyamaw.</p>
      </div>
    </section>

    <section class="about-content">
      <div class="container">
        <div class="about-grid">
          
          {{-- TEKS CERITA --}}
          <div class="about-text">
            <h2>Dari Dapur Rumah, <br>Untuk Anda.</h2>
            <p><strong>Nyamaw</strong> lahir dari kecintaan kami pada resep masakan rumahan yang otentik dan berkualitas. Kami percaya bahwa makanan enak tidak harus rumit atau mahal. Setiap hidangan yang kami sajikan adalah hasil dari proses memasak yang penuh cinta.</p>
            <p>Visi kami adalah menjadi teman setia di setiap momen makan Anda, menghadirkan kehangatan dan kebahagiaan melalui cita rasa yang selalu dirindukan.</p>
          </div>

          {{-- GAMBAR (DIPERBAIKI) --}}
          <div class="about-image">
            {{-- Kita gunakan gambar yang pasti ada (login-bg.jpg) --}}
            <img src="{{ asset('img/bg-home.png') }}" alt="Tim Dapur Nyamaw">
          </div>

        </div>
      </div>
    </section>

@endsection