<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'rating_stars',
        'comment'
    ];

    // Review milik satu Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Review ditulis oleh satu User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}