<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register Akun Baru - Nyamaw</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  {{-- Kita gunakan CSS yang sama persis dengan login.blade.php --}}
  <style>
    :root {
      --primary-color: #FF6347; --secondary-color: #FFF0E5; --dark-color: #333;
      --light-color: #f4f4f4; --font-family: 'Poppins', sans-serif;
      --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
      --radius: 10px;
    }
    * { box-sizing: border-box; }
    body { margin: 0; font-family: var(--font-family); background: var(--light-color); color: var(--dark-color); display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 24px; }
    .login-container { display: grid; grid-template-columns: 1fr 1fr; max-width: 960px; width: 100%; background: #fff; border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; }
    .login-branding { background: var(--primary-color); color: #fff; padding: 48px; display: flex; flex-direction: column; justify-content: center; }
    .login-branding h1 { font-size: 2.5rem; margin-top: 0; line-height: 1.2; }
    .login-branding p { font-size: 1.1rem; opacity: 0.9; }
    .login-form { padding: 48px; }
    .login-form h2 { margin-top: 0; font-size: 1.8rem; }
    .field { margin-bottom: 20px; }
    .field label { display: block; margin-bottom: 8px; font-weight: 600; }
    .input { width: 100%; padding: 12px 16px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 1rem; transition: border-color 0.2s, box-shadow 0.2s; }
    .input:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(255, 99, 71, 0.2); }
    .btn { display: inline-block; width: 100%; background: var(--primary-color); color: #fff; border: none; border-radius: 8px; padding: 14px; text-decoration: none; cursor: pointer; font-weight: 600; font-size: 1rem; transition: background-color 0.2s; }
    .btn:hover { background-color: #e55337; }
    .error { padding: 12px; border-radius: 8px; margin-bottom: 16px; background-color: #fee2e2; color: #b91c1c; }
    .login-link { text-align: center; margin-top: 16px; }
    .login-link a { color: var(--primary-color); text-decoration: none; font-weight: 600; }
    @media (max-width: 768px) { .login-container { grid-template-columns: 1fr; } .login-branding { display: none; } .login-form { padding: 32px; } }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-branding">
      <h1>Buat Akun Baru.</h1>
      <p>Bergabunglah dengan Nyamaw untuk menikmati kemudahan memesan hidangan favorit Anda.</p>
    </div>
    <div class="login-form">
      <h2>Register Akun</h2>

      @if ($errors->any())
        <div class="error">
          <ul style="margin:0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      {{-- FORM REGISTRASI --}}
      <form method="POST" action="{{ route('register.post') }}">
        @csrf
        <div class="field">
          <label for="name">Nama Lengkap</label>
          <input id="name" name="name" class="input" value="{{ old('name') }}" required autofocus>
        </div>
        <div class="field">
          <label for="email">Alamat Email</label>
          <input id="email" name="email" class="input" type="email" value="{{ old('email') }}" required>
        </div>
        <div class="field">
          <label for="password">Password</label>
          <input id="password" name="password" class="input" type="password" required>
        </div>
        <div class="field">
          <label for="password_confirmation">Konfirmasi Password</label>
          <input id="password_confirmation" name="password_confirmation" class="input" type="password" required>
        </div>
        <button class="btn" type="submit">Daftar Sekarang</button>
      </form>
      
      <div class="login-link">
        <p>Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></p>
      </div>
    </div>
  </div>
</body>
</html>