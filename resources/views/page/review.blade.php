<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Beri Ulasan - Nyamaw</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    :root { --primary: #FF6347; --bg: #fdfaf8; --text: #333; }
    body { margin: 0; font-family: 'Poppins', sans-serif; background: var(--bg); color: var(--text); padding: 40px 20px; }
    .container { max-width: 600px; margin: 0 auto; }
    .card { background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); text-align: center; }
    h1 { color: var(--primary); margin-bottom: 10px; }
    
    .field { margin-bottom: 20px; text-align: left; }
    label { display: block; margin-bottom: 8px; font-weight: 600; }
    select, textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
    
    .btn-submit { background: var(--primary); color: white; border: none; padding: 12px 24px; border-radius: 50px; font-weight: bold; cursor: pointer; width: 100%; font-size: 1rem; }
    .btn-back { display: block; margin-top: 15px; color: #777; text-decoration: none; font-size: 0.9rem; }
  </style>
</head>
<body>

<div class="container">
  <div class="card">
    <h1>Beri Ulasan</h1>
    <p>Bagaimana pengalaman Anda dengan pesanan <strong>#{{ $order->id }}</strong>?</p>

    <form action="{{ route('reviews.store', $order->id) }}" method="POST">
        @csrf
        
        <div class="field">
            <label>Rating Bintang</label>
            <select name="rating_stars" required style="font-size: 1.2rem;">
                <option value="5">⭐⭐⭐⭐⭐ (Sangat Puas)</option>
                <option value="4">⭐⭐⭐⭐ (Puas)</option>
                <option value="3">⭐⭐⭐ (Biasa Saja)</option>
                <option value="2">⭐⭐ (Kurang)</option>
                <option value="1">⭐ (Kecewa)</option>
            </select>
        </div>

        <div class="field">
            <label>Komentar Anda (Opsional)</label>
            <textarea name="comment" rows="4" placeholder="Ceritakan rasa makanannya..."></textarea>
        </div>

        <button type="submit" class="btn-submit">Kirim Ulasan</button>
    </form>

    <a href="{{ route('orders.history') }}" class="btn-back">Kembali</a>
  </div>
</div>

</body>
</html>