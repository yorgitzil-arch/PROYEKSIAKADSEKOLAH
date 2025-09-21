@extends('layouts.app_admin')

@section('title', 'Tambah Akun Siswa')
@section('page_title', 'Tambah Akun Siswa Baru')

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card card-outline card-success"> {{-- Mengubah card-primary menjadi card-outline card-success --}}
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-plus mr-1"></i> Form Tambah Akun Siswa Baru {{-- Menambahkan ikon --}}
                    </h3>
                </div>
                <form action="{{ route('admin.siswa-management.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        {{-- Notifikasi Error/Validasi --}}
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                                <i class="fas fa-times-circle mr-2"></i> {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                                <h5><i class="icon fas fa-exclamation-triangle mr-2"></i> Terjadi Kesalahan Validasi!</h5>
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
                            <label for="name">Nama Siswa <span class="text-danger">*</span></label>
                            <div class="input-group"> {{-- Tambahkan input-group --}}
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span> {{-- Ikon --}}
                                </div>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Masukkan nama siswa" value="{{ old('name') }}" required>
                                @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nis">NIS <span class="text-danger">*</span></label>
                            <div class="input-group"> {{-- Tambahkan input-group --}}
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-id-card"></i></span> {{-- Ikon --}}
                                </div>
                                <input type="text" name="nis" class="form-control @error('nis') is-invalid @enderror" id="nis" placeholder="Masukkan NIS siswa" value="{{ old('nis') }}" required>
                                @error('nis')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- === KOREKSI PENTING: TAMBAHKAN FIELD NISN DI SINI === --}}
                        <div class="form-group">
                            <label for="nisn">NISN (Opsional)</label> {{-- NISN biasanya opsional atau bisa diisi nanti --}}
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-id-badge"></i></span> {{-- Ikon untuk NISN --}}
                                </div>
                                <input type="text" name="nisn" class="form-control @error('nisn') is-invalid @enderror" id="nisn" placeholder="Masukkan NISN siswa (jika ada)" value="{{ old('nisn') }}">
                                @error('nisn')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        {{-- ================================================= --}}

                        <div class="form-group">
                            <label for="email">Email (Opsional)</label>
                            <div class="input-group"> {{-- Tambahkan input-group --}}
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span> {{-- Ikon --}}
                                </div>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Masukkan email siswa (jika ada)" value="{{ old('email') }}">
                                @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password">Password <span class="text-danger">*</span></label>
                            <div class="input-group"> {{-- Tambahkan input-group --}}
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span> {{-- Ikon --}}
                                </div>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Masukkan password" required>
                                @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password <span class="text-danger">*</span></label>
                            <div class="input-group"> {{-- Tambahkan input-group --}}
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-lock-open"></i></span> {{-- Ikon --}}
                                </div>
                                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Konfirmasi password" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="kelas_id">Pilih Kelas <span class="text-danger">*</span></label>
                            <div class="input-group"> {{-- Tambahkan input-group --}}
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-school"></i></span> {{-- Ikon --}}
                                </div>
                                {{-- Ganti select biasa dengan class untuk Select2 --}}
                                <select name="kelas_id" id="kelas_id" class="form-control select2 @error('kelas_id') is-invalid @enderror" style="width: 100%;" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach ($kelas as $k)
                                        <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                            {{ $k->nama_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kelas_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success float-right ml-2"> {{-- Ubah ke btn-success dan float-right --}}
                            <i class="fas fa-save mr-1"></i> Simpan Akun Siswa
                        </button>
                        <a href="{{ route('admin.siswa-management.index') }}" class="btn btn-secondary float-right"> {{-- float-right juga --}}
                            <i class="fas fa-times-circle mr-1"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <style>
        /* Optional: CSS untuk menyesuaikan tampilan jika perlu */
        .form-group label {
            font-weight: 600; /* Sedikit lebih tebal */
        }
        .select2-container--default .select2-selection--single {
            height: calc(2.25rem + 2px); /* Menyesuaikan tinggi select2 dengan input form-control */
            padding-top: 0.375rem;
            padding-bottom: 0.375rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: calc(2.25rem + 2px);
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2 pada dropdown kelas
            $('#kelas_id').select2({
                theme: 'bootstrap4',
                placeholder: '-- Pilih Kelas --',
                allowClear: true // Opsi untuk menghapus pilihan
            });

            // Handle invalid state for Select2 when validation fails
            @if ($errors->has('kelas_id'))
            $('#kelas_id').next('.select2-container').find('.select2-selection').addClass('is-invalid');
            $('#kelas_id').closest('.form-group').append('<span class="invalid-feedback d-block">{{ $errors->first('kelas_id') }}</span>');
            @endif
        });
    </script>
@endpush
