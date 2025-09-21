@extends('layouts.app_siswa')

@section('title', 'Profil Saya')
@section('page_title', 'Profil Siswa')

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Edit Profil Siswa</h3>
                </div>
                <form action="{{ route('siswa.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT') {{-- Gunakan metode PUT untuk update --}}
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <h5><i class="icon fas fa-ban"></i> Terjadi Kesalahan!</h5>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <p class="text-muted text-sm">Untuk mengubah NISN, NIS, atau data pribadi lainnya, silakan kunjungi halaman <a href="{{ route('siswa.data-diri.index') }}">Data Diri Lengkap</a>.</p>

                        <div class="form-group">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $siswa->name) }}" required>
                            @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $siswa->email) }}" required>
                            @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        {{-- === KOREKSI PENTING: TAMBAHKAN NISN SEBAGAI FIELD DISABLED === --}}
                        <div class="form-group">
                            <label for="nisn">NISN</label>
                            <input type="text" id="nisn" class="form-control" value="{{ $siswa->nisn ?? '-' }}" disabled>
                            <small class="form-text text-muted">NISN hanya dapat diubah melalui halaman Data Diri Lengkap atau oleh Admin.</small>
                        </div>
                        {{-- ============================================================= --}}
                        <div class="form-group">
                            <label for="nis">NIS</label>
                            <input type="text" id="nis" class="form-control" value="{{ $siswa->nis }}" disabled>
                            <small class="form-text text-muted">NIS tidak dapat diubah.</small>
                        </div>

                        {{-- Kolom password DIHAPUS dari sini sesuai instruksi --}}
                        {{-- <hr>
                        <p class="text-muted">Isi kolom di bawah ini hanya jika Anda ingin mengubah password.</p>
                        <div class="form-group">
                            <label for="current_password">Password Saat Ini</label>
                            <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror">
                            @error('current_password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password">Password Baru</label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                        </div> --}}
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="{{ route('siswa.dashboard') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
