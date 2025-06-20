@extends('layouts.template')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header">
            <h5>Profil Pengguna</h5>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3 text-center">
                    @if ($user->foto)
                        <img src="{{ asset('storage/foto/' . $user->foto) }}" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <img src="{{ asset('adminlte/dist/img/default-user.png') }}" class="rounded-circle" style="width: 150px; height: 150px;">
                    @endif
                </div>

                <div class="mb-3">
                    <label for="foto" class="form-label">Upload Foto Baru</label>
                    <input type="file" class="form-control" name="foto">
                    @error('foto')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</div>
@endsection
