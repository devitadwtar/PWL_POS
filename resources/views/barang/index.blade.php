@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
  <div class="card-header">
    <h3 class="card-title">{{ $page->title }}</h3>
    <div class="card-tools">
      <a href="{{ url('barang/create') }}" class="btn btn-primary btn-sm">Tambah</a>
    </div>
  </div>
  <div class="card-body">
    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered table-striped table-hover table-sm" id="table_barang">
      <thead>
        <tr>
          <th>No</th>
          <th>Kode</th>
          <th>Nama</th>
          <th>Harga Beli</th>
          <th>Harga Jual</th>
          <th>Kategori</th>
          <th>Aksi</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
@endsection

@push('js')
<script>
  $(function () {
    $('#table_barang').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: '{{ url("barang/list") }}',
        type: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      },
      columns: [
        { data: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'barang_kode' },
        { data: 'barang_nama' },
        { data: 'harga_beli' },
        { data: 'harga_jual' },
        { data: 'kategori_nama' },
        { data: 'aksi', orderable: false, searchable: false }
      ]
    });
  });
</script>
@endpush