<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'menu_item_id',
        'quantity',
        'price',
    ];

    // Relasi: Item menu milik order tertentu
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi: Item ini merujuk ke menu apa
    public function menu()
    {
        return $this->belongsTo(MenuItem::class, 'menu_item_id');
    }
}