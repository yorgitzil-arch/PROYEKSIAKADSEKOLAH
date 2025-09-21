@extends('layouts.app_admin')

@section('title', 'Pengaturan Sekolah')
@section('page_title', 'Pengaturan Sekolah')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        .card {
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
            background-color: #ffffff;
            color: #343a40;
            margin-bottom: 30px;
        }
        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, .125);
            padding: 1.25rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #343a40;
            margin-bottom: 0;
        }
        .form-group label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }
        .form-control {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 0.75rem 1rem;
            height: auto;
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        .form-control.is-invalid {
            border-color: #dc3545;
        }
        .invalid-feedback {
            font-size: 0.875rem;
        }
        .btn {
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            color: #fff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2);
        }
        .logo-preview {
            max-width: 150px;
            height: auto;
            border: 1px solid #ddd;
            padding: 5px;
            margin-top: 10px;
            border-radius: 8px;
            background-color: #f8f9fa;
        }
        .form-check-label {
            margin-left: 0.5rem;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-cog mr-1"></i> Pengaturan Informasi Sekolah
                    </h3>
                </div>
                <div class="card-body">
                    {{-- Alert Messages --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                            <i class="fas fa-times-circle mr-2"></i> {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form action="{{ route('admin.settings.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="nama_sekolah">Nama Sekolah</label>
                            <input type="text" name="nama_sekolah" id="nama_sekolah" class="form-control @error('nama_sekolah') is-invalid @enderror" value="{{ old('nama_sekolah', $settings->nama_sekolah ?? '') }}" placeholder="Masukkan nama sekolah">
                            @error('nama_sekolah')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="nssn">NSSN</label>
                            <input type="text" name="nssn" id="nssn" class="form-control @error('nssn') is-invalid @enderror" value="{{ old('nssn', $settings->nssn ?? '') }}" placeholder="Masukkan NSSN sekolah">
                            @error('nssn')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="npsn">NPSN</label>
                            <input type="text" name="npsn" id="npsn" class="form-control @error('npsn') is-invalid @enderror" value="{{ old('npsn', $settings->npsn ?? '') }}" placeholder="Masukkan NPSN sekolah">
                            @error('npsn')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="alamat">Alamat Sekolah</label>
                            <textarea name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3" placeholder="Masukkan alamat lengkap sekolah">{{ old('alamat', $settings->alamat ?? '') }}</textarea>
                            @error('alamat')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="logo_kiri">Logo Kiri (Contoh: Logo Sekolah)</label>
                            <input type="file" name="logo_kiri" id="logo_kiri" class="form-control-file @error('logo_kiri') is-invalid @enderror">
                            @error('logo_kiri')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            @if($settings->logo_kiri_path)
                                <p class="mt-2">Logo saat ini:</p>
                                <img src="{{ asset('storage/' . $settings->logo_kiri_path) }}" alt="Logo Kiri" class="logo-preview">
                                <div class="form-check mt-2">
                                    <input type="checkbox" name="clear_logo_kiri" id="clear_logo_kiri" value="1" class="form-check-input">
                                    <label class="form-check-label" for="clear_logo_kiri">Hapus Logo Kiri</label>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="logo_kanan">Logo Kanan (Contoh: Logo Provinsi/Kabupaten)</label>
                            <input type="file" name="logo_kanan" id="logo_kanan" class="form-control-file @error('logo_kanan') is-invalid @enderror">
                            @error('logo_kanan')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            @if($settings->logo_kanan_path)
                                <p class="mt-2">Logo saat ini:</p>
                                <img src="{{ asset('storage/' . $settings->logo_kanan_path) }}" alt="Logo Kanan" class="logo-preview">
                                <div class="form-check mt-2">
                                    <input type="checkbox" name="clear_logo_kanan" id="clear_logo_kanan" value="1" class="form-check-input">
                                    <label class="form-check-label" for="clear_logo_kanan">Hapus Logo Kanan</label>
                                </div>
                            @endif
                        </div>

                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Pengaturan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
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

            @if (session('success'))
                toastr.success("{{ session('success') }}");
            @endif

            @if (session('error'))
                toastr.error("{{ session('error') }}");
            @endif
        });
    </script>
@endpush
