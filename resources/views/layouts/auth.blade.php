<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title') - Nyamaw</title>
  <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
  
  <style>
    /* === CSS GLOBAL KHUSUS AUTH === */
    :root {
      --primary: #FF4500;
      --dark: #121212;
      --gray: #888;
      --light: #f9f9f9;
      --font-body: 'Poppins', sans-serif;
      --font-head: 'Playfair Display', serif;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: var(--font-body); background: var(--white); color: var(--dark); }

    /* LAYOUT SPLIT SCREEN */
    .auth-container {
        display: grid;
        grid-template-columns: 1fr 1fr; /* Bagi 2 kolom rata */
        min-height: 100vh;
        width: 100%;
        overflow: hidden;
    }

    /* BAGIAN KIRI (GAMBAR) */
    .auth-image {
        /* Gunakan asset() agar path-nya absolut dan akurat */
        background-image: url("{{ asset('img/login-bg.png') }}"); 
        background-size: cover;
        background-position: center;
        position: relative;
        display: flex;
        align-items: flex-end;
        padding: 60px;
        color: white;
    }
    /* Overlay Gelap */
    .auth-image::after {
        content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        background: linear-gradient(to bottom, rgba(0,0,0,0.1), rgba(0,0,0,0.7));
        z-index: 1;
    }
    .auth-quote { position: relative; z-index: 2; max-width: 80%; }
    .auth-quote h2 { font-family: var(--font-head); font-size: 3rem; margin-bottom: 10px; line-height: 1.1; }

    /* BAGIAN KANAN (FORMULIR) */
    .auth-content {
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 60px 100px; /* Padding besar biar lega */
        background: white;
        overflow-y: auto;
    }

    /* TYPOGRAPHY FORM */
    .auth-header { margin-bottom: 40px; }
    .auth-header h1 { font-family: var(--font-head); font-size: 3.5rem; color: var(--dark); margin-bottom: 5px; }
    .auth-header p { color: var(--gray); font-size: 1.1rem; }

    /* INPUT STYLE (GARIS BAWAH / UNDERLINE) */
    .form-group { margin-bottom: 25px; }
    .form-label { display: block; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: var(--dark); margin-bottom: 8px; }
    .form-input {
        width: 100%;
        padding: 12px 0;
        border: none;
        border-bottom: 2px solid #eee;
        font-family: var(--font-body);
        font-size: 1.1rem;
        outline: none;
        transition: 0.3s;
        border-radius: 0;
    }
    .form-input:focus { border-bottom-color: var(--dark); }

    /* TOMBOL */
    .btn-auth {
        width: 100%;
        background: var(--dark);
        color: white;
        padding: 18px;
        font-weight: 600;
        letter-spacing: 2px;
        text-transform: uppercase;
        border: none;
        cursor: pointer;
        margin-top: 10px;
        transition: 0.3s;
    }
    .btn-auth:hover { background: var(--primary); }

    .btn-google {
        display: flex; align-items: center; justify-content: center; gap: 10px;
        width: 100%; padding: 15px; margin-top: 15px;
        background: white; border: 2px solid #eee; color: var(--dark);
        font-weight: 600; text-decoration: none; transition: 0.3s;
    }
    .btn-google:hover { border-color: var(--dark); }

    /* LINK */
    .auth-footer { margin-top: 40px; text-align: center; color: var(--gray); font-size: 0.9rem; }
    .auth-footer a { color: var(--dark); font-weight: 600; text-decoration: underline; }
    .back-link { display: inline-block; margin-bottom: 30px; font-weight: 600; text-decoration: none; color: var(--dark); }

    /* RESPONSIVE (HP) */
    @media (max-width: 900px) {
        .auth-container { grid-template-columns: 1fr; }
        .auth-image { display: none; } /* Sembunyikan gambar di HP */
        .auth-content { padding: 40px 24px; }
        .auth-header h1 { font-size: 2.5rem; }
    }
  </style>
</head>
<body>

  <div class="auth-container">
    {{-- BAGIAN KIRI: GAMBAR (STATIS) --}}
    <div class="auth-image">
        <div class="auth-quote">
            <h2>Nyamaw</h2>
            <p>Masakan rumahan terbaik, langsung ke meja makan Anda.</p>
        </div>
    </div>

    {{-- BAGIAN KANAN: KONTEN DINAMIS --}}
    <div class="auth-content">
        {{-- Tombol Kembali --}}
        <a href="{{ route('home') }}" class="back-link">&larr; Back to Home</a>

        {{-- Tempat Formulir Masuk Di Sini --}}
        @yield('content')
    </div>
  </div>

</body>
</html>