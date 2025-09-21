@extends('layouts.app_admin')

@section('title', 'Tambah Admin')
@section('page_title', 'Tambah Admin Baru')

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card rounded-lg">
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Admin Baru</h3>
                </div>
                <!-- /.card-header -->
                <form action="{{ route('admin.admin-management.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show rounded-lg m-3" role="alert">
                                <h5><i class="icon fas fa-exclamation-triangle mr-2"></i> Terjadi Kesalahan!</h5>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="name">Nama Admin</label>
                            <input type="text" name="name" class="form-control rounded-lg @error('name') is-invalid @enderror" id="name" placeholder="Masukkan nama admin" value="{{ old('name') }}" required>
                            @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control rounded-lg @error('email') is-invalid @enderror" id="email" placeholder="Masukkan email admin" value="{{ old('email') }}" required>
                            @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control rounded-lg @error('password') is-invalid @enderror" id="password" placeholder="Masukkan password" required>
                            @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control rounded-lg" id="password_confirmation" placeholder="Konfirmasi password" required>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary rounded-lg">
                            <i class="fas fa-save mr-1"></i> Simpan Admin
                        </button>
                        <a href="{{ route('admin.admin-management.index') }}" class="btn btn-secondary rounded-lg">
                            <i class="fas fa-times mr-1"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection
