<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'total_amount',
        'shipping_price',
        'latitude',       
        'longitude',      
        'status',
        'payment_method',
        'payment_proof',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // RELASI BARU: Cek apakah order ini sudah direview
    public function review()
    {
        return $this->hasOne(Review::class);
    }
}