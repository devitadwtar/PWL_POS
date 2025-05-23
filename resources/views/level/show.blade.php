@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
  <div class="card-header">
    <h3 class="card-title">{{ $page->title }}</h3>
  </div>
  <div class="card-body">
    @if (!$level)
      <div class="alert alert-danger">Data tidak ditemukan.</div>
      <a href="{{ url('level') }}" class="btn btn-sm btn-default">Kembali</a>
    @else
      <table class="table table-bordered table-striped">
        <tr>
          <th>ID</th>
          <td>{{ $level->level_id }}</td>
        </tr>
        <tr>
          <th>Kode Level</th>
          <td>{{ $level->level_kode }}</td>
        </tr>
        <tr>
          <th>Nama Level</th>
          <td>{{ $level->level_nama }}</td>
        </tr>
      </table>
      <a href="{{ url('level') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
    @endif
  </div>
</div>
@endsection