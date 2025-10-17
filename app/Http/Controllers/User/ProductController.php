<?php

namespace App\Http\Controllers\User;

use App\Models\Product;
use App\Models\Kategori; // Tambahkan ini
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
     public function index(Request $request) // Tambahkan Request $request
    {
        $search = $request->get('search');
        $category = $request->get('category');
        
        $products = Product::with('kategori')
            ->when($search, function($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($category, function($query) use ($category) {
                return $query->where('kategori_id', $category);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);
            
        $categories = Kategori::all(); // Ambil semua kategori untuk filter
        
        return view('user.product', compact('products', 'categories'));
    }
    
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('user.product-show', compact('product'));
    }
}