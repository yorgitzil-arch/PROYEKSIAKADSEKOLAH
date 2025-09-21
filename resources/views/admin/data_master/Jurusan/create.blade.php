@extends('layouts.app_admin')

@section('title', 'Tambah Jurusan')
@section('page_title', 'Tambah Jurusan Baru')

@push('styles')
    {{-- Menambahkan link Toastr untuk notifikasi yang konsisten --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        /* Menggunakan kelas AdminLTE, hanya tambahkan shadow jika diperlukan */
        .card.card-outline.card-primary.shadow {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }
        /* Style untuk form-label agar lebih tebal dan seragam */
        .form-group label {
            font-weight: 600;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            {{-- Menggunakan card-outline dan shadow untuk konsistensi --}}
            <div class="card card-outline card-primary shadow">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus mr-1"></i> Form Tambah Jurusan
                    </h3>
                </div>
                <form action="{{ route('admin.jurusans.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        {{-- Menampilkan pesan notifikasi dari session menggunakan Toastr --}}
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-times-circle mr-2"></i> {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="nama_jurusan">Nama Jurusan</label>
                            <input type="text" name="nama_jurusan" id="nama_jurusan" class="form-control @error('nama_jurusan') is-invalid @enderror" value="{{ old('nama_jurusan') }}" placeholder="Contoh: Rekayasa Perangkat Lunak">
                            @error('nama_jurusan')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="kode_jurusan">Kode Jurusan</label>
                            <input type="text" name="kode_jurusan" id="kode_jurusan" class="form-control @error('kode_jurusan') is-invalid @enderror" value="{{ old('kode_jurusan') }}" placeholder="Contoh: RPL">
                            @error('kode_jurusan')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi (Opsional)</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="4" placeholder="Deskripsi singkat tentang jurusan">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
                        <a href="{{ route('admin.jurusans.index') }}" class="btn btn-secondary"><i class="fas fa-times mr-1"></i> Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Menambahkan script Toastr untuk notifikasi yang konsisten --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function() {
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };

            // Menampilkan pesan notifikasi dari session
            @if (session('success'))
                toastr.success("{{ session('success') }}");
            @endif
            @if (session('error'))
                toastr.error("{{ session('error') }}");
            @endif
            @if (session('info'))
                toastr.info("{{ session('info') }}");
            @endif

            // Menampilkan pesan error validasi menggunakan Toastr
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error("{{ $error }}");
                @endforeach
            @endif
        });
    </script>
@endpush
