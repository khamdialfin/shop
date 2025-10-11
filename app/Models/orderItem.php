<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class orderItem extends Model
{
       protected $fillable = ['order_id', 'product_id', 'quantity', 'price'];

    // Relasi ke produk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }   
}
