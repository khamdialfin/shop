<?php

namespace App\Http\Controllers\User;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
         $products = Product::latest()->take(8)->get(); // ambil 8 produk terbaru
        return view('user.home', compact('products')); 
    }

    public function products()
    {
        return view('user.products');
    }

    public function about()
    {
        return view('user.about');
    }

    public function contact()
    {
        return view('user.contact');
    }
}
