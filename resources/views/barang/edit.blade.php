@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
  <div class="card-header"><h3 class="card-title">{{ $page->title }}</h3></div>
  <div class="card-body">
    <form action="{{ url('barang/' . $barang->barang_id) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="form-group">
        <label>Kode Barang</label>
        <input type="text" name="barang_kode" class="form-control" value="{{ old('barang_kode', $barang->barang_kode) }}">
      </div>
      <div class="form-group">
        <label>Nama Barang</label>
        <input type="text" name="barang_nama" class="form-control" value="{{ old('barang_nama', $barang->barang_nama) }}">
      </div>
      <div class="form-group">
        <label>Harga Beli</label>
        <input type="number" name="harga_beli" class="form-control" value="{{ old('harga_beli', $barang->harga_beli) }}">
      </div>
      <div class="form-group">
        <label>Harga Jual</label>
        <input type="number" name="harga_jual" class="form-control" value="{{ old('harga_jual', $barang->harga_jual) }}">
      </div>
      <div class="form-group">
        <label>Kategori</label>
        <select name="kategori_id" class="form-control">
          @foreach ($kategori as $item)
            <option value="{{ $item->kategori_id }}" {{ $item->kategori_id == $barang->kategori_id ? 'selected' : '' }}>
              {{ $item->kategori_nama }}
            </option>
          @endforeach
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Update</button>
      <a href="{{ url('barang') }}" class="btn btn-danger">Kembali</a>
    </form>
  </div>
</div>
@endsection
