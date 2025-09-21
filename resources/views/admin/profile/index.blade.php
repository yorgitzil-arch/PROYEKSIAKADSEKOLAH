@extends('layouts.app_admin')

@section('title', 'Profil Admin')
@section('page_title', 'Profil Admin')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        .profile-card {
            border-radius: 15px; 
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08); 
            background-color: #ffffff;
            color: #343a40; 
        }

        .profile-user-img {
            border: 5px solid #adb5bd;
            padding: 3px; 
            object-fit: cover;
            width: 150px; 
            height: 150px;
        }

        .profile-username {
            font-size: 1.8rem; 
            font-weight: 600; 
            margin-top: 15px;
            color: #343a40; 
        }

        .text-muted {
            color: #6c757d !important;
        }

        .list-group-item {
            border: none;
            padding-left: 0;
            padding-right: 0;
            background-color: transparent; 
            color: #343a40;
        }

        .list-group-item b {
            color: #495057; 
        }

        .nav-pills .nav-link.active {
            background-color: #007bff; 
            color: #ffffff;
            border-radius: 8px; 
        }

        .nav-pills .nav-link {
            color: #6c757d; 
            font-weight: 500;
            transition: all 0.3s ease; 
        }

        .nav-pills .nav-link:hover {
            color: #007bff; 
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 0.75rem 1rem; 
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 8px;
            padding: 0.75rem 1.25rem; 
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            border-radius: 8px;
            padding: 0.75rem 1.25rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        .custom-file-input ~ .custom-file-label::after {
            content: "Browse"; 
        }


        @media (max-width: 768px) {
            .col-md-4, .col-md-8 {
                width: 100%; 
                margin-bottom: 20px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary card-outline profile-card">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle"
                             src="{{ $admin->profile_picture ? asset('storage/' . $admin->profile_picture) : asset('adminlte/dist/img/user4-128x128.jpg') }}"
                             alt="User profile picture">
                    </div>

                    <h3 class="profile-username text-center">{{ $admin->name }}</h3>
                    <p class="text-muted text-center">Administrator</p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Email</b> <a class="float-right">{{ $admin->email }}</a>
                        </li>
                    </ul>

                    {{-- Tombol Edit Profil akan langsung mengarahkan ke tab settings --}}
                    <a href="#settings" data-toggle="tab" class="btn btn-primary btn-block"><b>Edit Profil</b></a>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Pengaturan Profil --}}
        <div class="col-md-8">
            <div class="card profile-card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Pengaturan Profil</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="settings">
                            {{-- Notifikasi akan ditampilkan menggunakan Toastr --}}
                            <form class="form-horizontal" action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="form-group row">
                                    <label for="inputName" class="col-sm-2 col-form-label">Nama</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="inputName" name="name" value="{{ old('name', $admin->name) }}" placeholder="Nama Lengkap" required>
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="inputEmail" name="email" value="{{ old('email', $admin->email) }}" placeholder="Email" required>
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <label for="current_password" class="col-sm-2 col-form-label">Password Saat Ini</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" placeholder="Isi jika ingin ganti password">
                                        @error('current_password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="password" class="col-sm-2 col-form-label">Password Baru</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Password Baru">
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="password_confirmation" class="col-sm-2 col-form-label">Konfirmasi Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi Password Baru">
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <label for="profile_picture" class="col-sm-2 col-form-label">Gambar Profil</label>
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input @error('profile_picture') is-invalid @enderror" id="profile_picture" name="profile_picture">
                                                <label class="custom-file-label" for="profile_picture">Pilih file</label>
                                            </div>
                                        </div>
                                        @error('profile_picture')
                                        <span class="invalid-feedback d-block" role="alert"> {{-- d-block agar pesan error muncul --}}
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        @if ($admin->profile_picture)
                                            <small class="form-text text-muted mt-2">
                                                {{-- KOREKSI DI SINI: Hapus 'profile_pictures/' --}}
                                                <img src="{{ asset('storage/' . $admin->profile_picture) }}" alt="Current Profile" style="max-width: 100px; height: 100px; border-radius: 50%; object-fit: cover; display: block; margin-bottom: 5px;">
                                                <a href="{{ route('admin.profile.delete-picture') }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus gambar profil?');">Hapus Gambar</a>
                                            </small>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="offset-sm-2 col-sm-10">
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
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

            @if (session('success'))
            toastr.success("{{ session('success') }}");
            @endif

            @if (session('error'))
            toastr.error("{{ session('error') }}");
            @endif

            @if (session('warning'))
            toastr.warning("{{ session('warning') }}");
            @endif

            @if ($errors->any())
            @foreach ($errors->all() as $error)
            toastr.error("{{ $error }}");
            @endforeach
            @endif
        });
    </script>
@endpush