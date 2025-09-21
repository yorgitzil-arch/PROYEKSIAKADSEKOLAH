@extends('layouts.app_admin')

@section('title', 'Edit Akun Guru')
@section('page_title', 'Edit Akun Guru')

@push('styles')
    <!-- CSS untuk Toastr dan styling kustom -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        /* Styling Umum untuk Konsistensi */
        .card {
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
            background-color: #ffffff;
            color: #343a40;
        }

        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, .125);
            padding: 1.5rem;
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
        }

        .form-control {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            height: auto;
            border: 1px solid #ced4da;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .input-group-text {
            border-radius: 8px 0 0 8px;
            background-color: #e9ecef;
            border: 1px solid #ced4da;
            border-right: none;
            color: #495057;
        }

        .input-group > .form-control {
            border-left: none;
        }

        /* Styling untuk Tombol */
        .btn-warning {
            background: linear-gradient(45deg, #ffc107, #e0a800);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            color: #343a40;
            box-shadow: 0 4px 8px rgba(255, 193, 7, 0.2);
            transition: all 0.3s ease;
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(255, 193, 7, 0.3);
            background: linear-gradient(45deg, #e0a800, #ffc107);
        }

        .btn-secondary {
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        /* Styling untuk pesan error */
        .alert-danger h5 {
            font-size: 1.25rem;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-edit mr-1"></i> Form Edit Akun Guru: <span class="font-weight-bold">{{ $guru->name }}</span>
                    </h3>
                </div>
                <form action="{{ route('admin.guru-management.update', $guru->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
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
                            <label for="name">Nama Guru <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Masukkan nama guru" value="{{ old('name', $guru->name) }}" required>
                                @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nip">NIP <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-id-card-alt"></i></span>
                                </div>
                                <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" id="nip" placeholder="Masukkan NIP guru" value="{{ old('nip', $guru->nip) }}" required>
                                @error('nip')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- BAGIAN BARU: Kategori Guru (PNS/Non-PNS) --}}
                        <div class="form-group">
                            <label for="kategori">Kategori Guru <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-tags"></i></span>
                                </div>
                                <select name="kategori" id="kategori" class="form-control @error('kategori') is-invalid @enderror" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="PNS" {{ old('kategori', $guru->kategori) == 'PNS' ? 'selected' : '' }}>PNS</option>
                                    <option value="Non-PNS" {{ old('kategori', $guru->kategori) == 'Non-PNS' ? 'selected' : '' }}>Non-PNS</option>
                                </select>
                                @error('kategori')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        {{-- END BAGIAN BARU --}}

                        <div class="form-group">
                            <label for="email">Email (Opsional)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Masukkan email guru (jika ada)" value="{{ old('email', $guru->email) }}">
                                @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password">Password Baru (Kosongkan jika tidak ingin mengubah)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                </div>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Password Baru">
                                @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                </div>
                                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Konfirmasi password baru">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end">
                        <a href="{{ route('admin.guru-management.index') }}" class="btn btn-secondary mr-2">
                            <i class="fas fa-times-circle mr-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-sync-alt mr-1"></i> Perbarui Akun Guru
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(function () {
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
        });
    </script>
@endpush
