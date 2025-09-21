@extends('layouts.app_guru')

@section('title', 'Input/Edit Nilai Siswa')
@section('page_title', 'Input & Edit Nilai Siswa')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <style>
        /* Custom styling for cards and buttons */
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

        .table thead th {
            background-color: #f8f9fa;
            color: #495057;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody tr:hover {
            background-color: #f2f2f2;
        }

        .badge {
            font-size: 85%;
            padding: 0.4em 0.6em;
        }

        .img-circle {
            border-radius: 50%;
        }

        .img-size-32 {
            width: 32px;
            height: 32px;
            object-fit: cover;
        }

        .nav-tabs .nav-item .nav-link {
            border-radius: 10px 10px 0 0;
            font-weight: 600;
            color: #495057;
            background-color: #e9ecef;
            border: 1px solid transparent;
            border-bottom-color: #dee2e6;
            margin-bottom: -1px;
            padding: 0.75rem 1.25rem;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-item .nav-link.active {
            color: #007bff;
            background-color: #fff;
            border-color: #dee2e6 #dee2e6 #fff;
        }

        .tab-content {
            padding: 20px 0;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-graduate mr-1"></i> Input Nilai untuk: <span class="font-weight-bold">{{ $siswa->name }}</span> (NIS: {{ $siswa->nis }})
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('guru.grades.index', ['assignment_id' => $assignment->id]) }}" class="btn btn-secondary btn-sm">
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

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>Mata Pelajaran:</strong> <span class="badge badge-primary">{{ $assignment->mataPelajaran->nama_mapel ?? '-' }}</span></p>
                            <p><strong>Kelas:</strong> <span class="badge badge-info">{{ $assignment->kelas->nama_kelas ?? '-' }} ({{ $assignment->kelas->jurusan->nama_jurusan ?? '-' }})</span></p>
                        </div>
                        <div class="col-md-6 text-md-right">
                            <p><strong>Tahun Ajaran:</strong> {{ $activeTahunAjaran->nama ?? '-' }}</p>
                            <p><strong>Semester:</strong> {{ $activeSemester->nama ?? '-' }}</p>
                            <p><strong>KKM Mata Pelajaran:</strong> <span class="badge badge-success">{{ $kkm }}</span></p>
                        </div>
                    </div>

                    <ul class="nav nav-tabs" id="gradeTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="akademik-tab" data-toggle="tab" href="#akademik" role="tab" aria-controls="akademik" aria-selected="true">
                                <i class="fas fa-book-open mr-1"></i> Nilai Akademik
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="keterampilan-tab" data-toggle="tab" href="#keterampilan" role="tab" aria-controls="keterampilan" aria-selected="false">
                                <i class="fas fa-tools mr-1"></i> Nilai Keterampilan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="sikap-tab" data-toggle="tab" href="#sikap" role="tab" aria-controls="sikap" aria-selected="false">
                                <i class="fas fa-heart mr-1"></i> Nilai Sikap
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content" id="gradeTabContent">
                        {{-- TAB NILAI AKADEMIK --}}
                        <div class="tab-pane fade show active" id="akademik" role="tabpanel" aria-labelledby="akademik-tab">
                            <div class="card card-primary card-outline mt-3">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-plus-circle mr-1"></i> Tambah/Edit Nilai Akademik</h3>
                                </div>
                                <form id="formAkademik" action="{{ route('guru.grades.storeAkademik', ['assignment' => $assignment->id, 'siswa' => $siswa->id]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="nilai_id" id="akademik_nilai_id">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 form-group">
                                                <label for="jenis_nilai">Jenis Nilai <span class="text-danger">*</span></label>
                                                <select name="jenis_nilai" id="jenis_nilai" class="form-control @error('jenis_nilai') is-invalid @enderror" required>
                                                    <option value="">Pilih Jenis</option>
                                                    <option value="ulangan_harian">Ulangan Harian</option>
                                                    <option value="tugas">Tugas</option>
                                                    <option value="uts">UTS</option>
                                                    <option value="uas">UAS</option>
                                                    <option value="sumatif_lain">Sumatif Lain</option>
                                                </select>
                                                @error('jenis_nilai') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <label for="nama_nilai">Nama Nilai</label>
                                                <input type="text" name="nama_nilai" id="nama_nilai" class="form-control @error('nama_nilai') is-invalid @enderror" placeholder="Contoh: UH Bab 1, Tugas Proyek">
                                                @error('nama_nilai') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <label for="nilai">Nilai Angka <span class="text-danger">*</span></label>
                                                <input type="number" name="nilai" id="nilai" class="form-control @error('nilai') is-invalid @enderror" min="0" max="100" required placeholder="0-100">
                                                @error('nilai') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label for="tanggal_nilai">Tanggal Nilai <span class="text-danger">*</span></label>
                                                <div class="input-group date" id="akademik_datepicker" data-target-input="nearest">
                                                    <input type="text" name="tanggal_nilai" id="tanggal_nilai" class="form-control datetimepicker-input @error('tanggal_nilai') is-invalid @enderror" data-target="#akademik_datepicker" required placeholder="YYYY-MM-DD">
                                                    <div class="input-group-append" data-target="#akademik_datepicker" data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                                @error('tanggal_nilai') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label for="keterangan">Keterangan</label>
                                                <textarea name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="2" placeholder="Keterangan tambahan (opsional)"></textarea>
                                                @error('keterangan') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer text-right">
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Nilai Akademik</button>
                                        <button type="button" class="btn btn-secondary" id="resetAkademikForm"><i class="fas fa-redo mr-1"></i> Reset Form</button>
                                    </div>
                                </form>
                            </div>

                            <div class="card card-info card-outline mt-4">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-list-alt mr-1"></i> Daftar Nilai Akademik</h3>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Jenis Nilai</th>
                                                    <th>Nama Nilai</th>
                                                    <th>Nilai</th>
                                                    <th>Predikat</th>
                                                    <th>KKM</th>
                                                    <th>Tanggal</th>
                                                    <th>Keterangan</th>
                                                    <th style="width: 120px;">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($nilaiAkademik as $na)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ ucwords(str_replace('_', ' ', $na->jenis_nilai)) }}</td>
                                                        <td>{{ $na->nama_nilai ?? '-' }}</td>
                                                        <td>{{ $na->nilai }}</td>
                                                        <td><span class="badge badge-success">{{ $na->nilai_predikat ?? '-' }}</span></td>
                                                        <td>{{ $na->kkm ?? '-' }}</td>
                                                        <td>{{ $na->tanggal_nilai->format('d M Y') }}</td>
                                                        <td>{{ $na->keterangan ?? '-' }}</td>
                                                        <td>
                                                            <button type="button" class="btn btn-warning btn-sm edit-akademik"
                                                                data-id="{{ $na->id }}"
                                                                data-jenis_nilai="{{ $na->jenis_nilai }}"
                                                                data-nama_nilai="{{ $na->nama_nilai }}"
                                                                data-nilai="{{ $na->nilai }}"
                                                                data-tanggal_nilai="{{ $na->tanggal_nilai->format('Y-m-d') }}"
                                                                data-keterangan="{{ $na->keterangan }}">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <form action="{{ route('guru.grades.destroyAkademik', $na->id) }}" method="POST" class="d-inline delete-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus nilai ini?');">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="9" class="text-center py-3">Belum ada nilai akademik diinput.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- TAB NILAI KETERAMPILAN --}}
                        <div class="tab-pane fade" id="keterampilan" role="tabpanel" aria-labelledby="keterampilan-tab">
                            <div class="card card-primary card-outline mt-3">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-plus-circle mr-1"></i> Tambah/Edit Nilai Keterampilan</h3>
                                </div>
                                <form id="formKeterampilan" action="{{ route('guru.grades.storeKeterampilan', ['assignment' => $assignment->id, 'siswa' => $siswa->id]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="nilai_keterampilan_id" id="keterampilan_nilai_id">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 form-group">
                                                <label for="jenis_keterampilan">Jenis Keterampilan <span class="text-danger">*</span></label>
                                                <select name="jenis_keterampilan" id="jenis_keterampilan" class="form-control @error('jenis_keterampilan') is-invalid @enderror" required>
                                                    <option value="">Pilih Jenis</option>
                                                    <option value="praktik">Praktik</option>
                                                    <option value="proyek">Proyek</option>
                                                    <option value="portofolio">Portofolio</option>
                                                    <option value="unjuk_kerja">Unjuk Kerja</option>
                                                    <option value="lain-lain">Lain-lain</option>
                                                </select>
                                                @error('jenis_keterampilan') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <label for="nama_penilaian">Nama Penilaian</label>
                                                <input type="text" name="nama_penilaian" id="nama_penilaian" class="form-control @error('nama_penilaian') is-invalid @enderror" placeholder="Contoh: Praktik Memasak, Proyek Sains">
                                                @error('nama_penilaian') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <label for="nilai_keterampilan">Nilai Angka <span class="text-danger">*</span></label>
                                                <input type="number" name="nilai" id="nilai_keterampilan" class="form-control @error('nilai') is-invalid @enderror" min="0" max="100" required placeholder="0-100">
                                                @error('nilai') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label for="tanggal_nilai_keterampilan">Tanggal Nilai <span class="text-danger">*</span></label>
                                                <div class="input-group date" id="keterampilan_datepicker" data-target-input="nearest">
                                                    <input type="text" name="tanggal_nilai_keterampilan" id="tanggal_nilai_keterampilan" class="form-control datetimepicker-input @error('tanggal_nilai_keterampilan') is-invalid @enderror" data-target="#keterampilan_datepicker" required placeholder="YYYY-MM-DD">
                                                    <div class="input-group-append" data-target="#keterampilan_datepicker" data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                                @error('tanggal_nilai_keterampilan') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label for="deskripsi_keterampilan">Deskripsi Keterampilan</label>
                                                <textarea name="deskripsi_keterampilan" id="deskripsi_keterampilan" class="form-control @error('deskripsi_keterampilan') is-invalid @enderror" rows="2" placeholder="Deskripsi pencapaian keterampilan"></textarea>
                                                @error('deskripsi_keterampilan') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer text-right">
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Nilai Keterampilan</button>
                                        <button type="button" class="btn btn-secondary" id="resetKeterampilanForm"><i class="fas fa-redo mr-1"></i> Reset Form</button>
                                    </div>
                                </form>
                            </div>

                            <div class="card card-info card-outline mt-4">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-list-alt mr-1"></i> Daftar Nilai Keterampilan</h3>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Jenis Keterampilan</th>
                                                    <th>Nama Penilaian</th>
                                                    <th>Nilai</th>
                                                    <th>Tanggal</th>
                                                    <th>Deskripsi</th>
                                                    <th style="width: 120px;">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($nilaiKeterampilan as $nk)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ ucwords(str_replace('_', ' ', $nk->jenis_keterampilan)) }}</td>
                                                        <td>{{ $nk->nama_penilaian ?? '-' }}</td>
                                                        <td>{{ $nk->nilai }}</td>
                                                        <td>{{ $nk->tanggal_nilai->format('d M Y') }}</td>
                                                        <td>{{ $nk->deskripsi ?? '-' }}</td>
                                                        <td>
                                                            <button type="button" class="btn btn-warning btn-sm edit-keterampilan"
                                                                data-id="{{ $nk->id }}"
                                                                data-jenis_keterampilan="{{ $nk->jenis_keterampilan }}"
                                                                data-nama_penilaian="{{ $nk->nama_penilaian }}"
                                                                data-nilai="{{ $nk->nilai }}"
                                                                data-tanggal_nilai_keterampilan="{{ $nk->tanggal_nilai->format('Y-m-d') }}"
                                                                data-deskripsi_keterampilan="{{ $nk->deskripsi }}">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <form action="{{ route('guru.grades.destroyKeterampilan', $nk->id) }}" method="POST" class="d-inline delete-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus nilai ini?');">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center py-3">Belum ada nilai keterampilan diinput.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- TAB NILAI SIKAP --}}
                        <div class="tab-pane fade" id="sikap" role="tabpanel" aria-labelledby="sikap-tab">
                            <div class="card card-primary card-outline mt-3">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-plus-circle mr-1"></i> Input/Edit Nilai Sikap</h3>
                                </div>
                                <form id="formSikap" action="{{ route('guru.grades.storeSikap', ['assignment' => $assignment->id, 'siswa' => $siswa->id]) }}" method="POST">
                                    @csrf
                                    <div class="card-body">
                                        @php
                                            // KOREKSI: Ambil objek nilai sikap spiritual dan sosial dari collection
                                            // Pastikan $nilaiSikap adalah Collection, bukan single model
                                            $sikapSpiritualObj = $nilaiSikap->where('jenis_sikap', 'spiritual')->first();
                                            $sikapSosialObj = $nilaiSikap->where('jenis_sikap', 'sosial')->first();
                                        @endphp
                                        <div class="form-group">
                                            <label for="deskripsi_sikap_spiritual">Sikap Spiritual <span class="text-danger">*</span></label>
                                            <textarea name="deskripsi_sikap_spiritual" id="deskripsi_sikap_spiritual" class="form-control @error('deskripsi_sikap_spiritual') is-invalid @enderror" rows="3" placeholder="Deskripsi sikap spiritual siswa (contoh: Siswa menunjukkan sikap religius yang baik dalam beribadah dan berdoa.)">{{ old('deskripsi_sikap_spiritual', $sikapSpiritualObj ? $sikapSpiritualObj->deskripsi : '') }}</textarea>
                                            @error('deskripsi_sikap_spiritual') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="deskripsi_sikap_sosial">Sikap Sosial <span class="text-danger">*</span></label>
                                            <textarea name="deskripsi_sikap_sosial" id="deskripsi_sikap_sosial" class="form-control @error('deskripsi_sikap_sosial') is-invalid @enderror" rows="3" placeholder="Deskripsi sikap sosial siswa (contoh: Siswa menunjukkan sikap jujur, disiplin, dan tanggung jawab yang sangat baik.)">{{ old('deskripsi_sikap_sosial', $sikapSosialObj ? $sikapSosialObj->deskripsi : '') }}</textarea>
                                            @error('deskripsi_sikap_sosial') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="card-footer text-right">
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Nilai Sikap</button>
                                    </div>
                                </form>
                            </div>

                            <div class="card card-info card-outline mt-4">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-list-alt mr-1"></i> Deskripsi Sikap Tersimpan</h3>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th>Jenis Sikap</th>
                                                    <th>Deskripsi</th>
                                                    <th style="width: 80px;">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{-- KOREKSI: Gunakan objek yang sudah diambil di atas --}}
                                                @if($sikapSpiritualObj)
                                                    <tr>
                                                        <td>Spiritual</td>
                                                        <td>{{ $sikapSpiritualObj->deskripsi }}</td>
                                                        <td>
                                                            <form action="{{ route('guru.grades.destroySikap', $sikapSpiritualObj->id) }}" method="POST" class="d-inline delete-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus deskripsi sikap spiritual ini?');">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endif
                                                @if($sikapSosialObj)
                                                    <tr>
                                                        <td>Sosial</td>
                                                        <td>{{ $sikapSosialObj->deskripsi }}</td>
                                                        <td>
                                                            <form action="{{ route('guru.grades.destroySikap', $sikapSosialObj->id) }}" method="POST" class="d-inline delete-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus deskripsi sikap sosial ini?');">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endif
                                                @if(!$sikapSpiritualObj && !$sikapSosialObj)
                                                    <tr>
                                                        <td colspan="3" class="text-center py-3">Belum ada deskripsi sikap diinput.</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
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

            // Datepicker initialization
            $('#akademik_datepicker input').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                language: 'id',
                orientation: 'bottom auto'
            });

            $('#keterampilan_datepicker input').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                language: 'id',
                orientation: 'bottom auto'
            });

            // Handle Edit for Akademik
            $('.edit-akademik').on('click', function() {
                const id = $(this).data('id');
                const jenis_nilai = $(this).data('jenis_nilai');
                const nama_nilai = $(this).data('nama_nilai');
                const nilai = $(this).data('nilai');
                const tanggal_nilai = $(this).data('tanggal_nilai');
                const keterangan = $(this).data('keterangan');

                $('#akademik_nilai_id').val(id);
                $('#jenis_nilai').val(jenis_nilai).trigger('change'); // Trigger change for select
                $('#nama_nilai').val(nama_nilai);
                $('#nilai').val(nilai);
                $('#tanggal_nilai').val(tanggal_nilai);
                $('#keterangan').val(keterangan);

                // Scroll to top of the form
                $('html, body').animate({
                    scrollTop: $('#formAkademik').offset().top - 100
                }, 500);
            });

            // Reset Akademik Form
            $('#resetAkademikForm').on('click', function() {
                $('#formAkademik')[0].reset();
                $('#akademik_nilai_id').val('');
                $('#jenis_nilai').val('').trigger('change'); // Reset select2
                // Clear validation feedback if any
                $('#formAkademik .is-invalid').removeClass('is-invalid');
                $('#formAkademik .invalid-feedback').remove();
            });

            // Handle Edit for Keterampilan
            $('.edit-keterampilan').on('click', function() {
                const id = $(this).data('id');
                const jenis_keterampilan = $(this).data('jenis_keterampilan');
                const nama_penilaian = $(this).data('nama_penilaian');
                const nilai = $(this).data('nilai');
                const tanggal_nilai_keterampilan = $(this).data('tanggal_nilai_keterampilan');
                const deskripsi_keterampilan = $(this).data('deskripsi_keterampilan');

                $('#keterampilan_nilai_id').val(id);
                $('#jenis_keterampilan').val(jenis_keterampilan).trigger('change');
                $('#nama_penilaian').val(nama_penilaian);
                $('#nilai_keterampilan').val(nilai);
                $('#tanggal_nilai_keterampilan').val(tanggal_nilai_keterampilan);
                $('#deskripsi_keterampilan').val(deskripsi_keterampilan);

                // Scroll to top of the form
                $('html, body').animate({
                    scrollTop: $('#formKeterampilan').offset().top - 100
                }, 500);
            });

            // Reset Keterampilan Form
            $('#resetKeterampilanForm').on('click', function() {
                $('#formKeterampilan')[0].reset();
                $('#keterampilan_nilai_id').val('');
                $('#jenis_keterampilan').val('').trigger('change');
                // Clear validation feedback if any
                $('#formKeterampilan .is-invalid').removeClass('is-invalid');
                $('#formKeterampilan .invalid-feedback').remove();
            });

            // Handle form submission for Sikap (no reset needed, it's updateOrCreate)
            // The old() helper in blade handles repopulating the textarea
        });
    </script>
@endpush
