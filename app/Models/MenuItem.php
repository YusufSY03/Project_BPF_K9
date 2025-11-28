<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Import model lain agar bisa dihitung
use App\Models\OrderItem;
use App\Models\Review;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price', 'category', 'image_url', 'is_active', 'availability_status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Hitung Rata-rata Bintang
     */
    public function getRating()
    {
        // 1. Cari semua order yang membeli menu ini
        $orderIds = OrderItem::where('menu_item_id', $this->id)->pluck('order_id');
        
        // 2. Cari review berdasarkan order-order tersebut
        $avg = Review::whereIn('order_id', $orderIds)->avg('rating_stars');

        // 3. Kembalikan nilainya (bulatkan 1 desimal), atau 0 jika belum ada
        return $avg ? round($avg, 1) : 0;
    }

    /**
     * Hitung Jumlah Ulasan
     */
    public function getReviewCount()
    {
        $orderIds = OrderItem::where('menu_item_id', $this->id)->pluck('order_id');
        return Review::whereIn('order_id', $orderIds)->count();
    }
}