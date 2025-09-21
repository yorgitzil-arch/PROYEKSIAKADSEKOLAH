@extends('layouts.app_admin')

@section('title', 'Tambah Mata Pelajaran')
@section('page_title', 'Tambah Mata Pelajaran Baru')

@push('styles')
    <!-- CSS untuk Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        /* Custom CSS untuk tampilan yang lebih modern */
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
            color: #dc3545;
            display: block;
            margin-top: 0.25rem;
        }

        /* Styling untuk Tombol */
        .btn {
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            background: linear-gradient(45deg, #007bff, #0056b3);
            border: none;
            color: #ffffff;
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #0056b3, #007bff);
        }
        
        .btn-success {
            background: linear-gradient(45deg, #28a745, #1e7e34);
            border: none;
            color: #ffffff;
        }

        .btn-success:hover {
            background: linear-gradient(45deg, #1e7e34, #28a745);
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            color: #ffffff;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }

        /* Input Group Styling */
        .input-group-prepend .input-group-text {
            background-color: #e9ecef;
            border-color: #ced4da;
            border-radius: 8px 0 0 8px;
            padding: 0.75rem 1rem;
        }

        .input-group .form-control {
            border-radius: 0 8px 8px 0;
        }

        /* Tambahan untuk responsif */
        @media (max-width: 575.98px) {
            .btn {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus-square mr-1"></i> Form Tambah Mata Pelajaran Baru
                    </h3>
                </div>
                <form action="{{ route('admin.mata-pelajaran.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        
                        <div class="form-group">
                            <label for="nama_mapel">Nama Mata Pelajaran <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-book"></i></span>
                                </div>
                                <input type="text" name="nama_mapel" class="form-control @error('nama_mapel') is-invalid @enderror" id="nama_mapel" placeholder="Masukkan nama mata pelajaran (contoh: Matematika, Bahasa Indonesia)" value="{{ old('nama_mapel') }}" required>
                                @error('nama_mapel')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="kode_mapel">Kode Mata Pelajaran (Opsional)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                </div>
                                <input type="text" name="kode_mapel" class="form-control @error('kode_mapel') is-invalid @enderror" id="kode_mapel" placeholder="Masukkan kode mata pelajaran (misal: MTK01, BI02)" value="{{ old('kode_mapel') }}">
                                @error('kode_mapel')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="kelompok">Kelompok Mata Pelajaran</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                </div>
                                <select name="kelompok" id="kelompok" class="form-control @error('kelompok') is-invalid @enderror">
                                    <option value="">-- Pilih Kelompok --</option>
                                    @foreach($kelompokOptions as $option)
                                        <option value="{{ $option }}" {{ old('kelompok') == $option ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kelompok')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="kkm">KKM (Kriteria Ketuntasan Minimal) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-percent"></i></span>
                                </div>
                                <input type="number" name="kkm" class="form-control @error('kkm') is-invalid @enderror" id="kkm" placeholder="Masukkan KKM (contoh: 75)" value="{{ old('kkm') }}" required min="0" max="100">
                                @error('kkm')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.mata-pelajaran.index') }}" class="btn btn-secondary mr-2">
                                <i class="fas fa-times-circle"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-success" id="submitButton">
                                <i class="fas fa-save"></i> Simpan Mata Pelajaran
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(function() {
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

            // Menampilkan pesan notifikasi dari session jika ada
            @if (session('success'))
                toastr.success("{{ session('success') }}");
            @endif

            @if (session('error'))
                toastr.error("{{ session('error') }}");
            @endif

            // Menampilkan pesan error validasi dari Toastr
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error("{{ $error }}");
                @endforeach
            @endif
        });
    </script>
@endpush
