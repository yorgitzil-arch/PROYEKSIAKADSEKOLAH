@extends('layouts.app_admin')

@section('title', 'Edit Data Siswa')
@section('page_title', 'Edit & Konfirmasi Data Siswa')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <style>
        /* Custom CSS untuk tampilan yang lebih modern (konsisten dengan create.blade.php) */
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
            flex-wrap: wrap; /* Agar responsif */
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

        .btn-warning {
            background: linear-gradient(45deg, #ffc107, #e0a800);
            border: none;
            color: #212529; /* Warna teks gelap untuk kontras */
            box-shadow: 0 4px 8px rgba(255, 193, 7, 0.2);
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(255, 193, 7, 0.3);
            background: linear-gradient(45deg, #e0a800, #ffc107);
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            color: #ffffff;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
            transform: translateY(-1px);
        }

        .input-group-prepend .input-group-text {
            background-color: #e9ecef;
            border-color: #ced4da;
            border-radius: 8px 0 0 8px;
            padding: 0.75rem 1rem;
        }

        .input-group .form-control {
            border-radius: 0 8px 8px 0;
        }

        .form-control-file {
            padding: 0.75rem 1rem;
            height: auto;
        }

        .document-preview {
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .document-preview .btn {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
        .document-preview .text-muted {
            font-size: 0.875rem;
        }

        /* Responsive adjustments */
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
        <div class="col-md-10 offset-md-1">
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit mr-1"></i> Edit Data Siswa: <span class="font-weight-bold">{{ $siswa->name }}</span>
                    </h3>
                </div>
                <form action="{{ route('admin.student-data.update', $siswa->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        {{-- Notifikasi Error/Validasi (via Toastr, ini hanya fallback) --}}
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

                        {{-- Bagian Data Akun & Pribadi --}}
                        <h5 class="mb-3 text-primary"><i class="fas fa-user-circle mr-1"></i> Data Akun & Pribadi</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nis">NIS: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-id-card"></i></span></div>
                                        <input type="text" name="nis" id="nis" class="form-control @error('nis') is-invalid @enderror" value="{{ old('nis', $siswa->nis) }}" placeholder="Nomor Induk Siswa" required>
                                        @error('nis') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nama Lengkap: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user"></i></span></div>
                                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $siswa->name) }}" placeholder="Nama Lengkap Siswa" required>
                                        @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- === KOREKSI PENTING: TAMBAHKAN FIELD NISN DI SINI === --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nisn">NISN:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-id-badge"></i></span></div>
                                        <input type="text" name="nisn" id="nisn" class="form-control @error('nisn') is-invalid @enderror" value="{{ old('nisn', $siswa->nisn) }}" placeholder="Nomor Induk Siswa Nasional">
                                        @error('nisn') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-envelope"></i></span></div>
                                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $siswa->email) }}" placeholder="Email Siswa" required>
                                        @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- ================================================= --}}

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Password Baru (Kosongkan jika tidak diubah):</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-lock"></i></span></div>
                                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password Baru">
                                        @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Konfirmasi Password:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-lock"></i></span></div>
                                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Konfirmasi Password Baru">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="foto_profile">Foto Profil:</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="foto_profile" id="foto_profile" class="custom-file-input @error('foto_profile') is-invalid @enderror">
                                            <label class="custom-file-label" for="foto_profile">Pilih file...</label>
                                        </div>
                                        @error('foto_profile') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                    @if($siswa->foto_profile_path)
                                        <div class="document-preview">
                                            <a href="{{ asset('storage/' . $siswa->foto_profile_path) }}" target="_blank" class="btn btn-sm btn-info"><i class="fas fa-eye mr-1"></i> Lihat Foto Profil</a>
                                            <span class="text-muted"> (File saat ini)</span>
                                        </div>
                                    @else
                                        <small class="form-text text-muted">Belum ada foto profil diunggah.</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tempat_lahir">Tempat Lahir:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span></div>
                                        <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}" placeholder="Tempat Lahir">
                                        @error('tempat_lahir') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_lahir">Tanggal Lahir:</label>
                                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                        <div class="input-group-prepend" data-target="#reservationdate" data-toggle="datetimepicker">
                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                        </div>
                                        <input type="text" name="tanggal_lahir" id="tanggal_lahir" class="form-control datetimepicker-input @error('tanggal_lahir') is-invalid @enderror" data-target="#reservationdate" value="{{ old('tanggal_lahir', $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('Y-m-d') : '') }}" placeholder="YYYY-MM-DD">
                                        @error('tanggal_lahir') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jenis_kelamin">Jenis Kelamin:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-venus-mars"></i></span></div>
                                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror">
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="Laki-laki" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="Perempuan" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                        @error('jenis_kelamin') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="agama">Agama:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-pray"></i></span></div>
                                        <input type="text" name="agama" id="agama" class="form-control @error('agama') is-invalid @enderror" value="{{ old('agama', $siswa->agama) }}" placeholder="Agama">
                                        @error('agama') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_telepon">Nomor Telepon:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-phone"></i></span></div>
                                        <input type="text" name="nomor_telepon" id="nomor_telepon" class="form-control @error('nomor_telepon') is-invalid @enderror" value="{{ old('nomor_telepon', $siswa->nomor_telepon) }}" placeholder="Nomor Telepon (contoh: 0812...)">
                                        @error('nomor_telepon') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="alamat">Alamat:</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-home"></i></span></div>
                                <textarea name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3" placeholder="Alamat Lengkap">{{ old('alamat', $siswa->alamat) }}</textarea>
                                @error('alamat') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Bagian Data Sekolah --}}
                        <h5 class="mb-3 text-primary"><i class="fas fa-school mr-1"></i> Data Sekolah</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kelas_id">Kelas: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-chalkboard"></i></span></div>
                                        <select name="kelas_id" id="kelas_id" class="form-control select2bs4 @error('kelas_id') is-invalid @enderror" style="width: 100%;" required>
                                            <option value="">Pilih Kelas</option>
                                            @foreach($kelas as $k)
                                                <option value="{{ $k->id }}" {{ old('kelas_id', $siswa->kelas_id) == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                                            @endforeach
                                        </select>
                                        @error('kelas_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jurusan_id">Jurusan: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-graduation-cap"></i></span></div>
                                        <select name="jurusan_id" id="jurusan_id" class="form-control select2bs4 @error('jurusan_id') is-invalid @enderror" style="width: 100%;" required>
                                            <option value="">Pilih Jurusan</option>
                                            @foreach($jurusans as $j)
                                                <option value="{{ $j->id }}" {{ old('jurusan_id', $siswa->jurusan_id) == $j->id ? 'selected' : '' }}>{{ $j->nama_jurusan }}</option>
                                            @endforeach
                                        </select>
                                        @error('jurusan_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="wali_kelas_id">Wali Kelas:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user-tie"></i></span></div>
                                        <select name="wali_kelas_id" id="wali_kelas_id" class="form-control select2bs4 @error('wali_kelas_id') is-invalid @enderror" style="width: 100%;">
                                            <option value="">Pilih Wali Kelas</option>
                                            @foreach($gurus as $guru)
                                                <option value="{{ $guru->id }}" {{ old('wali_kelas_id', $siswa->wali_kelas_id) == $guru->id ? 'selected' : '' }}>{{ $guru->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('wali_kelas_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status Siswa: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-check-circle"></i></span></div>
                                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                            <option value="">Pilih Status</option>
                                            <option value="pending" {{ old('status', $siswa->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="confirmed" {{ old('status', $siswa->status) == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                                        </select>
                                        @error('status') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Bagian Data Orang Tua --}}
                        <h5 class="mb-3 text-primary"><i class="fas fa-users mr-1"></i> Data Orang Tua</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_ayah">Nama Ayah:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-male"></i></span></div>
                                        <input type="text" name="nama_ayah" id="nama_ayah" class="form-control @error('nama_ayah') is-invalid @enderror" value="{{ old('nama_ayah', $siswa->nama_ayah) }}" placeholder="Nama Ayah">
                                        @error('nama_ayah') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pekerjaan_ayah">Pekerjaan Ayah:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-briefcase"></i></span></div>
                                        <input type="text" name="pekerjaan_ayah" id="pekerjaan_ayah" class="form-control @error('pekerjaan_ayah') is-invalid @enderror" value="{{ old('pekerjaan_ayah', $siswa->pekerjaan_ayah) }}" placeholder="Pekerjaan Ayah">
                                        @error('pekerjaan_ayah') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_ibu">Nama Ibu:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-female"></i></span></div>
                                        <input type="text" name="nama_ibu" id="nama_ibu" class="form-control @error('nama_ibu') is-invalid @enderror" value="{{ old('nama_ibu', $siswa->nama_ibu) }}" placeholder="Nama Ibu">
                                        @error('nama_ibu') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pekerjaan_ibu">Pekerjaan Ibu:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-briefcase"></i></span></div>
                                        <input type="text" name="pekerjaan_ibu" id="pekerjaan_ibu" class="form-control @error('pekerjaan_ibu') is-invalid @enderror" value="{{ old('pekerjaan_ibu', $siswa->pekerjaan_ibu) }}" placeholder="Pekerjaan Ibu">
                                        @error('pekerjaan_ibu') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Bagian Dokumen Pendukung --}}
                        <h5 class="mb-3 text-primary"><i class="fas fa-file-upload mr-1"></i> Dokumen Pendukung</h5>
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
                                <label for="{{ $type }}">{{ $label }}:</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="{{ $type }}" id="{{ $type }}" class="custom-file-input @error($type) is-invalid @enderror">
                                        <label class="custom-file-label" for="{{ $type }}">Pilih file...</label>
                                    </div>
                                    @error($type) <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                                @php $pathColumn = $type . '_path'; @endphp
                                @if($siswa->$pathColumn)
                                    <div class="document-preview">
                                        <a href="{{ asset('storage/' . $siswa->$pathColumn) }}" target="_blank" class="btn btn-sm btn-info"><i class="fas fa-eye mr-1"></i> Lihat Dokumen</a>
                                        <span class="text-muted"> (File saat ini: {{ basename($siswa->$pathColumn) }})</span>
                                    </div>
                                @else
                                    <small class="form-text text-muted">Belum ada dokumen diunggah. Format: PDF, JPG, PNG (Max 2MB)</small>
                                @endif
                            </div>
                        @endforeach

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning float-right ml-2">
                            <i class="fas fa-sync-alt mr-1"></i> Perbarui Data Siswa
                        </button>
                        <a href="{{ route('admin.student-data.index') }}" class="btn btn-secondary float-right">
                            <i class="fas fa-times-circle mr-1"></i> Batal
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
    <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.id.min.js"></script>
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

            // Initialize Select2 on dropdowns
            $('.select2bs4').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih...',
                allowClear: true
            });

            // Datepicker for tanggal_lahir
            $('#reservationdate').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                language: 'id' // Use Indonesian language if available in the locale file
            });

            // Update custom file input label
            $('.custom-file-input').on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').html(fileName);
            });
        });
    </script>
@endpush
