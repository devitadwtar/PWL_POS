@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
        </div>
        <div class="card-body">
            @if (!$supplier)
                <div class="alert alert-danger">Data tidak ditemukan.</div>
                <a href="{{ url('supplier') }}" class="btn btn-sm btn-default">Kembali</a>
            @else
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>Kode Supplier</th>
                        <td>{{ $supplier->supplier_kode }}</td>
                    </tr>
                    <tr>
                        <th>Nama Supplier</th>
                        <td>{{ $supplier->supplier_nama }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{ $supplier->alamat }}</td>
                    </tr>
                    <tr>
                        <th>No Telepon</th>
                        <td>{{ $supplier->no_telp }}</td>
                    </tr>
                </table>
                <a href="{{ url('supplier') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
            @endif
        </div>
    </div>
@endsection
