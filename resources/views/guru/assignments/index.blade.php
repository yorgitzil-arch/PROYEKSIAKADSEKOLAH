@extends('layouts.app_guru')

@section('title', 'Daftar Penugasan Mengajar')
@section('page_title', 'Daftar Penugasan Mengajar')

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
        /* Styling untuk Search Bar dan Filter */
        .form-filter-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap; /* Agar responsif */
            margin-left: auto; /* Dorong ke kanan */
        }
        .form-filter-group .input-group {
            width: auto; /* Sesuaikan lebar */
            min-width: 150px; /* Lebar minimum untuk dropdown */
        }

        .input-group.input-group-sm {
            width: 250px !important; /* Lebar search bar yang lebih modern */
            max-width: 100%; /* Pastikan responsif */
        }

        .form-control.float-right,
        .form-control.filter-select {
            border-radius: 8px; /* Sudut membulat penuh untuk filter dropdown */
            border: 1px solid #ced4da;
            padding: 0.75rem 1rem;
            height: auto; /* Otomatis menyesuaikan padding */
            font-size: 1rem;
            width: auto; /* Allow content to dictate width for filters */
        }
        .form-control.float-right { /* Khusus search input */
            border-radius: 8px 0 0 8px;
        }

        .input-group-append .btn-default {
            border-radius: 0 8px 8px 0; /* Sudut membulat hanya di kanan */
            background-color: #007bff; /* Warna biru primary */
            border-color: #007bff;
            color: #ffffff;
            padding: 0.75rem 1rem;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .input-group-append .btn-default:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-outline-secondary {
            border-radius: 8px; /* Sudut membulat penuh untuk tombol reset */
            border-color: #6c757d;
            color: #6c757d;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            color: #fff;
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
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            color: #fff;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(108, 117, 125, 0.2);
        }
        .badge {
            font-size: 85%;
            padding: 0.4em 0.6em;
        }
        /* Responsive adjustments for card tools */
        @media (max-width: 767.98px) {
            .card-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .form-filter-group {
                margin-top: 15px;
                width: 100%;
                justify-content: flex-start;
            }
            .form-filter-group .input-group,
            .input-group.input-group-sm {
                width: 100% !important;
            }
            .form-control.float-right {
                border-radius: 8px 0 0 8px; /* Tetap membulat */
            }
            .input-group-append .btn-default {
                border-radius: 0 8px 8px 0; /* Tetap membulat */
            }
            .btn-outline-secondary {
                border-radius: 8px; /* Tetap membulat */
                width: 100%;
                margin-top: 5px; /* Add margin for spacing */
            }
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chalkboard-teacher mr-1"></i> Penugasan Mengajar Anda
                    </h3>
                    {{-- Filter Form --}}
                    <form action="{{ route('guru.assignments.index') }}" method="GET" class="form-filter-group">
                        {{-- Filter Tahun Ajaran --}}
                        <div class="input-group input-group-sm">
                            <select name="tahun_ajaran_id" class="form-control filter-select">
                                <option value="">Semua Tahun Ajaran</option>
                                @foreach($tahunAjarans as $ta)
                                    <option value="{{ $ta->id }}" {{ request()->query('tahun_ajaran_id') == $ta->id ? 'selected' : '' }}>
                                        {{ $ta->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filter Semester --}}
                        <div class="input-group input-group-sm">
                            <select name="semester_id" class="form-control filter-select">
                                <option value="">Semua Semester</option>
                                @foreach($semesters as $s)
                                    <option value="{{ $s->id }}" {{ request()->query('semester_id') == $s->id ? 'selected' : '' }}>
                                        {{ $s->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Search Bar --}}
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" class="form-control float-right" placeholder="Cari..." value="{{ $search }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default" data-toggle="tooltip" title="Cari / Filter">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        @if($search || request()->query('tahun_ajaran_id') || request()->query('semester_id'))
                            <a href="{{ route('guru.assignments.index') }}" class="btn btn-outline-secondary" data-toggle="tooltip" title="Reset Filter">Reset</a>
                        @endif
                    </form>
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
                    @if (session('info'))
                        <div class="alert alert-info alert-dismissible fade show m-3" role="alert">
                            <i class="fas fa-info-circle mr-2"></i> {{ session('info') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if($assignments->isEmpty())
                        <div class="alert alert-info text-center py-3">
                            Anda belum memiliki penugasan mengajar yang aktif.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Kelompok Mapel</th>
                                        <th>Kelas</th>
                                        <th>Tahun Ajaran</th>
                                        <th>Semester</th>
                                        <th>Status Konfirmasi</th>
                                        <th style="width: 250px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assignments as $assignment)
                                        <tr>
                                            <td>{{ $loop->iteration + ($assignments->currentPage() - 1) * $assignments->perPage() }}</td>
                                            <td>{{ $assignment->mataPelajaran->nama_mapel ?? 'N/A' }}</td>
                                            <td>{{ $assignment->mataPelajaran->kelompok ?? '-' }}</td>
                                            <td>
                                                {{ $assignment->kelas->nama_kelas ?? 'N/A' }}
                                                @if($assignment->kelas && $assignment->kelas->jurusan)
                                                    ({{ $assignment->kelas->jurusan->nama_jurusan }})
                                                @endif
                                            </td>
                                            <td>{{ $assignment->tahunAjaran->nama ?? 'N/A' }}</td>
                                            <td>{{ $assignment->semester->nama ?? 'N/A' }}</td>
                                            <td>
                                                @if($assignment->status_konfirmasi == 'Dikonfirmasi')
                                                    <span class="badge badge-success">Dikonfirmasi</span>
                                                @elseif($assignment->status_konfirmasi == 'Pending' || $assignment->status_konfirmasi == 'Menunggu Konfirmasi')
                                                    <span class="badge badge-warning">Menunggu Konfirmasi</span>
                                                @else
                                                    <span class="badge badge-danger">Ditolak</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($assignment->status_konfirmasi == 'Pending' || $assignment->status_konfirmasi == 'Menunggu Konfirmasi')
                                                    <form action="{{ route('guru.assignments.confirm', $assignment->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" name="status" value="Dikonfirmasi" class="btn btn-success btn-sm">Konfirmasi</button>
                                                        <button type="submit" name="status" value="Ditolak" class="btn btn-danger btn-sm">Tolak</button>
                                                    </form>
                                                @elseif($assignment->status_konfirmasi == 'Dikonfirmasi')
                                                    {{-- KOREKSI SANGAT PENTING: Ubah rute ke grades.index dengan parameter assignment_id --}}
                                                    <a href="{{ route('guru.grades.index', ['assignment_id' => $assignment->id]) }}" class="btn btn-info btn-sm">Input Nilai</a>
                                                    <a href="{{ route('guru.assignments.lesson_schedules.index', $assignment->id) }}" class="btn btn-info btn-sm">Kelola Presensi</a>
                                                    <a href="{{ route('guru.assignments.lesson_schedules.attendance_summary', $assignment->id) }}" class="btn btn-secondary btn-sm">Riwayat Presensi</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $assignments->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        // KOREKSI: Gunakan jQuery.noConflict(true) jika Anda menggunakan $j di tempat lain
        // atau pastikan jQuery dimuat sebelum script ini jika Anda menggunakan $
        // Untuk saat ini, saya asumsikan $ sudah tersedia dari AdminLTE/Bootstrap.
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
            @if (session('info'))
                toastr.info("{{ session('info') }}");
            @endif
        });
    </script>
@endpush
