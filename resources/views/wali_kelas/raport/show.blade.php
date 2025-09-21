@extends('layouts.app_guru')

@section('title', 'Detail Rapor Siswa')
@section('page_title', 'Rapor Siswa: ' . ($siswa->name ?? ''))

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
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
        .table thead th {
            background-color: #f8f9fa;
            color: #495057;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }
        .table tbody tr:hover {
            background-color: #f2f2f2;
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
        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
            color: #fff;
        }
        .btn-info:hover {
            background-color: #138496;
            border-color: #117a8b;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(23, 162, 184, 0.2);
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
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
            color: #fff;
        }
        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(40, 167, 69, 0.2);
        }
        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
        }
        .btn-warning:hover {
            background-color: #e0a800;
            border-color: #d39e00;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(255, 193, 7, 0.2);
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            color: #fff;
        }
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.2);
        }
        .badge {
            font-size: 85%;
            padding: 0.4em 0.6em;
        }
        .profile-img-lg {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #ced4da;
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
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-alt mr-1"></i> Rapor Siswa: <span class="font-weight-bold">{{ $siswa->name }}</span> (NIS: {{ $siswa->nis }})
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('guru.wali-kelas.raports.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Siswa
                        </a>
                    </div>
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

                    <div class="row mb-4 align-items-center">
                        <div class="col-md-2 text-center">
                            @if($siswa->foto_profile_path)
                                <img src="{{ asset('storage/' . $siswa->foto_profile_path) }}" alt="Foto {{ $siswa->name }}" class="profile-img-lg">
                            @else
                                <img src="https://placehold.co/80x80/cccccc/000000?text=No+Photo" alt="No Photo" class="profile-img-lg">
                            @endif
                        </div>
                        <div class="col-md-5">
                            <p class="mb-1"><strong>Nama:</strong> {{ $siswa->name }}</p>
                            <p class="mb-1"><strong>NIS:</strong> {{ $siswa->nis }}</p>
                            <p class="mb-1"><strong>NISN:</strong> {{ $siswa->nisn ?? '-' }}</p>
                            <p class="mb-1"><strong>Kelas:</strong> {{ $kelasWali->nama_kelas ?? '-' }} ({{ $kelasWali->jurusan->nama_jurusan ?? '-' }})</p>
                        </div>
                        <div class="col-md-5 text-md-right">
                            <p class="mb-1"><strong>Tahun Ajaran:</strong> <span class="badge badge-primary">{{ $activeTahunAjaran->nama ?? '-' }}</span></p>
                            <p class="mb-1"><strong>Semester:</strong> <span class="badge badge-secondary">{{ $activeSemester->nama ?? '-' }}</span></p>
                            @if($raport)
                                <p class="mb-1"><strong>Status Rapor:</strong>
                                    <span class="badge badge-{{ $raport->status_final ? 'success' : 'warning' }}">
                                        {{ $raport->status_final ? 'Final' : 'Draft' }}
                                    </span>
                                </p>
                                <p class="mb-1"><strong>Rata-rata Nilai:</strong> {{ $raport->rata_rata_nilai ?? '-' }}</p>
                                <p class="mb-1"><strong>Peringkat:</strong> {{ $raport->peringkat_ke ?? '-' }}</p>
                            @else
                                <p class="mb-1"><strong>Status Rapor:</strong> <span class="badge badge-warning">Belum Digenerate</span></p>
                            @endif
                        </div>
                    </div>

                    <hr>

                    {{-- Bagian Rekap Nilai Mata Pelajaran --}}
                    <div class="card card-info card-outline mt-4">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i> Rekap Nilai Mata Pelajaran</h3>
                            <div class="card-tools">
                                <form action="{{ route('guru.wali-kelas.raports.rekapNilai', $siswa->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('Apakah Anda yakin ingin merekap ulang nilai mata pelajaran? Ini akan memperbarui data nilai akhir.');">
                                        <i class="fas fa-sync-alt mr-1"></i> Rekap Ulang Nilai
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="align-middle text-center">#</th>
                                            <th rowspan="2" class="align-middle text-center">Mata Pelajaran</th>
                                            <th rowspan="2" class="align-middle text-center">KKM</th>
                                            <th colspan="2" class="text-center">Pengetahuan</th>
                                            <th colspan="2" class="text-center">Keterampilan</th>
                                            <th colspan="2" class="text-center">Sikap</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Angka</th>
                                            <th class="text-center">Predikat</th>
                                            <th class="text-center">Angka</th>
                                            <th class="text-center">Predikat</th>
                                            <th class="text-center">Spiritual</th>
                                            <th class="text-center">Sosial</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($rekapNilaiMapel as $rekap)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $rekap->mataPelajaran->nama_mapel ?? '-' }}</td>
                                                <td class="text-center">{{ $rekap->kkm_mapel ?? '-' }}</td>
                                                <td class="text-center">{{ $rekap->nilai_pengetahuan_angka ?? '-' }}</td>
                                                <td class="text-center"><span class="badge badge-success">{{ $rekap->nilai_pengetahuan_predikat ?? '-' }}</span></td>
                                                <td class="text-center">{{ $rekap->nilai_keterampilan_angka ?? '-' }}</td>
                                                <td class="text-center"><span class="badge badge-success">{{ $rekap->nilai_keterampilan_predikat ?? '-' }}</span></td>
                                                <td class="text-center"><span class="badge badge-info">{{ $rekap->nilai_sikap_spiritual_predikat ?? '-' }}</span></td>
                                                <td class="text-center"><span class="badge badge-info">{{ $rekap->nilai_sikap_sosial_predikat ?? '-' }}</span></td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center py-3">Belum ada rekap nilai mata pelajaran. Silakan klik "Rekap Ulang Nilai".</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                <p class="text-muted">
                                    * Deskripsi Pengetahuan: {{ $rekapNilaiMapel->first()->deskripsi_pengetahuan ?? 'Belum ada.' }}
                                </p>
                                <p class="text-muted">
                                    * Deskripsi Keterampilan: {{ $rekapNilaiMapel->first()->deskripsi_keterampilan ?? 'Belum ada.' }}
                                </p>
                                <p class="text-muted">
                                    * Deskripsi Sikap Spiritual: {{ $rekapNilaiMapel->first()->deskripsi_sikap_spiritual ?? 'Belum ada.' }}
                                </p>
                                <p class="text-muted">
                                    * Deskripsi Sikap Sosial: {{ $rekapNilaiMapel->first()->deskripsi_sikap_sosial ?? 'Belum ada.' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Bagian Presensi Akhir --}}
                    <div class="card card-info card-outline mt-4">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-calendar-check mr-1"></i> Presensi Akhir Semester</h3>
                        </div>
                        <form action="{{ route('guru.wali-kelas.raports.storePresensi', $siswa->id) }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label for="sakit">Sakit (Hari)</label>
                                        <input type="number" name="sakit" id="sakit" class="form-control @error('sakit') is-invalid @enderror" value="{{ old('sakit', $presensiAkhir->sakit ?? 0) }}" min="0" required>
                                        @error('sakit') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="izin">Izin (Hari)</label>
                                        <input type="number" name="izin" id="izin" class="form-control @error('izin') is-invalid @enderror" value="{{ old('izin', $presensiAkhir->izin ?? 0) }}" min="0" required>
                                        @error('izin') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="alpha">Tanpa Keterangan (Hari)</label>
                                        <input type="number" name="alpha" id="alpha" class="form-control @error('alpha') is-invalid @enderror" value="{{ old('alpha', $presensiAkhir->alpha ?? 0) }}" min="0" required>
                                        @error('alpha') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Presensi</button>
                            </div>
                        </form>
                    </div>

                    {{-- Bagian Catatan Wali Kelas --}}
                    <div class="card card-info card-outline mt-4">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-sticky-note mr-1"></i> Catatan Wali Kelas</h3>
                        </div>
                        <form action="{{ route('guru.wali-kelas.raports.storeCatatan', $siswa->id) }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="catatan">Catatan Perkembangan Siswa</label>
                                    <textarea name="catatan" id="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="5" placeholder="Tulis catatan perkembangan siswa di sini...">{{ old('catatan', $catatanWaliKelas->catatan ?? '') }}</textarea>
                                    @error('catatan') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Catatan</button>
                            </div>
                        </form>
                    </div>

                    {{-- BAGIAN BARU: Input Data Kepala Sekolah, Status Kenaikan Kelas, dan Info Cetak Rapor --}}
                    <div class="card card-info card-outline mt-4">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-user-tie mr-1"></i> Data Kepala Sekolah, Status Kenaikan Kelas & Info Cetak Rapor</h3>
                        </div>
                        <form action="{{ route('guru.wali-kelas.raports.storeKepalaSekolah', $siswa->id) }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="kepala_sekolah_nama">Nama Kepala Sekolah:</label>
                                    <input type="text" name="kepala_sekolah_nama" id="kepala_sekolah_nama" class="form-control @error('kepala_sekolah_nama') is-invalid @enderror" value="{{ old('kepala_sekolah_nama', $raport->kepala_sekolah_nama ?? '') }}" placeholder="Nama Kepala Sekolah">
                                    @error('kepala_sekolah_nama') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label for="kepala_sekolah_nip">NIP Kepala Sekolah:</label>
                                    <input type="text" name="kepala_sekolah_nip" id="kepala_sekolah_nip" class="form-control @error('kepala_sekolah_nip') is-invalid @enderror" value="{{ old('kepala_sekolah_nip', $raport->kepala_sekolah_nip ?? '') }}" placeholder="NIP Kepala Sekolah">
                                    @error('kepala_sekolah_nip') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                <hr> {{-- Pemisah visual --}}

                                <div class="form-group mb-3">
                                    <label for="status_kenaikan_kelas">Status Kenaikan Kelas:</label>
                                    <select name="status_kenaikan_kelas" id="status_kenaikan_kelas" class="form-control @error('status_kenaikan_kelas') is-invalid @enderror">
                                        <option value="">Pilih Status</option>
                                        <option value="Naik Kelas" {{ (old('status_kenaikan_kelas', $raport->status_kenaikan_kelas ?? '') == 'Naik Kelas') ? 'selected' : '' }}>Naik Kelas</option>
                                        <option value="Tinggal Kelas" {{ (old('status_kenaikan_kelas', $raport->status_kenaikan_kelas ?? '') == 'Tinggal Kelas') ? 'selected' : '' }}>Tinggal Kelas</option>
                                        <option value="Lulus" {{ (old('status_kenaikan_kelas', $raport->status_kenaikan_kelas ?? '') == 'Lulus') ? 'selected' : '' }}>Lulus</option>
                                        <option value="Tidak Lulus" {{ (old('status_kenaikan_kelas', $raport->status_kenaikan_kelas ?? '') == 'Tidak Lulus') ? 'selected' : '' }}>Tidak Lulus</option>
                                    </select>
                                    @error('status_kenaikan_kelas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="saran_kenaikan_kelas">Saran Kenaikan Kelas (Opsional):</label>
                                    <textarea name="saran_kenaikan_kelas" id="saran_kenaikan_kelas" class="form-control @error('saran_kenaikan_kelas') is-invalid @enderror" rows="3" placeholder="Contoh: Naik ke kelas XI dengan predikat baik.">{{ old('saran_kenaikan_kelas', $raport->saran_kenaikan_kelas ?? '') }}</textarea>
                                    @error('saran_kenaikan_kelas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <hr> {{-- Pemisah visual --}}

                                {{-- Input untuk Tempat Cetak dan Tanggal Cetak --}}
                                <div class="form-group mb-3">
                                    <label for="tempat_cetak">Tempat Cetak Rapor:</label>
                                    <input type="text" name="tempat_cetak" id="tempat_cetak" class="form-control @error('tempat_cetak') is-invalid @enderror" value="{{ old('tempat_cetak', $raport->tempat_cetak ?? 'Hilimbaruzu') }}" placeholder="Contoh: Jakarta">
                                    @error('tempat_cetak') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="tanggal_cetak">Tanggal Cetak Rapor:</label>
                                    <div class="input-group date" id="tanggal_cetak_datepicker" data-target-input="nearest">
                                        <input type="text" name="tanggal_cetak" id="tanggal_cetak" class="form-control datetimepicker-input @error('tanggal_cetak') is-invalid @enderror" data-target="#tanggal_cetak_datepicker" value="{{ old('tanggal_cetak', $raport?->tanggal_cetak ? \Carbon\Carbon::parse($raport->tanggal_cetak)->format('d-m-Y') : \Carbon\Carbon::now()->format('d-m-Y')) }}" placeholder="DD-MM-YYYY" required>
                                        <div class="input-group-append" data-target="#tanggal_cetak_datepicker" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                    @error('tanggal_cetak') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Data Rapor Utama</button>
                            </div>
                        </form>
                    </div>

                    {{-- BAGIAN BARU: Input Ekstrakurikuler --}}
                    <div class="card card-info card-outline mt-4">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-running mr-1"></i> Data Ekstrakurikuler</h3>
                        </div>
                        <form action="{{ route('guru.wali-kelas.raports.storeEkstrakurikuler', $siswa->id) }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div id="ekskul-container">
                                    @forelse($ekstrakurikulerRaport as $index => $ekskul)
                                        <div class="form-row mb-3 ekskul-item" data-index="{{ $index }}">
                                            <input type="hidden" name="ekskul[{{ $index }}][id]" value="{{ $ekskul->id ?? '' }}">
                                            <div class="col-md-4">
                                                <label for="nama_ekskul_{{ $index }}">Nama Ekstrakurikuler:</label>
                                                <input type="text" name="ekskul[{{ $index }}][nama_ekskul]" id="nama_ekskul_{{ $index }}" class="form-control" value="{{ old('ekskul.'.$index.'.nama_ekskul', $ekskul->nama_ekskul ?? '') }}" placeholder="Contoh: Pramuka">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="jenis_ekskul_{{ $index }}">Jenis:</label>
                                                <select name="ekskul[{{ $index }}][jenis_ekskul]" id="jenis_ekskul_{{ $index }}" class="form-control">
                                                    <option value="">Pilih Jenis</option>
                                                    <option value="Wajib" {{ (old('ekskul.'.$index.'.jenis_ekskul', $ekskul->jenis_ekskul ?? '') == 'Wajib') ? 'selected' : '' }}>Wajib</option>
                                                    <option value="Pilihan" {{ (old('ekskul.'.$index.'.jenis_ekskul', $ekskul->jenis_ekskul ?? '') == 'Pilihan') ? 'selected' : '' }}>Pilihan</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="predikat_ekskul_{{ $index }}">Predikat:</label>
                                                <select name="ekskul[{{ $index }}][predikat]" id="predikat_ekskul_{{ $index }}" class="form-control">
                                                    <option value="">Pilih Predikat</option>
                                                    <option value="A" {{ (old('ekskul.'.$index.'.predikat', $ekskul->predikat ?? '') == 'A') ? 'selected' : '' }}>A (Sangat Baik)</option>
                                                    <option value="B" {{ (old('ekskul.'.$index.'.predikat', $ekskul->predikat ?? '') == 'B') ? 'selected' : '' }}>B (Baik)</option>
                                                    <option value="C" {{ (old('ekskul.'.$index.'.predikat', $ekskul->predikat ?? '') == 'C') ? 'selected' : '' }}>C (Cukup)</option>
                                                    <option value="D" {{ (old('ekskul.'.$index.'.predikat', $ekskul->predikat ?? '') == 'D') ? 'selected' : '' }}>D (Kurang)</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-danger btn-sm remove-ekskul-item" data-id="{{ $ekskul->id ?? '' }}"><i class="fas fa-trash"></i> Hapus</button>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="form-row mb-3 ekskul-item" data-index="0">
                                            <div class="col-md-4">
                                                <label for="nama_ekskul_0">Nama Ekstrakurikuler:</label>
                                                <input type="text" name="ekskul[0][nama_ekskul]" id="nama_ekskul_0" class="form-control" placeholder="Contoh: Pramuka">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="jenis_ekskul_0">Jenis:</label>
                                                <select name="ekskul[0][jenis_ekskul]" id="jenis_ekskul_0" class="form-control">
                                                    <option value="">Pilih Jenis</option>
                                                    <option value="Wajib">Wajib</option>
                                                    <option value="Pilihan">Pilihan</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="predikat_ekskul_0">Predikat:</label>
                                                <select name="ekskul[0][predikat]" id="predikat_ekskul_0" class="form-control">
                                                    <option value="">Pilih Predikat</option>
                                                    <option value="A">A (Sangat Baik)</option>
                                                    <option value="B">B (Baik)</option>
                                                    <option value="C">C (Cukup)</option>
                                                    <option value="D">D (Kurang)</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-danger btn-sm remove-ekskul-item" style="display: none;"><i class="fas fa-trash"></i> Hapus</button>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                                <button type="button" class="btn btn-secondary btn-sm" id="add-ekskul-item"><i class="fas fa-plus"></i> Tambah Ekstrakurikuler</button>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Ekstrakurikuler</button>
                            </div>
                        </form>
                    </div>


                    {{-- Bagian Generate dan Finalisasi Rapor --}}
                    <div class="card card-success card-outline mt-4">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-cogs mr-1"></i> Pengelolaan Rapor</h3>
                        </div>
                        <div class="card-body text-center">
                            <p class="lead">Setelah semua nilai, presensi, dan catatan lengkap, generate rapor.</p>
                            <form action="{{ route('guru.wali-kelas.raports.generateRaport', $siswa->id) }}" method="POST" class="d-inline mr-2">
                                @csrf
                                <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda yakin ingin menggenerate/memperbarui data rapor utama? Ini akan mengkompilasi semua data.');"
                                    @if($raport && $raport->status_final) disabled @endif>
                                    <i class="fas fa-magic mr-1"></i> Generate/Perbarui Rapor
                                </button>
                            </form>
                            <form action="{{ route('guru.wali-kelas.raports.finalisasiRaport', $siswa->id) }}" method="POST" class="d-inline mr-2">
                                @csrf
                                <button type="submit" class="btn btn-warning" onclick="return confirm('Apakah Anda yakin ingin memfinalisasi rapor ini? Rapor yang sudah final tidak dapat diubah lagi!');"
                                    @if(!$raport || $raport->status_final) disabled @endif>
                                    <i class="fas fa-lock mr-1"></i> Finalisasi Rapor
                                </button>
                            </form>
                            {{-- Tombol cetak akan diaktifkan jika rapor sudah digenerate --}}
                            <a href="{{ route('guru.wali-kelas.raports.print', $siswa->id) }}" class="btn btn-info"
                                @if(!$raport) disabled @endif>
                                <i class="fas fa-print mr-1"></i> Cetak Rapor
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
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

            // Datepicker for tanggal_cetak
            $('#tanggal_cetak_datepicker').datepicker({
                format: 'dd-mm-yyyy',
                language: 'id',
                autoclose: true,
                todayHighlight: true
            });

            // Logika untuk menambahkan/menghapus input Ekstrakurikuler
            let ekskulItemIndex = {{ count($ekstrakurikulerRaport ?? []) > 0 ? count($ekstrakurikulerRaport) : 0 }};

            $('#add-ekskul-item').on('click', function() {
                const newIndex = ekskulItemIndex++;
                const newItem = `
                    <div class="form-row mb-3 ekskul-item" data-index="${newIndex}">
                        <div class="col-md-4">
                            <label for="nama_ekskul_${newIndex}">Nama Ekstrakurikuler:</label>
                            <input type="text" name="ekskul[${newIndex}][nama_ekskul]" id="nama_ekskul_${newIndex}" class="form-control" placeholder="Contoh: Pramuka">
                        </div>
                        <div class="col-md-3">
                            <label for="jenis_ekskul_${newIndex}">Jenis:</label>
                            <select name="ekskul[${newIndex}][jenis_ekskul]" id="jenis_ekskul_${newIndex}" class="form-control">
                                <option value="">Pilih Jenis</option>
                                <option value="Wajib">Wajib</option>
                                <option value="Pilihan">Pilihan</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="predikat_ekskul_${newIndex}">Predikat:</label>
                            <select name="ekskul[${newIndex}][predikat]" id="predikat_ekskul_${newIndex}" class="form-control">
                                <option value="">Pilih Predikat</option>
                                <option value="A">A (Sangat Baik)</option>
                                <option value="B">B (Baik)</option>
                                <option value="C">C (Cukup)</option>
                                <option value="D">D (Kurang)</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm remove-ekskul-item"><i class="fas fa-trash"></i> Hapus</button>
                        </div>
                    </div>
                `;
                $('#ekskul-container').append(newItem);
            });

            // Event delegation for remove button
            $(document).on('click', '.remove-ekskul-item', function() {
                const itemToRemove = $(this).closest('.ekskul-item');
                const ekskulId = $(this).data('id'); // Get the ID if it's an existing record

                if (ekskulId) {
                    // If it's an existing record, we need to send a request to delete it from DB
                    if (confirm('Apakah Anda yakin ingin menghapus ekstrakurikuler ini?')) {
                        $.ajax({
                            url: `/guru/wali-kelas/raports/ekstrakurikuler/${ekskulId}/delete`, // New route for deletion
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                _method: 'DELETE' // Use DELETE method
                            },
                            success: function(response) {
                                toastr.success(response.success || 'Ekstrakurikuler berhasil dihapus.');
                                itemToRemove.remove();
                            },
                            error: function(xhr) {
                                toastr.error(xhr.responseJSON.error || 'Gagal menghapus ekstrakurikuler.');
                                console.error("Error deleting ekskul:", xhr.responseText);
                            }
                        });
                    }
                } else {
                    // If it's a newly added item (no ID), just remove from DOM
                    itemToRemove.remove();
                }
            });
        });
    </script>
@endpush
