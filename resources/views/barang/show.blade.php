@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
  <div class="card-header"><h3 class="card-title">{{ $page->title }}</h3></div>
  <div class="card-body">
    <table class="table table-bordered">
      <tr>
        <th>Kode</th>
        <td>{{ $barang->barang_kode }}</td>
      </tr>
      <tr>
        <th>Nama</th>
        <td>{{ $barang->barang_nama }}</td>
      </tr>
      <tr>
        <th>Harga Beli</th>
        <td>{{ $barang->harga_beli }}</td>
      </tr>
      <tr>
        <th>Harga Jual</th>
        <td>{{ $barang->harga_jual }}</td>
      </tr>
      <tr>
        <th>Kategori</th>
        <td>{{ $barang->kategori->kategori_nama ?? '-' }}</td>
      </tr>
    </table>
    <a href="{{ url('barang') }}" class="btn btn-danger mt-3">Kembali</a>
  </div>
</div>
@endsection
