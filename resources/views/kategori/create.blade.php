@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
  <div class="card-header">
    <h3 class="card-title">{{ $page->title }}</h3>
  </div>
  <div class="card-body">
    <form action="{{ url('kategori') }}" method="POST">
      @csrf
      <div class="form-group">
        <label for="kategori_kode">Kode Kategori</label>
        <input type="text" name="kategori_kode" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="kategori_nama">Nama Kategori</label>
        <input type="text" name="kategori_nama" class="form-control" required>
      </div>
      <button class="btn btn-primary">Simpan</button>
      <a href="{{ url('kategori') }}" class="btn btn-default">Kembali</a>
    </form>
  </div>
</div>
@endsection
