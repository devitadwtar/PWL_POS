@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Kode Supplier</dt>
                <dd class="col-sm-9">{{ $supplier->supplier_kode }}</dd>

                <dt class="col-sm-3">Nama Supplier</dt>
                <dd class="col-sm-9">{{ $supplier->supplier_nama }}</dd>

                <dt class="col-sm-3">Alamat</dt>
                <dd class="col-sm-9">{{ $supplier->alamat }}</dd>

                <dt class="col-sm-3">No Telepon</dt>
                <dd class="col-sm-9">{{ $supplier->no_telp }}</dd>
            </dl>
            <a href="{{ url('supplier') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
@endsection
