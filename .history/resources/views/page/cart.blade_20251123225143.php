<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Keranjang Belanja - Nyamaw</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
  <style>
    /* Style Dasar (Sama dengan Menu) */
    :root { --primary-color: #FF6347; --secondary-color: #333; --bg-color: #fdfaf8; --text-color: #4a4a4a; --font-primary: 'Poppins', sans-serif; --font-secondary: 'Playfair Display', serif; }
    body { margin: 0; font-family: var(--font-primary); background: var(--bg-color); color: var(--text-color); }
    .container { max-width: 1100px; margin: 0 auto; padding: 0 24px; }
    
    /* Navbar */
    .navbar { background: rgba(255,255,255,0.8); backdrop-filter: blur(10px); padding: 16px 0; border-bottom: 1px solid #eee; position: sticky; top: 0; z-index: 100; }
    .navbar .container { display: flex; justify-content: space-between; align-items: center; }
    .navbar-brand { font-family: var(--font-secondary); font-size: 1.8rem; text-decoration: none; color: var(--primary-color); }
    .navbar-nav { list-style: none; margin: 0; padding: 0; display: flex; gap: 24px; }
    .nav-link { text-decoration: none; color: var(--text-color); font-weight: 600; }
    .nav-link:hover { color: var(--primary-color); }
    .btn-primary-outline { background: transparent; color: var(--primary-color); border: 2px solid var(--primary-color); padding: 8px 16px; text-decoration: none; border-radius: 50px; font-weight: 600; }
    .navbar-auth { display: flex; align-items: center; gap: 16px; }

    /* Tabel Keranjang */
    .cart-page { padding: 60px 0; }
    .cart-table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 20px rgba(0,0,0,0.05); margin-bottom: 24px; }
    .cart-table th, .cart-table td { padding: 20px; text-align: left; border-bottom: 1px solid #eee; }
    .cart-table th { background: #f9fafb; font-weight: 600; color: var(--secondary-color); }
    .cart-item-info { display: flex; align-items: center; gap: 16px; }
    .cart-item-info img { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; }
    
    /* Total & Checkout */
    .cart-summary { background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 10px 20px rgba(0,0,0,0.05); text-align: right; }
    .total-price { font-size: 1.5rem; font-weight: 700; color: var(--primary-color); }
    .btn-checkout { background: var(--primary-color); color: #fff; padding: 12px 32px; border-radius: 50px; text-decoration: none; font-weight: 600; display: inline-block; margin-top: 16px; border: none; cursor: pointer; font-size: 1rem; }
    .btn-remove { color: #ef4444; background: none; border: none; cursor: pointer; font-weight: 600; }
  </style>
</head>
<body>

  {{-- NAVBAR --}}
  <nav class="navbar">
    <div class="container">
      <a href="{{ route('home') }}" class="navbar-brand">Nyamaw 🐾</a>
      <ul class="navbar-nav">
        <li><a href="{{ route('home') }}" class="nav-link">Home</a></li>
        <li><a href="{{ route('menu') }}" class="nav-link">Menu</a></li>
      </ul>
      <div class="navbar-auth">
        <a href="{{ route('cart') }}" class="btn-primary-outline" style="background:var(--primary-color); color:white;">
            🛒 {{ count((array) session('cart')) }}
        </a>
        <span style="font-weight:600;">{{ Auth::user()->name }}</span>
      </div>
    </div>
  </nav>

  {{-- KONTEN KERANJANG --}}
  <section class="cart-page">
    <div class="container">
      <h2>Keranjang Belanja Anda</h2>
      
      @if(session('status'))
        <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('status') }}
        </div>
      @endif

      @if(session('cart'))
        <table class="cart-table">
          <thead>
            <tr>
              <th style="width: 50%">Menu</th>
              <th>Harga</th>
              <th>Jumlah</th>
              <th>Subtotal</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @php $total = 0; @endphp
            @foreach(session('cart') as $id => $details)
              @php $total += $details['price'] * $details['quantity'] @endphp
              <tr>
                <td>
                  <div class="cart-item-info">
                    <img src="{{ $details['image'] ?? 'https://via.placeholder.com/60' }}" alt="{{ $details['name'] }}">
                    <div>
                        <strong>{{ $details['name'] }}</strong>
                    </div>
                  </div>
                </td>
                <td>Rp {{ number_format($details['price'], 0, ',', '.') }}</td>
                <td>{{ $details['quantity'] }}</td>
                <td>Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</td>
                <td>
                  <form action="{{ route('cart.remove') }}" method="POST">
                      @csrf
                      @method('DELETE')
                      <input type="hidden" name="id" value="{{ $id }}">
                      <button type="submit" class="btn-remove">Hapus</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        <div class="cart-summary">
            <h3>Total Pembayaran</h3>
            <div class="total-price">Rp {{ number_format($total, 0, ',', '.') }}</div>
            <p style="color:#777; margin-bottom: 24px;">Biaya pengiriman akan dihitung saat checkout.</p>
            
            <a href="{{ route('menu') }}" style="text-decoration:none; color:#777; margin-right: 16px;">&larr; Lanjut Belanja</a>
            {{-- Tombol ini nanti akan kita arahkan ke proses Checkout --}}
            <button class="btn-checkout">Proses Checkout &rarr;</button>
        </div>

      @else
        <div style="text-align: center; padding: 60px; background: #fff; border-radius: 12px;">
            <h3>Keranjang Anda masih kosong.</h3>
            <p>Yuk, cari makanan enak di menu kami!</p>
            <a href="{{ route('menu') }}" class="btn-checkout" style="margin-top:16px;">Lihat Menu</a>
        </div>
      @endif

    </div>
  </section>

</body>
</html>