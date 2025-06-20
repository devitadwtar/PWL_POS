@extends('layouts.template')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <!-- Kolom Kiri: Upload Foto -->
                    <div class="col-md-4 text-center">
                        @if($user->foto)
                            <img src="{{ asset('uploads/foto/' . $user->foto) }}" 
                                 class="img-fluid rounded-circle mb-3" 
                                 style="width: 150px; height: 150px; object-fit: cover;" 
                                 alt="Foto Profil">
                        @else
                            <p class="mb-3">Belum ada foto</p>
                        @endif

                        <div class="mb-3">
                            <input type="file" name="foto" class="form-control">
                            @error('foto')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Info User -->
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" value="{{ $user->username }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" value="{{ $user->nama }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Level</label>
                            <input type="text" class="form-control" value="{{ $user->level->level_nama ?? '-' }}" readonly>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection
