@extends('layouts.app_admin')

@section('title', 'Edit Semester')
@section('page_title', 'Edit Semester') {{-- Menambahkan page_title untuk konsistensi --}}

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
        <div class="col-md-12">
            {{-- Menggunakan card-outline dan shadow untuk konsistensi --}}
            <div class="card card-outline card-primary shadow">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit mr-1"></i> Form Edit Semester
                    </h3>
                </div>
                <div class="card-body">
                    {{-- Alert Messages (opsional, jika Anda ingin menampilkan di sini juga) --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form action="{{ route('admin.semester.update', $semester->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="nama">Nama Semester</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $semester->nama) }}" required placeholder="Contoh: Ganjil, Genap">
                            @error('nama')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="tahun_ajaran_id">Tahun Ajaran</label>
                            <select class="form-control @error('tahun_ajaran_id') is-invalid @enderror" id="tahun_ajaran_id" name="tahun_ajaran_id" required>
                                <option value="">Pilih Tahun Ajaran</option>
                                @foreach ($tahunAjarans as $tahunAjaran)
                                    <option value="{{ $tahunAjaran->id }}" {{ old('tahun_ajaran_id', $semester->tahun_ajaran_id) == $tahunAjaran->id ? 'selected' : '' }}>
                                        {{ $tahunAjaran->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tahun_ajaran_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group form-check">
                            {{-- Tambahkan hidden input untuk memastikan nilai 0 terkirim jika checkbox tidak dicentang --}}
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $semester->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Aktifkan Semester Ini</label>
                            <small class="form-text text-muted">Jika diaktifkan, semester lain di tahun ajaran yang sama akan dinonaktifkan secara otomatis.</small>
                            @error('is_active')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Update</button>
                        <a href="{{ route('admin.semester.index') }}" class="btn btn-secondary"><i class="fas fa-times mr-1"></i> Batal</a>
                    </form>
                </div>
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
        });
    </script>
@endpush
