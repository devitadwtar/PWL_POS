@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
  <div class="card-header">
    <h3 class="card-title">{{ $page->title }}</h3>
    <div class="card-tools">
      <a href="{{ url('level/create') }}" class="btn btn-sm btn-primary">Tambah</a>
      <button onclick="modalAction('{{ url('level/create_ajax') }}')" class="btn btn-sm btn-success">Tambah Ajax</button>
    </div>
  </div>
  <div class="card-body">
    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered table-striped table-hover table-sm" id="table_level">
      <thead>
        <tr>
          <th>No</th>
          <th>Kode Level</th>
          <th>Nama Level</th>
          <th>Aksi</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<!-- Modal untuk create/edit -->
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
  var dataLevel;
  $(function () {
    dataLevel = $('#table_level').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: "{{ url('/level/list') }}",
        type: 'POST',
        data: {
          _token: '{{ csrf_token() }}'
        }
      },
      columns: [
        { data: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'level_kode' },
        { data: 'level_nama' },
        { data: 'aksi', orderable: false, searchable: false }
      ]
    });
  });

  // Fungsi reload (kalau dibutuhkan setelah save)
  function reloadLevelTable() {
    if (dataLevel) dataLevel.ajax.reload();
  }
</script>
@endpush