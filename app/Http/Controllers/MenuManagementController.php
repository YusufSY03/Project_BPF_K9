<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; // Pastikan ini ada

class MenuManagementController extends Controller
{
    /**
     * Menampilkan halaman daftar menu (READ).
     */
    public function index()
    {
        $menuItems = MenuItem::orderBy('id', 'desc')->get();
        return view('dashboard.menuManagement', [
            'menuItems' => $menuItems
        ]);
    }

    /**
     * Menampilkan formulir untuk membuat menu baru (CREATE).
     */
    public function create()
    {
        return view('dashboard.menuForm');
    }

    /**
     * Menyimpan menu baru ke database (STORE).
     */
    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
            'availability_status' => 'required|string|in:available,sold_out',
        ]);

        // 2. Buat data
        MenuItem::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
            'image_url' => $request->image_url,
            'availability_status' => $request->availability_status,
            'is_active' => $request->has('is_active'),
        ]);

        // 3. Redirect
        return redirect()->route('owner.menu.index')
                         ->with('status', 'Menu baru berhasil ditambahkan.');
    }

    // ===============================================
    // FUNGSI BARU UNTUK 'EDIT', 'UPDATE', 'DELETE'
    // ===============================================

    /**
     * Menampilkan formulir untuk mengedit menu (EDIT).
     */
    public function edit(MenuItem $menuItem)
    {
        // Tampilkan form, tapi kirim data $menuItem yang mau diedit
        return view('dashboard.menuForm', [
            'menuItem' => $menuItem
        ]);
    }

    /**
     * Menyimpan perubahan menu ke database (UPDATE).
     */
    public function update(Request $request, MenuItem $menuItem)
    {
        // 1. Validasi
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
            'availability_status' => 'required|string|in:available,sold_out',
        ]);

        // 2. Update data
        $menuItem->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
            'image_url' => $request->image_url,
            'availability_status' => $request->availability_status,
            'is_active' => $request->has('is_active'),
        ]);

        // 3. Redirect
        return redirect()->route('owner.menu.index')
                         ->with('status', 'Data menu berhasil diperbarui.');
    }

    /**
     * Menghapus menu dari database (DESTROY).
     */
    public function destroy(MenuItem $menuItem)
    {
        // Hapus menu
        $menuItem->delete();

        // Redirect
        return redirect()->route('owner.menu.index')
                         ->with('status', 'Menu berhasil dihapus.');
    }
}
