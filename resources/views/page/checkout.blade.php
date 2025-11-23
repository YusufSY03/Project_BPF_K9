<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout - Nyamaw</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    :root { --primary: #FF6347; --bg: #fdfaf8; --text: #333; --card: #fff; }
    body { margin: 0; font-family: 'Poppins', sans-serif; background: var(--bg); color: var(--text); padding: 40px 20px; }
    .container { max-width: 600px; margin: 0 auto; }
    .card { background: var(--card); padding: 30px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
    h1 { margin-top: 0; color: var(--primary); }
    .summary-item { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee; }
    .total { font-weight: 700; font-size: 1.2rem; color: var(--primary); border-top: 2px solid #eee; margin-top: 10px; padding-top: 10px; }
    
    .field { margin-bottom: 20px; margin-top: 20px; }
    label { display: block; margin-bottom: 8px; font-weight: 600; }
    select, input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
    
    .btn-confirm { width: 100%; background: var(--primary); color: #fff; padding: 15px; border: none; border-radius: 8px; font-size: 1rem; font-weight: 700; cursor: pointer; margin-top: 20px; }
    .btn-back { display: block; text-align: center; margin-top: 15px; color: #777; text-decoration: none; }
  </style>
</head>
<body>

<div class="container">
  <div class="card">
    <h1>Konfirmasi Pesanan</h1>
    <p>Mohon periksa kembali pesanan Anda sebelum melanjutkan.</p>

    {{-- Ringkasan Pesanan --}}
    <div style="background: #f9fafb; padding: 15px; border-radius: 8px;">
        @php $total = 0; @endphp
        @foreach(session('cart') as $details)
            @php $total += $details['price'] * $details['quantity'] @endphp
            <div class="summary-item">
                <span>{{ $details['name'] }} (x{{ $details['quantity'] }})</span>
                <span>Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</span>
            </div>
        @endforeach
        <div class="summary-item total">
            <span>Total Bayar</span>
            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- Formulir Checkout --}}
    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        
        <div class="field">
            <label>Nama Pemesan</label>
            <input type="text" value="{{ Auth::user()->name }}" disabled style="background: #eee;">
        </div>

        <div class="field">
            <label for="payment_method">Metode Pembayaran</label>
            <select name="payment_method" id="payment_method">
                <option value="cash">Bayar Tunai (Cash)</option>
                <option value="transfer">Transfer Bank</option>
            </select>
        </div>

        <button type="submit" class="btn-confirm">Buat Pesanan Sekarang</button>
    </form>

    <a href="{{ route('cart') }}" class="btn-back">Kembali ke Keranjang</a>
  </div>
</div>

</body>
</html>