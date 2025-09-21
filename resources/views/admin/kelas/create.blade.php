@extends('layouts.app_admin')

@section('title', 'Tambah Kelas')
@section('page_title', 'Tambah Kelas Baru')

@push('styles')
    {{-- Memuat Toastr untuk notifikasi yang konsisten --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    {{-- Memuat Select2 untuk dropdown yang lebih baik --}}
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <style>
        .form-group label {
            font-weight: 600;
        }
        /* Menambahkan shadow untuk konsistensi card */
        .card.card-outline.card-info.shadow {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }
        .select2-container--default .select2-selection--single {
            height: calc(2.25rem + 2px);
            padding-top: 0.375rem;
            padding-bottom: 0.375rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: calc(2.25rem + 2px);
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            {{-- Menambahkan kelas shadow pada card --}}
            <div class="card card-outline card-info shadow">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus-circle mr-1"></i> Form Tambah Kelas Baru
                    </h3>
                </div>
                <form action="{{ route('admin.kelas.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        {{-- Blok pesan error bawaan diganti dengan notifikasi Toastr melalui script di bawah --}}

                        <div class="form-group">
                            <label for="nama_kelas">Nama Kelas <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                </div>
                                <input type="text" name="nama_kelas" class="form-control @error('nama_kelas') is-invalid @enderror" id="nama_kelas" placeholder="Contoh: X RPL 1" value="{{ old('nama_kelas') }}" required>
                                @error('nama_kelas')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="tingkat">Tingkat <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-sort-numeric-up-alt"></i></span>
                                </div>
                                <input type="text" name="tingkat" class="form-control @error('tingkat') is-invalid @enderror" id="tingkat" placeholder="Contoh: X, XI, XII" value="{{ old('tingkat') }}" required>
                                @error('tingkat')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="jurusan_id">Jurusan</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-ruler"></i></span>
                                </div>
                                <select name="jurusan_id" id="jurusan_id" class="form-control select2bs4 @error('jurusan_id') is-invalid @enderror" style="width: 100%;">
                                    <option value="">-- Pilih Jurusan (Opsional) --</option>
                                    @foreach ($jurusans as $jurusan)
                                        <option value="{{ $jurusan->id }}" {{ old('jurusan_id') == $jurusan->id ? 'selected' : '' }}>{{ $jurusan->nama_jurusan }}</option>
                                    @endforeach
                                </select>
                                @error('jurusan_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="wali_kelas_id">Wali Kelas</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                </div>
                                <select name="wali_kelas_id" id="wali_kelas_id" class="form-control select2bs4 @error('wali_kelas_id') is-invalid @enderror" style="width: 100%;">
                                    <option value="">-- Pilih Wali Kelas (Opsional) --</option>
                                    @foreach ($gurus as $guru)
                                        <option value="{{ $guru->id }}" {{ old('wali_kelas_id') == $guru->id ? 'selected' : '' }}>{{ $guru->name }}</option>
                                    @endforeach
                                </select>
                                @error('wali_kelas_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-info float-right ml-2">
                            <i class="fas fa-save mr-1"></i> Simpan Kelas
                        </button>
                        <a href="{{ route('admin.kelas.index') }}" class="btn btn-secondary float-right">
                            <i class="fas fa-times-circle mr-1"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Memuat Toastr dan Select2 --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Konfigurasi Toastr
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

            // Inisialisasi Select2 pada dropdown
            $('.select2bs4').select2({
                theme: 'bootstrap4',
                placeholder: function(element) {
                    return $(element).attr('data-placeholder') || '-- Pilih ' + $(element).attr('id').replace('_id', '').replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) + ' (Opsional) --';
                },
                allowClear: true
            });

            // Handle invalid state untuk Select2
            @if ($errors->has('jurusan_id') || $errors->has('wali_kelas_id'))
                @if ($errors->has('jurusan_id'))
                    $('#jurusan_id').next('.select2-container').find('.select2-selection').addClass('is-invalid');
                    $('#jurusan_id').closest('.form-group').append('<span class="invalid-feedback d-block">{{ $errors->first('jurusan_id') }}</span>');
                @endif
                @if ($errors->has('wali_kelas_id'))
                    $('#wali_kelas_id').next('.select2-container').find('.select2-selection').addClass('is-invalid');
                    $('#wali_kelas_id').closest('.form-group').append('<span class="invalid-feedback d-block">{{ $errors->first('wali_kelas_id') }}</span>');
                @endif
            @endif
        });
    </script>
@endpush
