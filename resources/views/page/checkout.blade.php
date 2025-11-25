<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- PENTING UNTUK AJAX --}}
  <title>Checkout - Nyamaw</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  
  {{-- LEAFLET CSS --}}
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

  <style>
    :root { --primary: #FF6347; --bg: #fdfaf8; --text: #333; --card: #fff; }
    body { margin: 0; font-family: 'Poppins', sans-serif; background: var(--bg); color: var(--text); padding: 40px 20px; }
    .container { max-width: 800px; margin: 0 auto; } /* Lebarkan dikit container */
    .card { background: var(--card); padding: 30px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
    h1 { margin-top: 0; color: var(--primary); }
    .summary-item { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee; }
    .total { font-weight: 700; font-size: 1.2rem; color: var(--primary); border-top: 2px solid #eee; margin-top: 10px; padding-top: 10px; }
    
    .field { margin-bottom: 20px; margin-top: 20px; }
    label { display: block; margin-bottom: 8px; font-weight: 600; }
    select, input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
    
    .btn-confirm { width: 100%; background: var(--primary); color: #fff; padding: 15px; border: none; border-radius: 8px; font-size: 1rem; font-weight: 700; cursor: pointer; margin-top: 20px; transition: 0.3s; }
    .btn-confirm:disabled { background: #ccc; cursor: not-allowed; }
    .btn-back { display: block; text-align: center; margin-top: 15px; color: #777; text-decoration: none; }

    /* Style Peta */
    #map { height: 350px; width: 100%; border-radius: 8px; margin-bottom: 10px; border: 2px solid #eee; }
    .location-info { background: #e0f2fe; color: #0369a1; padding: 10px; border-radius: 6px; font-size: 0.9rem; margin-bottom: 15px; display: none; }
  </style>
</head>
<body>

<div class="container">
  <div class="card">
    <h1>Konfirmasi Pesanan</h1>
    <p>Silakan pilih lokasi pengantaran pada peta di bawah ini.</p>

    {{-- Pesan Error Validasi --}}
    @if ($errors->any())
        <div style="background: #fee2e2; color: #b91c1c; padding: 10px; border-radius: 6px; margin-bottom: 15px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- AREA PETA --}}
    <div class="field">
        <label>Pilih Lokasi Pengantaran (Klik pada Peta)</label>
        <div id="map"></div>
        <div id="location-status" class="location-info"></div>
    </div>

    {{-- Ringkasan Pesanan --}}
    <div style="background: #f9fafb; padding: 15px; border-radius: 8px;">
        @php $subtotal = 0; @endphp
        @foreach(session('cart') as $details)
            @php $subtotal += $details['price'] * $details['quantity'] @endphp
            <div class="summary-item">
                <span>{{ $details['name'] }} (x{{ $details['quantity'] }})</span>
                <span>Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</span>
            </div>
        @endforeach
        
        {{-- Baris Ongkir --}}
        <div class="summary-item" style="color: #666;">
            <span>Biaya Pengiriman (<span id="jarak-text">0 km</span>)</span>
            <span id="ongkir-text">Rp 0</span>
        </div>

        <div class="summary-item total">
            <span>Total Bayar</span>
            <span id="grand-total-text">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- Formulir Checkout --}}
    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        
        {{-- Input Tersembunyi untuk Koordinat --}}
        <input type="hidden" name="latitude" id="lat_input">
        <input type="hidden" name="longitude" id="long_input">
        <input type="hidden" id="subtotal_raw" value="{{ $subtotal }}">

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

        {{-- Tombol dimatikan dulu sebelum pilih lokasi --}}
        <button type="submit" class="btn-confirm" id="btn-submit" disabled>Pilih Lokasi Dulu</button>
    </form>

    <a href="{{ route('cart') }}" class="btn-back">Kembali ke Keranjang</a>
  </div>
</div>

{{-- LEAFLET JS --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    // 1. Inisialisasi Peta (Default ke Jakarta/Lokasi Toko Kamu)
    // Ambil dari .env idealnya, tapi kita hardcode default dulu ke Monas biar aman
    var map = L.map('map').setView([-6.175392, 106.827153], 13);
    var marker;

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    // Coba ambil lokasi user saat ini (Browser GPS)
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var lat = position.coords.latitude;
            var long = position.coords.longitude;
            map.setView([lat, long], 15);
        });
    }

    // 2. Event Listener: Saat Peta Diklik
    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var long = e.latlng.lng;

        // Pindahkan Marker
        if (marker) {
            marker.setLatLng(e.latlng);
        } else {
            marker = L.marker(e.latlng).addTo(map);
        }

        // Update Input Hidden
        document.getElementById('lat_input').value = lat;
        document.getElementById('long_input').value = long;

        // Tampilkan loading
        document.getElementById('location-status').style.display = 'block';
        document.getElementById('location-status').innerText = "Menghitung ongkir...";
        document.getElementById('btn-submit').disabled = true;
        document.getElementById('btn-submit').innerText = "Menghitung...";

        // 3. Panggil AJAX ke Controller
        hitungOngkir(lat, long);
    });

    function hitungOngkir(lat, long) {
        // Ambil CSRF Token
        var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch("{{ route('check.shipping') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": token
            },
            body: JSON.stringify({
                latitude: lat,
                longitude: long
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.status == 'success') {
                // Update UI
                document.getElementById('jarak-text').innerText = data.jarak + " km";
                document.getElementById('ongkir-text').innerText = "Rp " + new Intl.NumberFormat('id-ID').format(data.ongkir);
                
                // Update Grand Total
                var subtotal = parseFloat(document.getElementById('subtotal_raw').value);
                var grandTotal = subtotal + data.ongkir;
                document.getElementById('grand-total-text').innerText = "Rp " + new Intl.NumberFormat('id-ID').format(grandTotal);
                
                // Aktifkan Tombol
                document.getElementById('location-status').innerText = "Jarak: " + data.jarak + " km. Ongkir ditemukan.";
                document.getElementById('btn-submit').disabled = false;
                document.getElementById('btn-submit').innerText = "Buat Pesanan Sekarang";
            } else {
                alert("Gagal menghitung ongkir: " + data.message);
                document.getElementById('location-status').innerText = "Gagal. Coba titik lain.";
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Terjadi kesalahan sistem.");
        });
    }
</script>

</body>
</html>