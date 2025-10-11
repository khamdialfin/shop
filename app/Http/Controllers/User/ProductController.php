<?php

namespace App\Http\Controllers\User;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
     public function index()
    {
        $products = Product::paginate(12);
    return view('user.product', compact('products'));
    }
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('user.product-show', compact('product'));
    }
}
