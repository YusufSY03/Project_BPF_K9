<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
        'payment_method',
        'payment_proof',
    ];

    // Relasi: Satu order punya banyak item
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relasi: Order dimiliki oleh satu user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}