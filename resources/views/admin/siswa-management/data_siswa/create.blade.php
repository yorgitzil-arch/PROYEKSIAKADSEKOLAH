@extends('layouts.app_admin')

@section('title', 'Tambah Data Siswa')
@section('page_title', 'Tambah Data Siswa Baru')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            background-color: #ffffff;
            border: none;
            margin-bottom: 30px;
        }
        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, .08);
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #343a40;
            margin-bottom: 0;
        }
        .form-group label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }
        .form-control {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 0.65rem 1rem;
            height: auto;
            font-size: 0.95rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.1rem rgba(13, 110, 253, 0.15);
        }
        .form-control.is-invalid {
            border-color: #dc3545;
        }
        .invalid-feedback {
            font-size: 0.85rem;
        }
        .btn {
            border-radius: 8px;
            padding: 0.65rem 1.25rem;
            font-weight: 600;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: #fff;
        }
        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(13, 110, 253, 0.2);
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            color: #ffffff;
        }
        .btn-secondary:hover {
            background-color: #5c636a;
            border-color: #565e64;
            transform: translateY(-1px);
        }
        .select2-container--bootstrap4 .select2-selection--single {
            height: calc(0.95rem + 1rem + 2px);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.95rem;
        }
        .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
            height: calc(0.95rem + 1rem + 2px);
        }
        h5 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #343a40;
            border-left: 4px solid #0d6efd;
            padding-left: 10px;
        }
        hr {
            border: 0;
            height: 1px;
            background-color: #e2e8f0;
            margin: 2.5rem 0;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-plus mr-1"></i> Formulir Akun Siswa Baru
                    </h3>
                    <!-- Ini adalah div yang sudah benar, fungsinya untuk memisahkan konten ke sisi kanan -->
                    <div class="card-tools">
                        <a href="{{ route('admin.student-data.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Data Siswa
                        </a>
                    </div>
                </div>
                <form action="{{ route('admin.student-data.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        {{-- Alert Messages --}}
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                <i class="fas fa-times-circle mr-2"></i> {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <h5 class="mb-3">Informasi Akun & Dasar</h5>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="nis">NIS (Nomor Induk Siswa) <span class="text-danger">*</span></label>
                                <input type="text" name="nis" id="nis" class="form-control @error('nis') is-invalid @enderror" value="{{ old('nis') }}" required>
                                @error('nis') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="nisn">NISN (Nomor Induk Siswa Nasional)</label>
                                <input type="text" name="nisn" id="nisn" class="form-control @error('nisn') is-invalid @enderror" value="{{ old('nisn') }}">
                                @error('nisn') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="email">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="password">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="password_confirmation">Konfirmasi Password <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="foto_profile">Upload Foto Profil (Opsional)</label>
                                <div class="custom-file">
                                    <input type="file" name="foto_profile" id="foto_profile" class="custom-file-input @error('foto_profile') is-invalid @enderror">
                                    <label class="custom-file-label" for="foto_profile">Pilih file...</label>
                                </div>
                                <small class="form-text text-muted">Format: JPG, PNG, GIF, SVG. Max: 2MB.</small>
                                @error('foto_profile') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3">Data Pribadi</h5>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="tempat_lahir">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" value="{{ old('tempat_lahir') }}" placeholder="Contoh: Jakarta">
                                @error('tempat_lahir') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="tanggal_lahir_datepicker">Tanggal Lahir</label>
                                <div class="input-group date" id="tanggal_lahir_datepicker_group" data-target-input="nearest">
                                    <input type="text" name="tanggal_lahir" id="tanggal_lahir_datepicker" class="form-control datetimepicker-input @error('tanggal_lahir') is-invalid @enderror" data-target="#tanggal_lahir_datepicker_group" value="{{ old('tanggal_lahir') }}" placeholder="YYYY-MM-DD">
                                    <div class="input-group-append" data-target="#tanggal_lahir_datepicker_group" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                    @error('tanggal_lahir') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="jenis_kelamin">Jenis Kelamin</label>
                                <select name="jenis_kelamin" id="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="agama">Agama</label>
                                <input type="text" name="agama" id="agama" class="form-control @error('agama') is-invalid @enderror" value="{{ old('agama') }}" placeholder="Contoh: Islam">
                                @error('agama') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat Lengkap</label>
                            <textarea name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3" placeholder="Alamat lengkap siswa">{{ old('alamat') }}</textarea>
                            @error('alamat') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="nomor_telepon">Nomor Telepon</label>
                            <input type="text" name="nomor_telepon" id="nomor_telepon" class="form-control @error('nomor_telepon') is-invalid @enderror" value="{{ old('nomor_telepon') }}" placeholder="Contoh: 081234567890">
                            @error('nomor_telepon') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3">Data Sekolah</h5>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="jurusan_id">Jurusan <span class="text-danger">*</span></label>
                                <select name="jurusan_id" id="jurusan_id" class="form-control select2bs4 @error('jurusan_id') is-invalid @enderror" style="width: 100%;" required>
                                    <option value="">Pilih Jurusan</option>
                                    @foreach($jurusans as $jurusan)
                                        <option value="{{ $jurusan->id }}" {{ old('jurusan_id') == $jurusan->id ? 'selected' : '' }}>{{ $jurusan->nama_jurusan }}</option>
                                    @endforeach
                                </select>
                                @error('jurusan_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="kelas_id">Kelas <span class="text-danger">*</span></label>
                                <select name="kelas_id" id="kelas_id" class="form-control select2bs4 @error('kelas_id') is-invalid @enderror" style="width: 100%;" required>
                                    <option value="">Pilih Kelas</option>
                                    @foreach($kelas as $k)
                                        <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                                    @endforeach
                                </select>
                                @error('kelas_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="wali_kelas_id">Wali Kelas</label>
                                <select name="wali_kelas_id" id="wali_kelas_id" class="form-control select2bs4 @error('wali_kelas_id') is-invalid @enderror" style="width: 100%;">
                                    <option value="">Pilih Wali Kelas (Opsional)</option>
                                    @foreach($gurus as $guru)
                                        <option value="{{ $guru->id }}" {{ old('wali_kelas_id') == $guru->id ? 'selected' : '' }}>{{ $guru->name }}</option>
                                    @endforeach
                                </select>
                                @error('wali_kelas_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="status">Status Akun <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                                </select>
                                @error('status') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3">Data Orang Tua</h5>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="nama_ayah">Nama Ayah</label>
                                <input type="text" name="nama_ayah" id="nama_ayah" class="form-control @error('nama_ayah') is-invalid @enderror" value="{{ old('nama_ayah') }}" placeholder="Nama Lengkap Ayah">
                                @error('nama_ayah') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="pekerjaan_ayah">Pekerjaan Ayah</label>
                                <input type="text" name="pekerjaan_ayah" id="pekerjaan_ayah" class="form-control @error('pekerjaan_ayah') is-invalid @enderror" value="{{ old('pekerjaan_ayah') }}" placeholder="Pekerjaan Ayah">
                                @error('pekerjaan_ayah') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="nama_ibu">Nama Ibu</label>
                                <input type="text" name="nama_ibu" id="nama_ibu" class="form-control @error('nama_ibu') is-invalid @enderror" value="{{ old('nama_ibu') }}" placeholder="Nama Lengkap Ibu">
                                @error('nama_ibu') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="pekerjaan_ibu">Pekerjaan Ibu</label>
                                <input type="text" name="pekerjaan_ibu" id="pekerjaan_ibu" class="form-control @error('pekerjaan_ibu') is-invalid @enderror" value="{{ old('pekerjaan_ibu') }}" placeholder="Pekerjaan Ibu">
                                @error('pekerjaan_ibu') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3">Dokumen Pendukung</h5>
                        @php
                            $documents = [
                                'ijazah' => 'Ijazah',
                                'raport' => 'Raport',
                                'kk' => 'Kartu Keluarga (KK)',
                                'ktp_ortu' => 'KTP Orang Tua',
                                'akta_lahir' => 'Akta Lahir',
                                'sk_lulus' => 'Surat Keterangan Lulus (SKL)',
                                'kis' => 'Kartu Indonesia Sehat (KIS)',
                                'kks' => 'Kartu Keluarga Sejahtera (KKS) / Bantuan Sosial',
                            ];
                        @endphp
                        @foreach($documents as $type => $label)
                            <div class="form-group">
                                <label for="{{ $type }}">{{ $label }} (Opsional)</label>
                                <div class="custom-file">
                                    <input type="file" name="{{ $type }}" id="{{ $type }}" class="custom-file-input @error($type) is-invalid @enderror">
                                    <label class="custom-file-label" for="{{ $type }}">Pilih file...</label>
                                </div>
                                <small class="form-text text-muted">Format: PDF, JPG, PNG. Max: 2MB.</small>
                                @error($type) <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        @endforeach
                    </div>
                    <div class="card-footer d-flex justify-content-end align-items-center" style="gap: 10px;">
                        <a href="{{ route('admin.student-data.index') }}" class="btn btn-secondary"><i class="fas fa-times-circle mr-1"></i> Batal</a>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Buat Data Siswa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.id.min.js"></script>
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

            @if (session('success'))
                toastr.success("{{ session('success') }}");
            @endif

            @if (session('error'))
                toastr.error("{{ session('error') }}");
            @endif

            // Menampilkan error validasi via Toastr (jika ada)
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error("{{ $error }}");
                @endforeach
            @endif

            // Inisialisasi Datepicker
            $('#tanggal_lahir_datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                language: 'id',
                orientation: 'bottom auto'
            });

            // Inisialisasi Select2
            $('.select2bs4').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih...',
                allowClear: true
            });

            // Update custom file input label
            $('.custom-file-input').on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').html(fileName);
            });
        });
    </script>
@endpush
