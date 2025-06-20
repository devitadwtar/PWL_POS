@extends('layouts.template')

@section('content')
  <div class="card card-outline card-primary">
    <div class="card-header">
      <h3 class="card-title">{{ $page->title }}</h3>
      <div class="card-tools">
        <a class="btn btn-sm btn-primary mt-1" href="{{ url('supplier/create') }}">Tambah</a>
        <button onclick="modalAction('{{ url('supplier/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah Ajax</button>
        <button onclick="modalAction('{{ url('supplier/import') }}')" class="btn btn-sm btn-info mt-1">Import Data</button>
      </div>
    </div>
    <div class="card-body"> 
      @if (session('success')) 
        <div class="alert alert-success">
          {{ session('success') }}
        </div> 
      @endif 

      @if (session('error')) 
        <div class="alert alert-danger">
          {{ session('error') }}
        </div> 
      @endif 

      <table class="table table-bordered table-striped table-hover table-sm" id="table_supplier"> 
        <thead> 
          <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama</th>
            <th>Alamat</th>
            <th>No Telepon</th>
            <th>Aksi</th>
          </tr> 
        </thead> 
      </table> 
    </div>      
  </div>

  <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('js')
<script>
  // Fungsi membuka modal dengan AJAX
  function modalAction(url = '') {
    $('#myModal').load(url, function() {
      $('#myModal').modal('show');
    });
  }

  var dataSupplier;
  $(document).ready(function() {
    // Inisialisasi DataTables
    dataSupplier = $('#table_supplier').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: "{{ url('supplier/list') }}",
        type: "POST",
        data: {
          _token: '{{ csrf_token() }}'
        }
      },
      columns: [
        {
          data: "DT_RowIndex",
          className: "text-center",
          orderable: false,
          searchable: false
        },
        { data: "supplier_kode" },
        { data: "supplier_nama" },
        { data: "alamat" },
        { data: "telepon" },
        {
          data: "aksi",
          orderable: false,
          searchable: false
        }
      ]
    });
  });

  // Reload data table, misalnya setelah import/upload sukses
  function reloadSupplierTable() {
    if (dataSupplier) dataSupplier.ajax.reload();
  }
</script>
@endpush
