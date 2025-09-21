@extends('layouts.app_admin')

@section('title', 'Tambah Tahun Ajaran')
@section('page_title', 'Tambah Tahun Ajaran Baru')

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
                        <i class="fas fa-plus mr-1"></i> Form Tambah Tahun Ajaran
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.tahun-ajaran.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="tahun">Tahun Ajaran (Contoh: 2023/2024)</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="tahun" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: 2023/2024">
                            @error('nama')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group form-check">
                            {{-- Tambahkan hidden input untuk memastikan nilai 0 terkirim jika checkbox tidak dicentang --}}
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Aktifkan Tahun Ajaran Ini</label>
                            <small class="form-text text-muted">Jika diaktifkan, tahun ajaran lain yang aktif akan dinonaktifkan secara otomatis.</small>
                            @error('is_active')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
                        <a href="{{ route('admin.tahun-ajaran.index') }}" class="btn btn-secondary"><i class="fas fa-times mr-1"></i> Batal</a>
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
