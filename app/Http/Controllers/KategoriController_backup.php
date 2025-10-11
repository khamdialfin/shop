<?php

namespace App\Http\Controllers;

use App\Models\kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KategoriController extends Controller
{
    public function index(){
        $kategori = kategori::all();
        confirmDelete('Hapus Data', 'Apakah Anda Yakin Ingin Menghapus data ini?');
        return view('kategori.index', compact('kategori'));
    }

    public function store(Request $request)
    {
        $id = $request->id;
        $request->validate([
            'nama_kategori' => 'required|unique:kategoris,nama_kategori,' .$id,
            'deskripsi' => 'required|max:100|min:10'
        ],[
            'nama_kategori.required' => 'Nama Kategori Harus Diisi',
            'nama_kategori.unique' => 'Nama Kategori Sudah Ada',
            'deskripsi.required' => 'Deskripsi Harus Disi',
            'deskripsi.max' => 'Deskripsi Maksimal 100 Karakter',
            'deskripsi.min' => 'Deskripsi Minimal 10 Karakter',
        ]);
        
        Kategori::updateOrCreate(
            ['id' => $id],
            [
                'nama_kategori' => $request->nama_kategori,
                'slug' => Str::slug($request->nama_kategori),
                'deskripsi' => $request->deskripsi,
            ]
        );

        toast()->success('Data Berhasil Ditambahkan');
        return redirect()->route('master-data.kategori.index');
    }

    public function destroy(String $id) {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();
        toast()->success('Data Berhasil Dihapus');
        return redirect()->route('master-data.kategori.index');
    }
}
