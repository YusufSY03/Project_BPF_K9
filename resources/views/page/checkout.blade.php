@extends('layouts.app')

@section('title', 'Checkout - Nyamaw')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endpush

@section('custom-css')
<style>
    /* LAYOUT */
    .checkout-section { padding: 60px 0 100px; }
    .checkout-grid { display: grid; grid-template-columns: 1.5fr 1fr; gap: 40px; }
    .checkout-card { background: white; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid #eee; padding: 30px; margin-bottom: 20px; }
    .card-title { font-family: var(--font-head); font-size: 1.5rem; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px dashed #eee; color: var(--dark); }
    #map { height: 400px; width: 100%; border-radius: 12px; border: 2px solid #eee; z-index: 1; }
    .map-instruction { background: #e0f2fe; color: #0284c7; padding: 12px; border-radius: 8px; font-size: 0.9rem; margin-bottom: 15px; border-left: 4px solid #0284c7; }
    .form-group { margin-bottom: 20px; }
    .form-label { font-weight: 600; display: block; margin-bottom: 8px; color: var(--dark); }
    .form-input, .form-select { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: var(--font-body); font-size: 1rem; }
    .form-input:disabled { background: #f3f4f6; color: #888; }
    .summary-box { position: sticky; top: 100px; }
    .summary-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 0.95rem; color: #555; }
    .summary-total { margin-top: 20px; padding-top: 20px; border-top: 2px solid #eee; display: flex; justify-content: space-between; font-weight: 800; font-size: 1.3rem; color: var(--primary); }
    .btn-confirm { width: 100%; background: var(--dark); color: white; padding: 15px; border-radius: 8px; font-weight: 700; text-transform: uppercase; border: none; cursor: pointer; margin-top: 20px; transition: 0.3s; }
    .btn-confirm:hover { background: var(--primary); transform: translateY(-2px); }
    .btn-confirm:disabled { background: #ccc; cursor: not-allowed; transform: none; }
    
    /* AREA QRIS */
    #qris-area { display: none; text-align: center; margin-top: 20px; padding: 20px; background: #f9fafb; border-radius: 12px; border: 1px solid #eee; }
    .qris-img { width: 200px; border-radius: 8px; border: 2px solid #ddd; margin-bottom: 10px; }
    .qris-note { font-size: 0.85rem; color: #666; }

    @media (max-width: 900px) { .checkout-grid { grid-template-columns: 1fr; } .summary-box { position: static; } }
</style>
@endsection

@section('content')

    <div style="height: 120px; width: 100%; background: var(--light);"></div>

    <section class="checkout-section container">
        
        <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
            @csrf
            <input type="hidden" name="latitude" id="lat_input">
            <input type="hidden" name="longitude" id="long_input">
            <input type="hidden" id="subtotal_raw" value="{{ $subtotal = collect(session('cart'))->sum(fn($i) => $i['price'] * $i['quantity']) }}">

            <div class="checkout-grid">
                
                {{-- KIRI --}}
                <div>
                    <div class="checkout-card">
                        <h2 class="card-title">Lokasi Pengantaran</h2>
                        @if ($errors->any())
                            <div style="background: #fee2e2; color: #b91c1c; padding: 10px; border-radius: 6px; margin-bottom: 15px;">
                                <ul style="margin:0; padding-left:20px;">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                            </div>
                        @endif
                        <div class="map-instruction">📍 <strong>Klik pada peta</strong> untuk menentukan lokasi pengantaran pesanan Anda.</div>
                        <div id="map"></div>
                        <div id="location-status" style="margin-top:10px; font-weight:600; color:var(--primary);"></div>
                    </div>

                    <div class="checkout-card">
                        <h2 class="card-title">Metode Pembayaran</h2>
                        
                        <div class="form-group">
                            <label class="form-label">Pilih Cara Bayar</label>
                            <select name="payment_method" class="form-select" id="payment_select" onchange="toggleQris()">
                                <option value="cash">Bayar Tunai (COD)</option>
                                <option value="transfer">QRIS (Scan & Bayar)</option>
                            </select>
                        </div>

                        {{-- TAMPILAN QRIS (MUNCUL JIKA DIPILIH) --}}
                        <div id="qris-area">
                            <img src="{{ asset('img/qris.jpg') }}" class="qris-img" alt="QRIS Nyamaw">
                            <p class="qris-note">Scan kode di atas menggunakan GoPay, OVO, Dana, atau Mobile Banking.</p>
                            <div style="margin-top:10px; font-size:0.8rem; color:#d97706; background:#fff7ed; padding:8px; border-radius:6px;">
                                ⚠️ Mohon screenshot bukti bayar Anda, nanti diupload di halaman <strong>Riwayat Pesanan</strong>.
                            </div>
                        </div>

                    </div>
                </div>

                {{-- KANAN --}}
                <div>
                    <div class="checkout-card summary-box">
                        <h2 class="card-title">Ringkasan Pesanan</h2>
                        @foreach(session('cart') as $details)
                            <div class="summary-row">
                                <span>{{ $details['name'] }} <small>x{{ $details['quantity'] }}</small></span>
                                <span>Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                        <div class="summary-row" style="margin-top: 15px; padding-top: 15px; border-top: 1px dashed #eee;">
                            <span>Subtotal</span><span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="summary-row">
                            <span>Ongkos Kirim</span><span id="ongkir-text">Rp 0</span>
                        </div>
                        <div class="summary-total">
                            <span>Total</span><span id="grand-total-text">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <button type="submit" class="btn-confirm" id="btn-submit" disabled>Pilih Lokasi Dulu</button>
                    </div>
                </div>

            </div>
        </form>

    </section>

@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        // FUNGSI MUNCULKAN QRIS
        function toggleQris() {
            var select = document.getElementById('payment_select');
            var qrisArea = document.getElementById('qris-area');
            if(select.value === 'transfer') {
                qrisArea.style.display = 'block';
            } else {
                qrisArea.style.display = 'none';
            }
        }

        // LOGIKA PETA (SAMA SEPERTI SEBELUMNYA)
        var storeLat = -6.175392; 
        var storeLong = 106.827153;
        var map = L.map('map').setView([storeLat, storeLong], 13);
        var marker;
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '© OpenStreetMap' }).addTo(map);
        if (navigator.geolocation) { navigator.geolocation.getCurrentPosition(function(position) { map.setView([position.coords.latitude, position.coords.longitude], 15); }); }

        map.on('click', function(e) {
            var lat = e.latlng.lat; var long = e.latlng.lng;
            if (marker) { marker.setLatLng(e.latlng); } else { marker = L.marker(e.latlng).addTo(map); }
            document.getElementById('lat_input').value = lat; document.getElementById('long_input').value = long;
            
            var statusEl = document.getElementById('location-status');
            var btn = document.getElementById('btn-submit');
            statusEl.style.display = 'block'; statusEl.innerText = "⏳ Menghitung ongkir..."; statusEl.style.color = "#d97706";
            btn.disabled = true; btn.innerText = "Menghitung..."; btn.style.background = "#ccc";
            hitungOngkir(lat, long);
        });

        function hitungOngkir(lat, long) {
            var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch("{{ route('check.shipping') }}", {
                method: "POST", headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": token },
                body: JSON.stringify({ latitude: lat, longitude: long })
            }).then(response => response.json()).then(data => {
                var statusEl = document.getElementById('location-status');
                var btn = document.getElementById('btn-submit');
                if(data.status == 'success') {
                    document.getElementById('ongkir-text').innerText = "Rp " + new Intl.NumberFormat('id-ID').format(data.ongkir);
                    var subtotal = parseFloat(document.getElementById('subtotal_raw').value);
                    document.getElementById('grand-total-text').innerText = "Rp " + new Intl.NumberFormat('id-ID').format(subtotal + data.ongkir);
                    statusEl.innerText = "✅ Jarak: " + data.jarak + " km. Ongkir ditemukan."; statusEl.style.color = "#16a34a";
                    btn.disabled = false; btn.innerText = "BUAT PESANAN SEKARANG"; btn.style.background = "var(--dark)";
                } else {
                    statusEl.innerText = "❌ " + data.message; statusEl.style.color = "#dc2626"; btn.innerText = "Pilih Lokasi Lain";
                }
            }).catch(error => { console.error('Error:', error); alert("Gagal terhubung ke server."); });
        }
    </script>
@endpush