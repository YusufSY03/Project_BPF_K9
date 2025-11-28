<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage; // Penting utk hapus foto lama

class MenuManagementController extends Controller
{
    public function index()
    {
        $menuItems = MenuItem::orderBy('id', 'desc')->get();
        return view('dashboard.menuManagement', ['menuItems' => $menuItems]);
    }

    public function create()
    {
        return view('dashboard.menuForm');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'image' => 'nullable|image|max:2048', // Validasi foto maks 2MB
        ]);

        $data = $request->except('image');

        // LOGIKA UPLOAD FOTO BARU
        if ($request->hasFile('image')) {
            // Simpan foto ke folder 'public/menus' dan ambil alamatnya
            $data['image_url'] = $request->file('image')->store('menus', 'public');
        }

        $data['is_active'] = $request->has('is_active'); // Checkbox

        MenuItem::create($data);

        return redirect()->route('owner.menu.index')->with('status', 'Menu berhasil ditambah!');
    }

    public function edit(MenuItem $menuItem)
    {
        return view('dashboard.menuForm', ['menuItem' => $menuItem]);
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('image');

        // LOGIKA GANTI FOTO
        if ($request->hasFile('image')) {
            // 1. Hapus foto lama jika ada
            if ($menuItem->image_url) {
                Storage::disk('public')->delete($menuItem->image_url);
            }
            // 2. Simpan foto baru
            $data['image_url'] = $request->file('image')->store('menus', 'public');
        }

        $data['is_active'] = $request->has('is_active');

        $menuItem->update($data);

        return redirect()->route('owner.menu.index')->with('status', 'Menu berhasil diupdate!');
    }

    public function destroy(MenuItem $menuItem)
    {
        // Hapus foto saat menu dihapus
        if ($menuItem->image_url) {
            Storage::disk('public')->delete($menuItem->image_url);
        }
        $menuItem->delete();
        return redirect()->route('owner.menu.index')->with('status', 'Menu dihapus!');
    }
}