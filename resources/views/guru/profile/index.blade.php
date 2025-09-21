@extends('layouts.app_guru')

@section('title', 'Profil Saya')
@section('page_title', 'Profil Guru')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        /* Kamu bisa menambahkan CSS custom di sini seperti di profil admin,
           contoh: .profile-user-img, .custom-file-input, dll. */
        .profile-user-img {
            border: 3px solid #adb5bd;
            margin: 0 auto;
            padding: 3px;
            width: 100px; /* Sesuaikan ukuran */
            height: 100px; /* Sesuaikan ukuran */
            object-fit: cover;
            display: block;
            border-radius: 50%;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Edit Profil Guru</h3>
                </div>
                {{-- TAMBAHKAN enctype="multipart/form-data" DI SINI --}}
                <form action="{{ route('guru.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') {{-- Gunakan metode PUT untuk update --}}
                    <div class="card-body">
                        {{-- Notifikasi Toastr akan menggantikan ini nanti, tapi untuk sekarang biarkan dulu --}}
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

                        {{-- TAMPILKAN GAMBAR PROFIL SAAT INI --}}
                        <div class="form-group text-center">
                            <label for="profile_picture">Gambar Profil Saat Ini</label>
                            <div>
                                <img class="profile-user-img"
                                     src="{{ $guru->profile_picture ? asset('storage/' . $guru->profile_picture) : asset('adminlte/dist/img/user4-128x128.jpg') }}"
                                     alt="User profile picture">
                            </div>
                            @if ($guru->profile_picture)
                                <a href="{{ route('guru.profile.delete-picture') }}" class="btn btn-sm btn-outline-danger mt-2" onclick="return confirm('Yakin ingin menghapus gambar profil?');">Hapus Gambar</a>
                            @endif
                        </div>
                        <hr>

                        <div class="form-group">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $guru->name) }}" required>
                            @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $guru->email) }}" required>
                            @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- INPUT FILE UNTUK GAMBAR PROFIL BARU --}}
                        <div class="form-group">
                            <label for="profile_picture">Ubah Gambar Profil</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('profile_picture') is-invalid @enderror" id="profile_picture" name="profile_picture">
                                    <label class="custom-file-label" for="profile_picture">Pilih file</label>
                                </div>
                            </div>
                            @error('profile_picture')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Max 2MB, format: JPG, PNG, GIF, SVG.</small>
                        </div>

                        {{-- Kolom password DIHAPUS dari sini --}}
                        {{-- Jika ingin menambah fitur ganti password, kamu bisa tambahkan lagi seperti di admin --}}
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
                        <a href="{{ route('guru.dashboard') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>

    <script>
        $(function () {
            bsCustomFileInput.init(); // Inisialisasi custom file input

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

            // Notifikasi dari session
            @if (session('success'))
                toastr.success("{{ session('success') }}");
            @endif

            @if (session('error'))
                toastr.error("{{ session('error') }}");
            @endif

            @if (session('warning'))
                toastr.warning("{{ session('warning') }}");
            @endif

            // Menampilkan error validasi dari $errors->any()
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error("{{ $error }}");
                @endforeach
            @endif
        });
    </script>
@endpush