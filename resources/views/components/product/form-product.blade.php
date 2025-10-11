

{{-- resources/views/product/components/form-product.blade.php --}}
<div>
    <button type="button" class="btn {{ $id ? 'btn-default' : 'btn-primary' }}" data-toggle="modal" data-target="#formProduct{{ $id ?? '' }}">
                 @if ($id)
                <i class="fas fa-edit"></i>
                  @else
                  Product Baru
                @endif
    </button>

   <div class="modal fade" id="formProduct{{ $id ?? '' }}">
       <form action="{{ route('admin.product.store') }}" 
             method="POST" 
             enctype="multipart/form-data">
           @csrf
           <input type="hidden" name="id" value="{{ $id ?? '' }}">
           <div class="modal-dialog">
               <div class="modal-content">
                   <div class="modal-header">
                       <h4 class="modal-title">
                           {{ $id ? 'Edit Produk' : 'Produk Baru' }}
                       </h4>
                       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                       </button>
                   </div>

                   <div class="modal-body">
                       <div class="form-group my-1">
                           <label>Nama Produk</label>
                           <input type="text" name="name" class="form-control"
                                  value="{{ $id ? $name : old('name') }}">
                       </div>

                       <div class="form-group">
                           <label>Deskripsi</label>
                           <textarea name="description" class="form-control">{{ $id ? $description : old('description') }}</textarea>
                       </div>

                       <div class="form-group">
                           <label>Harga</label>
                           <input type="number" name="price" class="form-control"
                                  value="{{ $id ? $price : old('price') }}" required>
                       </div>

                       <div class="form-group">
                           <label>Stok</label>
                           <input type="number" name="stock" class="form-control"
                                  value="{{ $id ? $stock : old('stock') }}" required>
                       </div>

                       <div class="form-group">
                           <label>Kategori</label>
                           <select name="kategori_id" class="form-control" required>
                               <option value="">-- Pilih Kategori --</option>
                               @foreach ($kategoris as $kategori)
                                   <option value="{{ $kategori->id }}"
                                       {{ ($id && $kategori_id == $kategori->id) || old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                       {{ $kategori->nama_kategori }}
                                   </option>
                               @endforeach
                           </select>
                       </div>

                       <div class="form-group">
                           <label>Gambar Produk</label>
                           <input type="file" name="image" class="form-control">
                           @if ($id && $image)
                               <img src="{{ asset('storage/' . $image) }}" alt="gambar" class="mt-2" width="100">
                           @endif
                       </div>
                   </div>

                   <div class="modal-footer">
                       <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                       <button type="submit" class="btn btn-primary">Simpan</button>
                   </div>
               </div>
           </div>
       </form>
   </div>
</div>
