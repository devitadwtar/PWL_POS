@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
  <div class="card-header">
    <h3 class="card-title">{{ $page->title }}</h3>
  </div>
  <div class="card-body">
    <form action="{{ url('level/'.$level->level_id) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="form-group">
        <label for="level_kode">Kode Level</label>
        <input type="text" class="form-control" id="level_kode" name="level_kode" value="{{ old('level_kode', $level->level_kode) }}" required>
        @error('level_kode')
        <small class="form-text text-danger">{{ $message }}</small>
        @enderror
      </div>
      <div class="form-group">
        <label for="level_nama">Nama Level</label>
        <input type="text" class="form-control" id="level_nama" name="level_nama" value="{{ old('level_nama', $level->level_nama) }}" required>
        @error('level_nama')
        <small class="form-text text-danger">{{ $message }}</small>
        @enderror
      </div>
      <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
      <a href="{{ url('level') }}" class="btn btn-sm btn-default">Kembali</a>
    </form>
  </div>
</div>
@endsection