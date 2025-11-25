<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | Nyamaw Dashboard</title>

    <style>
        :root {
            --bg: #f3f4f6;
            --card: #ffffff;
            --text: #111827;
            --muted: #6b7280;
            --primary: #3b82f6;
            --danger: #ef4444;
            --success: #10b981;
            --radius: 10px;
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial;
            background: var(--bg);
            color: var(--text);
            line-height: 1.6;
            display: flex;
        }

        .sidebar {
            width: 240px;
            background: #1f2937;
            color: #e5e7eb;
            padding: 24px;
            height: 100vh;
            position: fixed;
        }

        .main-content {
            margin-left: 240px;
            width: calc(100% - 240px);
            padding: 24px;
        }

        .sidebar-header {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 24px;
        }

        .sidebar-nav a {
            display: block;
            color: #d1d5db;
            text-decoration: none;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 8px;
            transition: background 0.2s;
        }

        .sidebar-nav a.active,
        .sidebar-nav a:hover {
            background: #4b5563;
            color: #fff;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 24px;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .header h1 {
            margin: 0;
            font-size: 1.8rem;
        }

        .btn {
            display: inline-block;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px 14px;
            text-decoration: none;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-danger {
            background: var(--danger);
        }

        .btn-success {
            background: var(--success);
        }

        .card {
            background: var(--card);
            border-radius: var(--radius);
            padding: 24px;
            box-shadow: var(--shadow);
        }

        .status-message {
            padding: 16px;
            background: var(--success);
            color: #fff;
            border-radius: var(--radius);
            margin-bottom: 24px;
            box-shadow: var(--shadow);
        }

        .error-message {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 16px;
            background-color: #fee2e2;
            color: #b91c1c;
        }

        .user-table {
            width: 100%;
            border-collapse: collapse;
        }

        .user-table th,
        .user-table td {
            padding: 12px 16px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }

        .user-table th {
            background-color: #f9fafb;
            font-weight: 600;
        }

        .user-table .badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .badge.role-owner {
            background-color: #fef3c7;
            color: #92400e;
        }

        .badge.role-admin {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .badge.role-user {
            background-color: #e5e7eb;
            color: #374151;
        }

        .action-buttons a,
        .action-buttons button {
            margin-right: 8px;
            text-decoration: none;
            padding: 6px 10px;
            border-radius: 6px;
            font-weight: 600;
        }

        .btn-edit {
            background: #dbeafe;
            color: #1e40af;
        }

        .btn-delete {
            background: #fee2e2;
            color: #991b1b;
            border: none;
            cursor: pointer;
        }

        .form-grid {
            max-width: 700px;
        }

        .field {
            margin-bottom: 20px;
        }

        .field label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .input,
        .select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            background: #fff;
        }

        .input:focus,
        .select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }

        .note {
            font-size: 0.9rem;
            color: var(--muted);
            margin-top: 8px;
        }
        /* ... CSS form yang lama ... */

    /* === TAMBAHAN CSS UNTUK FORM SEARCH & FILTER === */
    .search-filter-container {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        align-items: center;
        padding: 16px; /* Tambahan padding agar tidak mepet */
    }

    /* Memperbaiki tampilan input dan select agar sejajar */
    .search-filter-container .input,
    .search-filter-container .select {
        margin-bottom: 0; /* Hapus margin bawah default form */
        height: 42px; /* Samakan tinggi */
    }

    /* Tombol Cari agar lebih terlihat */
    .btn-search {
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0 20px;
        height: 42px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-reset {
        background: #e5e7eb;
        color: var(--text);
        text-decoration: none;
        border-radius: 8px;
        padding: 0 20px;
        height: 42px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    /* === STYLE PAGINATION CUSTOM === */
    /* Memperbaiki tampilan pagination bawaan Laravel/Bootstrap */

    /* Hapus icon SVG raksasa jika masih ada sisa */
    nav svg { display: none; }

    /* Container pagination */
    .pagination {
        display: flex;
        padding-left: 0;
        list-style: none;
        gap: 5px; /* Jarak antar kotak */
        justify-content: center;
        margin-top: 20px;
    }

    /* Kotak nomor/tombol */
    .page-link {
        position: relative;
        display: block;
        padding: 8px 16px;
        font-size: 0.9rem;
        color: var(--text);
        text-decoration: none;
        background-color: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        transition: all 0.2s;
    }

    /* Efek Hover (saat mouse lewat) */
    .page-link:hover {
        background-color: #f3f4f6;
        color: var(--primary);
        border-color: var(--primary);
    }

    /* Halaman yang Sedang Aktif */
    .page-item.active .page-link {
        z-index: 3;
        color: #fff;
        background-color: var(--primary); /* Warna Biru Dashboard */
        border-color: var(--primary);
    }

    /* Tombol Mati (Disabled) - misal: di halaman 1, tombol 'Prev' mati */
    .page-item.disabled .page-link {
        color: #9ca3af;
        pointer-events: none;
        background-color: #f9fafb;
        border-color: #e5e7eb;
    }

    /* Sembunyikan teks ribet "Showing 1 to 10..." dari layout default jika mengganggu */
    .hidden.sm\:flex-1.sm\:flex.sm\:items-center.sm\:justify-between {
        display: none;
    }
    /* Tampilkan hanya navigasi nomornya saja */
    div[role="navigation"] > div:nth-child(2) {
        display: flex;
        justify-content: center;
    }
    </style>
</head>

<body>

    <aside class="sidebar">
        <div class="sidebar-header">
            {{ Auth::user()->role === 'admin' ? 'Admin Panel' : 'Owner Panel' }}
        </div>

        <nav class="sidebar-nav">
            <a href="{{ Auth::user()->role === 'admin' ? route('admin') : route('owner') }}"
                class="{{ request()->routeIs('admin', 'owner') ? 'active' : '' }}">
                Dashboard
            </a>
            {{-- Link Pesanan Masuk (BARU) --}}
            <a href="{{ route('orders.index') }}"
                class="{{ request()->routeIs('orders.*') ? 'active' : '' }}">
                Pesanan Masuk
            </a>

            <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                Manajemen User
            </a>
            {{-- Tampilkan HANYA jika role-nya owner --}}
            @if (Auth::user()->role === 'owner')
            <a href="{{ route('owner.menu.index') }}"
                class="{{ request()->routeIs('owner.menu.*') ? 'active' : '' }}">
                Manajemen Menu
            </a>
            @endif


            <a href="#">Pengaturan</a>
            <a href="{{ route('home') }}">Lihat Situs</a>
        </nav>

        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-danger" style="width:100%;">Logout</button>
            </form>
        </div>
    </aside>

    <main class="main-content">
        @yield('content')
    </main>

</body>

</html>
