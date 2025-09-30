<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di Nyamaw - Cita Rasa Rumahan</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
    
    :root {
        --primary-color: #FF6347; /* Tomato */
        --secondary-color: #FFF0E5; /* Apricot White */
        --dark-color: #333;
        --light-color: #f4f4f4;
        --font-family: 'Poppins', sans-serif;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: var(--font-family); line-height: 1.6; color: var(--dark-color); }
    .container { max-width: 1100px; margin: auto; padding: 0 2rem; text-align: center; }
    h1, h2, h3, h4 { font-weight: 600; }
    section { padding: 4rem 0; }
    
    /* Hero Section Home */
    .hero-home { background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://via.placeholder.com/1500x700/333/FFFFFF?text=Foto+Makanan+Nyamaw') no-repeat center center/cover; color: #fff; height: 90vh; display: flex; justify-content: center; align-items: center; text-align: center; }
    .hero-home h1 { font-size: 3.5rem; margin-bottom: 1rem; }
    .hero-home p { font-size: 1.25rem; margin-bottom: 2rem; }
    
    /* Tombol Utama */
    .btn-primary { background-color: var(--primary-color); color: #fff; padding: 1rem 2rem; text-decoration: none; border-radius: 50px; font-weight: 600; transition: background-color 0.3s ease, transform 0.3s ease; }
    .btn-primary:hover { background-color: #e55337; transform: translateY(-3px); }
    
    /* Bagian Keunggulan */
    .advantages { background-color: var(--light-color); }
    .advantages-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-top: 2rem; }
    .advantage-item img { border-radius: 50%; margin-bottom: 1rem; }
    
    /* Bagian Produk Unggulan */
    .product-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-top: 2rem; }
    .product-card { background: #fff; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); overflow: hidden; transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .product-card:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); }
    .product-card img { width: 100%; height: 200px; object-fit: cover; }
    .product-card h4, .product-card p { padding: 0.5rem 1.5rem; }
    .product-card h4 { padding-top: 1rem; font-size: 1.25rem; }
    .product-card p { padding-bottom: 1.5rem; color: #777; }
    
    /* Bagian Testimoni */
    .testimonials { background-color: var(--secondary-color); }
    .testimonial-card { background: #fff; max-width: 700px; margin: auto; padding: 2rem; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
    .testimonial-card p { font-style: italic; font-size: 1.1rem; margin-bottom: 1rem; }
    .testimonial-card h4 { font-weight: 600; color: var(--primary-color); }
    
    /* Footer */
    footer { background-color: var(--dark-color); color: #fff; text-align: center; padding: 3rem 0; }
    footer h2 { margin-bottom: 1rem; }
    .social-links a { color: #fff; text-decoration: none; margin: 0 0.5rem; padding: 0.5rem 1rem; border: 1px solid var(--primary-color); border-radius: 20px; transition: background-color 0.3s ease; }
    .social-links a:hover { background-color: var(--primary-color); }
    .copyright { margin-top: 2rem; font-size: 0.9rem; color: #aaa; }

    /* ==================================
       CSS SELESAI DI SINI
       ================================== */
    </style>
</head>
<body>

    <header class="hero-home">
        <div class="hero-content">
            <h1>Rasa Otentik, Sampai ke Hati.</h1>
            <p>Nikmati hidangan spesial dari dapur Nyamaw yang dibuat khusus untuk Anda.</p>
            <a href="#produk" class="btn-primary">Lihat Menu Unggulan</a>
        </div>
    </header>

    <main>
        <section class="advantages">
            <div class="container">
                <h2>Kenapa Pilih Nyamaw?</h2>
                <div class="advantages-grid">
                    <div class="advantage-item">
                        <img src="https://via.placeholder.com/80/FF6347/FFFFFF?text=✓" alt="Icon Bahan Segar">
                        <h3>Bahan Segar Pilihan</h3>
                        <p>Kami hanya menggunakan bahan baku terbaik setiap hari.</p>
                    </div>
                    <div class="advantage-item">
                        <img src="https://via.placeholder.com/80/FF6347/FFFFFF?text=♥" alt="Icon Resep Asli">
                        <h3>Resep Asli Keluarga</h3>
                        <p>Cita rasa klasik yang diwariskan dari generasi ke generasi.</p>
                    </div>
                    <div class="advantage-item">
                        <img src="https://via.placeholder.com/80/FF6347/FFFFFF?text=✨" alt="Icon Higienis">
                        <h3>100% Higienis</h3>
                        <p>Proses pembuatan yang bersih dan terjamin keamanannya.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="featured-products" id="produk">
            <div class="container">
                <h2>Menu Andalan Kami</h2>
                <div class="product-grid">
                    <div class="product-card">
                        <img src="https://via.placeholder.com/300x200/FADBD8/884EA0?text=Produk+Andalan+1" alt="Produk 1">
                        <h4>Nama Produk Satu</h4>
                        <p>Deskripsi singkat yang menggugah selera tentang produk ini.</p>
                    </div>
                    <div class="product-card">
                        <img src="https://via.placeholder.com/300x200/D5F5E3/1E8449?text=Produk+Andalan+2" alt="Produk 2">
                        <h4>Nama Produk Dua</h4>
                        <p>Deskripsi singkat yang menggugah selera tentang produk ini.</p>
                    </div>
                    <div class="product-card">
                        <img src="https://via.placeholder.com/300x200/FCF3CF/F1C40F?text=Produk+Andalan+3" alt="Produk 3">
                        <h4>Nama Produk Tiga</h4>
                        <p>Deskripsi singkat yang menggugah selera tentang produk ini.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="testimonials">
            <div class="container">
                <h2>Apa Kata Mereka?</h2>
                <div class="testimonial-card">
                    <p>"Masakannya enak banget, berasa makan di rumah. Bumbunya pas, pengirimannya juga cepat. Pasti pesan lagi!"</p>
                    <h4>- Nama Pelanggan, Pelanggan Setia</h4>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <h2>Pesan Sekarang!</h2>
            <div class="social-links">
                <a href="#">Instagram</a>
                <a href="#">WhatsApp</a>
            </div>
            <p class="copyright">&copy; 2025 Nyamaw. All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>
