<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'customer_city',
        'customer_postal_code',
        'subtotal',
        'tax',
        'total',
        'payment_method',
        'status'
    ];
    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke item order
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

      public static function generateOrderNumber()
    {
        return 'ORD-' . time() . '-' . rand(1000, 9999);
    }
}
