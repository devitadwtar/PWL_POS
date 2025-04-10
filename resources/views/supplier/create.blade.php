@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ url('supplier') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Kode Supplier</label>
                    <input type="text" name="supplier_kode" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Nama Supplier</label>
                    <input type="text" name="supplier_nama" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="alamat" class="form-control" required></textarea>
                </div>
                <div class="form-group">
                    <label>No Telepon</label>
                    <input type="text" name="no_telp" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="{{ url('supplier') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
@endsection
