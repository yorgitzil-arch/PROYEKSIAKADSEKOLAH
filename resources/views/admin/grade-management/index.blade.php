@extends('layouts.app_admin')

@section('title', 'Manajemen Nilai Siswa')
@section('page_title', 'Manajemen Nilai Siswa')

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
            flex-direction: column; /* Mengubah menjadi kolom untuk responsivitas filter */
            align-items: flex-start; /* Sejajarkan ke kiri */
            gap: 15px; /* Jarak antar elemen di header */
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

        .card-tools {
            width: 100%; /* Agar form filter mengambil lebar penuh */
            display: flex;
            flex-direction: column; /* Filter dan search dalam kolom */
            gap: 10px; /* Jarak antar grup filter */
        }

        /* Styling untuk Search dan Filter */
        .input-group {
            width: 100%;
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

        .input-group-append .btn-default,
        .input-group-append .btn-info,
        .input-group-append .btn-secondary {
            border-radius: 0 8px 8px 0; /* Sudut membulat di kanan */
            padding: 0.75rem 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .input-group-append .btn-default {
            background-color: #007bff;
            border-color: #007bff;
            color: #ffffff;
        }
        .input-group-append .btn-default:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .input-group-append .btn-info {
            background: linear-gradient(45deg, #17a2b8, #138496); /* Gradient untuk Apply Filter */
            border: none;
            color: #ffffff;
            box-shadow: 0 4px 8px rgba(23, 162, 184, 0.2);
        }
        .input-group-append .btn-info:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 12px rgba(23, 162, 184, 0.3);
            background: linear-gradient(45deg, #138496, #17a2b8);
        }

        .input-group-append .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            color: #ffffff;
        }
        .input-group-append .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
            transform: translateY(-1px);
        }

        /* Grouping filter dropdowns */
        .filter-dropdowns {
            display: flex;
            flex-wrap: wrap; /* Agar responsif */
            gap: 10px; /* Jarak antar dropdown */
            width: 100%;
        }
        .filter-dropdowns select {
            flex-grow: 1; /* Agar dropdown mengisi ruang */
            min-width: 150px; /* Lebar minimum agar tidak terlalu kecil */
            border-radius: 8px; /* Sudut membulat untuk dropdown filter */
        }
        .filter-dropdowns .input-group-append {
            display: flex; /* Untuk menata Apply dan Reset */
            gap: 5px;
        }
        .filter-dropdowns .input-group-append .btn {
            border-radius: 8px; /* Membulatkan tombol Apply dan Reset */
        }

        /* Styling untuk Tabel */
        .table {
            width: 100%;
            margin-bottom: 0;
            color: #343a40;
        }

        .table thead th {
            background-color: #f8f9fa; /* Latar belakang header tabel */
            color: #495057; /* Teks header tabel */
            border-bottom: 2px solid #dee2e6;
            padding: 1rem;
            font-weight: 600;
            text-align: left;
        }

        .table tbody tr {
            transition: background-color 0.2s ease;
        }

        .table tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, .03); /* Sedikit abu-abu untuk baris ganjil */
        }

        .table tbody tr:hover {
            background-color: rgba(0, 0, 0, .07); /* Lebih gelap saat hover */
        }

        .table td, .table th {
            padding: 1rem;
            vertical-align: middle;
            border-top: 1px solid #dee2e6;
        }

        /* Badge Styling for Nilai */
        .badge {
            font-size: 85%;
            padding: .4em .6em;
            border-radius: .3rem;
            font-weight: 600;
            min-width: 45px; /* Lebar minimum agar konsisten */
            text-align: center;
        }
        .badge-success { background-color: #28a745; color: #fff; } /* Nilai >= 75 */
        .badge-danger { background-color: #dc3545; color: #fff; } /* Nilai < 75 */

        /* Card footer untuk pagination */
        .card-footer.clearfix {
            padding: 1.25rem 1.5rem;
            border-top: 1px solid rgba(0, 0, 0, .125);
            background-color: #ffffff;
            border-radius: 0 0 15px 15px; /* Sudut membulat di bawah */
        }

        /* Responsif */
        @media (max-width: 767.98px) {
            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            .card-tools {
                flex-direction: column;
                gap: 10px;
            }
            .filter-dropdowns {
                flex-direction: column;
                gap: 10px;
            }
            .filter-dropdowns select {
                width: 100%;
                min-width: unset;
            }
            .filter-dropdowns .input-group-append {
                width: 100%;
                justify-content: stretch;
            }
            .filter-dropdowns .input-group-append .btn {
                flex-grow: 1; /* Tombol Apply/Reset mengisi ruang */
            }
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-info"> {{-- Menggunakan card-outline card-info untuk tampilan yang lebih baik --}}
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-clipboard-check mr-1"></i> Daftar Nilai Siswa {{-- Menambahkan ikon --}}
                    </h3>
                    <div class="card-tools">
                        <form action="{{ route('admin.grade-management.index') }}" method="GET">
                            {{-- Search input --}}
                            <div class="input-group mb-2"> {{-- Ditambah mb-2 untuk jarak dengan filter dropdowns --}}
                                <input type="text" name="search" class="form-control" placeholder="Cari (Siswa, Guru, Mapel, Kelas)" value="{{ $search }}">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default" data-toggle="tooltip" title="Cari">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Filter dropdowns --}}
                            <div class="filter-dropdowns">
                                <select name="guru_id" class="form-control" data-toggle="tooltip" title="Filter berdasarkan Guru">
                                    <option value="">-- Filter Guru --</option>
                                    @foreach($gurus as $guru)
                                        <option value="{{ $guru->id }}" {{ $filterGuru == $guru->id ? 'selected' : '' }}>{{ $guru->name }}</option>
                                    @endforeach
                                </select>
                                <select name="mata_pelajaran_id" class="form-control" data-toggle="tooltip" title="Filter berdasarkan Mata Pelajaran">
                                    <option value="">-- Filter Mapel --</option>
                                    @foreach($mataPelajarans as $mapel)
                                        <option value="{{ $mapel->id }}" {{ $filterMapel == $mapel->id ? 'selected' : '' }}>{{ $mapel->nama_mapel }}</option>
                                    @endforeach
                                </select>
                                <select name="kelas_id" class="form-control" data-toggle="tooltip" title="Filter berdasarkan Kelas">
                                    <option value="">-- Filter Kelas --</option>
                                    @foreach($kelas as $k)
                                        <option value="{{ $k->id }}" {{ $filterKelas == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-info" data-toggle="tooltip" title="Terapkan Filter">Apply Filter</button>
                                    @if($search || $filterGuru || $filterMapel || $filterKelas)
                                        <a href="{{ route('admin.grade-management.index') }}" class="btn btn-secondary" data-toggle="tooltip" title="Reset Semua Filter">Reset</a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body table-responsive p-0"> {{-- Menambahkan table-responsive dan p-0 --}}
                    {{-- Notifikasi akan ditampilkan via Toastr --}}

                    <table class="table table-hover table-head-fixed text-nowrap"> {{-- Menggunakan table-hover, table-head-fixed, text-nowrap --}}
                        <thead>
                        <tr>
                            <th style="width: 50px"><i class="fas fa-hashtag"></i></th> {{-- Ikon untuk nomor urut --}}
                            <th><i class="fas fa-user-graduate mr-1"></i> Siswa (NIS)</th> {{-- Ikon --}}
                            <th><i class="fas fa-user-tie mr-1"></i> Guru Pengampu</th> {{-- Ikon --}}
                            <th><i class="fas fa-book mr-1"></i> Mata Pelajaran</th> {{-- Ikon --}}
                            <th><i class="fas fa-school mr-1"></i> Kelas</th> {{-- Ikon --}}
                            <th><i class="fas fa-star mr-1"></i> Nilai</th> {{-- Ikon --}}
                            <th><i class="fas fa-info-circle mr-1"></i> Keterangan</th> {{-- Ikon --}}
                            <th><i class="fas fa-calendar-alt mr-1"></i> Tanggal Input</th> {{-- Ikon --}}
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($grades as $grade)
                            <tr>
                                <td>{{ $loop->iteration + ($grades->currentPage() - 1) * $grades->perPage() }}</td>
                                <td>{{ $grade->siswa->name ?? '-' }} ({{ $grade->siswa->nis ?? '-' }})</td>
                                <td>{{ $grade->assignment->guru->name ?? '-' }}</td>
                                <td>{{ $grade->assignment->mataPelajaran->nama_mapel ?? '-' }}</td>
                                <td>{{ $grade->assignment->kelas->nama_kelas ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ ($grade->nilai ?? 0) >= 75 ? 'badge-success' : 'badge-danger' }}"> {{-- Menggunakan badge-success/badge-danger --}}
                                        {{ $grade->nilai ?? '-' }}
                                    </span>
                                </td>
                                <td>{{ $grade->keterangan ?? '-' }}</td>
                                <td>{{ $grade->created_at->format('d M Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-database fa-2x text-muted mb-2"></i><br> {{-- Ikon untuk empty state --}}
                                    Tidak ada data nilai yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $grades->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
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

            @if (session('success'))
            toastr.success("{{ session('success') }}");
            @endif

            @if (session('error'))
            toastr.error("{{ session('error') }}");
            @endif

            @if ($errors->any())
            @foreach ($errors->all() as $error)
            toastr.error("{{ $error }}");
            @endforeach
            @endif

            // Inisialisasi Tooltip
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush
