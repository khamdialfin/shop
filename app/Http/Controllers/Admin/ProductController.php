<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\kategori;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
   public function index(Request $request) 
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
            ->latest()
            ->get();
            
        confirmDelete('Hapus Data', 'Apakah Anda Yakin Ingin Menghapus data ini?');
        $kategoris = Kategori::all();
        
        return view('admin.product.index', compact('products', 'kategoris'));
    }

    public function store(Request $request)
    {
        $id = $request->id;
        $request->validate([
            'name' => 'required|unique:products,name,' .$id, 
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'kategori_id' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

         $product = Product::find($id);

         $imagePath = $product->image ?? null; 

            
        if ($request->hasFile('image')) {
                if ($product && $product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::updateOrCreate(
            ['id' => $id],
            [
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imagePath,
            'kategori_id' => $request->kategori_id,
        ]);

        toast()->success('Data Berhasil Ditambahkan');
        return redirect()->route('admin.product.index');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        toast()->success('Data Berhasil Dihapus');
        return redirect()->route('admin.product.index');
    }
}
