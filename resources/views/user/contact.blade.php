@extends('layouts.user.app')
@section('title', 'Contact Us')

@section('content')
<h1>Hubungi Kami</h1>
<form>
    <div class="mb-3">
        <label for="nama" class="form-label">Nama</label>
        <input type="text" id="nama" class="form-control">
    </div>
    <div class="mb-3">
        <label for="pesan" class="form-label">Pesan</label>
        <textarea id="pesan" class="form-control"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Kirim</button>
</form>
@endsection
