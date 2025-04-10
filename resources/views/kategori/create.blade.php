@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
  <div class="card-header">
    <h3 class="card-title">{{ $page->title }}</h3>
  </div>
  <div class="card-body">
    <form action="{{ url('barang') }}" method="POST">
      @csrf
      <div class="form-group">
        <label for="barang_kode">Kode Barang</label>
        <input type="text" name="barang_kode" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="barang_nama">Nama Barang</label>
        <input type="text" name="barang_nama" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="harga_beli">Harga Beli</label>
        <input type="number" name="harga_beli" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="harga_jual">Harga Jual</label>
        <input type="number" name="harga_jual" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="kategori_id">Kategori</label>
        <select name="kategori_id" class="form-control" required>
            <option value="">- Pilih -</option>
            @foreach ($kategori as $k)
              <option value="{{ $k->kategori_id }}">{{ $k->kategori_nama }}</option>
            @endforeach
          </select>          
      </div>
      <button class="btn btn-primary">Simpan</button>
      <a href="{{ url('barang') }}" class="btn btn-default">Kembali</a>
    </form>
  </div>
</div>
@endsection
