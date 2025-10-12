<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tentang Nyamaw</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

  <style>
    /* CSS di sini sama persis dengan halaman home.blade.php untuk konsistensi */
    :root {
      --primary-color: #FF6347;
      --secondary-color: #333333;
      --bg-color: #fdfaf8;
      --text-color: #4a4a4a;
      --card-bg: #ffffff;
      --font-primary: 'Poppins', sans-serif;
      --font-secondary: 'Playfair Display', serif;
      --shadow: 0 10px 20px rgba(0,0,0,0.05);
      --radius: 12px;
    }
    .navbar-auth {
    display: flex;
     align-items: center;
    gap: 16px;
}
.welcome-text {
  font-weight: 600;
  color: var(--text-color);
}
    * { box-sizing: border-box; }
    body { margin: 0; font-family: var(--font-primary); background: var(--bg-color); color: var(--text-color); line-height: 1.7; }
    .container { max-width: 1100px; margin: 0 auto; padding: 0 24px; }
    h1, h2, h3 { font-family: var(--font-secondary); color: var(--secondary-color); }

    .navbar { background: rgba(255,255,255,0.8); backdrop-filter: blur(10px); padding: 16px 0; border-bottom: 1px solid #eee; position: sticky; top: 0; z-index: 100; }
    .navbar .container { display: flex; justify-content: space-between; align-items: center; }
    .navbar-brand { font-family: var(--font-secondary); font-size: 1.8rem; text-decoration: none; color: var(--primary-color); }
    .navbar-nav { list-style: none; margin: 0; padding: 0; display: flex; gap: 24px; }
    .nav-link { text-decoration: none; color: var(--text-color); font-weight: 600; transition: color 0.2s; }
    .nav-link:hover, .nav-link.active { color: var(--primary-color); }
    .btn-primary-outline { background: transparent; color: var(--primary-color); border: 2px solid var(--primary-color); padding: 10px 20px; text-decoration: none; border-radius: 50px; font-weight: 600; transition: all 0.2s; }
    .btn-primary-outline:hover { background: var(--primary-color); color: #fff; }

    .page-header { text-align: center; padding: 80px 0; background-color: var(--secondary-color); color: #fff; }
    .page-header h1 { color: #fff; font-size: 3.5rem; }

    .about-content { padding: 80px 0; }
    .about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 48px; align-items: center; }
    .about-text h2 { font-size: 2.5rem; margin-top: 0; }
    .about-text p { font-size: 1.1rem; color: #777; }
    .about-image img { width: 100%; border-radius: var(--radius); box-shadow: var(--shadow); }

    .footer { background: var(--secondary-color); color: #fff; text-align: center; padding: 48px 0; }
    .footer p { margin: 0; }
    .footer .copyright { opacity: 0.7; margin-top: 8px; font-size: 0.9rem; }

    /* Responsive untuk mobile */
    @media (max-width: 768px) {
        .about-grid {
            grid-template-columns: 1fr;
        }
    }
  </style>
</head>
<body>

  <nav class="navbar">
  <div class="container">
    <a href="{{ route('home') }}" class="navbar-brand">Nyamaw 🐾</a>
    <ul class="navbar-nav">
      <li><a href="{{ route('home') }}" class="nav-link">Home</a></li>
      <li><a href="{{ route('menu') }}" class="nav-link">Menu</a></li>
      <li><a href="{{ route('about') }}" class="nav-link active">About</a></li>
    </ul>
    <div class="navbar-auth">
      @if (session('role'))
        <span class="welcome-text">Halo, {{ session('user_name') ?? 'User' }}</span>
        <form action="{{ route('logout') }}" method="POST" style="margin:0;">
          @csrf
          <button type="submit" class="btn-primary-outline">Logout</button>
        </form>
      @else
        <a href="{{ route('login') }}" class="btn-primary-outline">Login</a>
      @endif
    </div>
  </div>
</nav>

  <main>
    <section class="page-header">
      <div class="container">
        <h1>Tentang Kami</h1>
        <p>Cerita singkat di balik lezatnya setiap hidangan Nyamaw.</p>
      </div>
    </section>

    <section class="about-content">
      <div class="container">
        <div class="about-grid">
          <div class="about-text">
            <h2>Dari Dapur Rumah, <br>Untuk Anda.</h2>
            <p><strong>Nyamaw</strong> lahir dari kecintaan kami pada resep masakan rumahan yang otentik dan berkualitas. Kami percaya bahwa makanan enak tidak harus rumit atau mahal. Setiap hidangan yang kami sajikan adalah hasil dari proses memasak yang penuh cinta, menggunakan bahan-bahan segar pilihan dan resep warisan keluarga.</p>
            <p>Visi kami adalah menjadi teman setia di setiap momen makan Anda, menghadirkan kehangatan dan kebahagiaan melalui cita rasa yang selalu dirindukan.</p>
          </div>
          <div class="about-image">
            <img src="https://via.placeholder.com/500x550/fde68a/000000?text=Tim+Nyamaw" alt="Tim Dapur Nyamaw">
          </div>
        </div>
      </div>
    </section>
  </main>

  <footer class="footer">
    <div class="container">
      <p>Dibuat dengan ❤️ untuk para pecinta kuliner.</p>
      <p class="copyright">&copy; {{ date('Y') }} Nyamaw. All Rights Reserved.</p>
    </div>
  </footer>

</body>
</html>
