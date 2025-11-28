<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Tampilkan Formulir Review
    public function create(Order $order)
    {
        // Validasi: Harus punya user yg login, status selesai, dan belum pernah direview
        if ($order->user_id !== Auth::id() || $order->status !== 'completed') {
            return redirect()->route('orders.history')->with('error', 'Pesanan tidak valid untuk diulas.');
        }

        if ($order->review) {
            return redirect()->route('orders.history')->with('error', 'Anda sudah mengulas pesanan ini.');
        }

        return view('page.review', ['order' => $order]);
    }

    // Simpan Review
    public function store(Request $request, Order $order)
    {
        $request->validate([
            'rating_stars' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        Review::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'rating_stars' => $request->rating_stars,
            'comment' => $request->comment,
        ]);

        return redirect()->route('orders.history')->with('status', 'Terima kasih atas ulasan Anda!');
    }
}