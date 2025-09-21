@extends('layouts.app_admin')

@section('title', 'Kirim Apresiasi Guru')
@section('page_title', 'Kirim Apresiasi Guru')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        /* Custom CSS untuk tampilan yang lebih modern */
        .card {
            border-radius: 15px; /* Sudut lebih membulat */
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08); /* Bayangan lebih halus dan dalam */
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
            display: flex; /* Untuk ikon dan teks */
            align-items: center;
            gap: 8px; /* Jarak antara ikon dan teks judul */
        }

        .form-group label {
            font-weight: 600; /* Label lebih tebal */
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border-radius: 8px; /* Sudut membulat pada input/select */
            border: 1px solid #ced4da;
            padding: 0.75rem 1rem;
            height: auto; /* Otomatis menyesuaikan padding */
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

        /* Styling untuk Tombol */
        .btn {
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px; /* Jarak antara ikon dan teks */
        }

        .btn-primary { /* Digunakan untuk tombol Kirim Apresiasi */
            background: linear-gradient(45deg, #007bff, #0056b3);
            border: none;
            color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 123, 255, 0.3);
            background: linear-gradient(45deg, #0056b3, #007bff);
        }

        .btn-info { /* Digunakan untuk tombol Lihat Riwayat Apresiasi */
            background: linear-gradient(45deg, #17a2b8, #138496);
            border: none;
            color: #ffffff;
            box-shadow: 0 4px 8px rgba(23, 162, 184, 0.2);
        }
        .btn-info:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(23, 162, 184, 0.3);
            background: linear-gradient(45deg, #138496, #17a2b8);
        }

        /* Input Group Styling for icons */
        .input-group-prepend .input-group-text {
            background-color: #e9ecef;
            border-color: #ced4da;
            border-radius: 8px 0 0 8px;
            padding: 0.75rem 1rem;
        }

        .input-group .form-control {
            border-radius: 0 8px 8px 0;
        }

        /* Textarea specific styling */
        textarea.form-control {
            min-height: 120px; /* Tinggi minimum untuk textarea */
            resize: vertical; /* Hanya bisa resize vertikal */
        }

        /* Responsive adjustments */
        @media (max-width: 575.98px) {
            .btn {
                width: 100%;
                margin-bottom: 10px;
            }
            .card-footer .btn:last-child {
                margin-bottom: 0; /* Remove margin for the last button on small screens */
            }
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2"> {{-- Centering the form and making it narrower --}}
            <div class="card card-outline card-success"> {{-- Added card-outline and card-success class --}}
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-paper-plane mr-1"></i> Form Kirim Apresiasi kepada Guru {{-- Added icon --}}
                    </h3>
                </div>
                <div class="card-body">
                    {{-- Pesan Sukses/Error - Will be handled by Toastr now, removing direct alerts --}}
                    {{-- @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif --}}

                    <form action="{{ route('admin.appreciation-management.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="guru_id">Pilih Guru <span class="text-danger">*</span></label>
                            <div class="input-group"> {{-- Added input-group for icon --}}
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user-tie"></i></span> {{-- Icon for Guru --}}
                                </div>
                                <select name="guru_id" id="guru_id" class="form-control @error('guru_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Guru --</option>
                                    @foreach($gurus as $guru)
                                        <option value="{{ $guru->id }}" {{ old('guru_id') == $guru->id ? 'selected' : '' }}>{{ $guru->name }}</option>
                                    @endforeach
                                </select>
                                @error('guru_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="title">Judul Apresiasi <span class="text-danger">*</span></label>
                            <div class="input-group"> {{-- Added input-group for icon --}}
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-heading"></i></span> {{-- Icon for Title --}}
                                </div>
                                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Contoh: Apresiasi untuk Dedikasi" required> {{-- Added placeholder --}}
                                @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="message">Pesan Apresiasi <span class="text-danger">*</span></label>
                            <div class="input-group"> {{-- Added input-group for icon --}}
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-comment-alt"></i></span> {{-- Icon for Message --}}
                                </div>
                                <textarea name="message" id="message" class="form-control @error('message') is-invalid @enderror" rows="5" placeholder="Tulis pesan apresiasi Anda di sini..." required>{{ old('message') }}</textarea> {{-- Added placeholder --}}
                                @error('message')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="category">Kategori Apresiasi <span class="text-danger">*</span></label>
                            <div class="input-group"> {{-- Added input-group for icon --}}
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-tags"></i></span> {{-- Icon for Category --}}
                                </div>
                                <select name="category" id="category" class="form-control @error('category') is-invalid @enderror" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="baik" {{ old('category') == 'baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="sangat luar biasa" {{ old('category') == 'sangat luar biasa' ? 'selected' : '' }}>Sangat Luar Biasa</option>
                                    <option value="buruk" {{ old('category') == 'buruk' ? 'selected' : '' }}>Buruk</option>
                                </select>
                                @error('category')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                </div>
                <div class="card-footer text-right"> {{-- Aligned buttons to the right --}}
                    <button type="submit" class="btn btn-primary ml-2"> {{-- Added icon and margin-left --}}
                        <i class="fas fa-paper-plane mr-1"></i> Kirim Apresiasi
                    </button>
                    <a href="{{ route('admin.appreciation-management.index') }}" class="btn btn-info"> {{-- Changed to btn-info and added icon --}}
                        <i class="fas fa-history mr-1"></i> Lihat Riwayat Apresiasi
                    </a>
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
        // Use jQuery.noConflict(true) to avoid conflicts with other libraries
        var $j = jQuery.noConflict(true);

        document.addEventListener('DOMContentLoaded', function() {
            // Toastr Configuration
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

            // Display all validation errors using Toastr
            @if ($errors->any())
            @foreach ($errors->all() as $error)
            toastr.error("{{ $error }}");
            @endforeach
            @endif
        });
    </script>
@endpush
