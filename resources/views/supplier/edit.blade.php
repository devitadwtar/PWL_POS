@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ url('supplier/' . $supplier->supplier_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Kode Supplier</label>
                    <input type="text" name="supplier_kode" class="form-control" value="{{ $supplier->supplier_kode }}" required>
                </div>
                <div class="form-group">
                    <label>Nama Supplier</label>
                    <input type="text" name="supplier_nama" class="form-control" value="{{ $supplier->supplier_nama }}" required>
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="alamat" class="form-control" required>{{ $supplier->alamat }}</textarea>
                </div>
                <div class="form-group">
                    <label>No Telepon</label>
                    <input type="text" name="no_telp" class="form-control" value="{{ $supplier->no_telp }}" required>
                </div>
                <button type="submit" class="btn btn-success">Update</button>
                <a href="{{ url('supplier') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
@endsection
