@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
  <div class="card-header">
    <h3 class="card-title">{{ $page->title }}</h3>
    <div class="card-tools">
       <a href="{{ url('/kategori/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export kategori</a>
      <button onclick="modalAction('{{ url('kategori/create_ajax') }}')" class="btn btn-sm btn-success">Tambah Ajax</button>
      <button onclick="modalAction('{{ url('kategori/import') }}')" class="btn btn-sm btn-info">Import Kategori</button>
    </div>
  </div>
  <div class="card-body">
    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered table-striped table-hover table-sm" id="table_kategori">
      <thead>
        <tr>
          <th>No</th>
          <th>Kode Kategori</th>
          <th>Nama Kategori</th>
          <th>Aksi</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<!-- Modal untuk create/edit/import -->
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('js')
<script>
  // Fungsi load modal
  function modalAction(url = '') {
    $('#myModal').load(url, function () {
      $('#myModal').modal('show');
    });
  }

  // Inisialisasi DataTable
  var dataKategori;
  $(function () {
    dataKategori = $('#table_kategori').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: "{{ url('/kategori/list') }}",
        type: 'POST',
        data: {
          _token: '{{ csrf_token() }}'
        }
      },
      columns: [
        { data: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'kategori_kode' },
        { data: 'kategori_nama' },
        { data: 'aksi', orderable: false, searchable: false }
      ]
    });
  });

  // Fungsi reload (kalau dibutuhkan setelah save)
  function reloadKategoriTable() {
    if (dataKategori) dataKategori.ajax.reload();
  }
</script>
@endpush
