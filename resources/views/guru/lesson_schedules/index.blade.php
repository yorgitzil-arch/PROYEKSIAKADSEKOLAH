@extends('layouts.app_guru') {{-- Menggunakan layout app_guru --}}

@section('title', 'Jadwal Pelajaran & Presensi')
@section('page_title', 'Jadwal Pelajaran & Presensi')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
            flex-direction: column; /* Mengubah ke kolom agar info di bawah judul */
            align-items: flex-start; /* Rata kiri */
            flex-wrap: wrap;
        }
        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #343a40;
            margin-bottom: 5px; /* Sedikit margin bawah untuk info tambahan */
        }
        .card-header .info-line { /* Gaya untuk baris info tambahan */
            font-size: 1rem;
            color: #555;
            margin-bottom: 10px;
        }
        .card-tools {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 10px; /* Jarak dari info di atasnya */
            width: 100%; /* Agar tombol rata kiri di bawah */
            justify-content: flex-start;
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
        .input-group-text {
            border-radius: 0 8px 8px 0;
            background-color: #e9ecef;
            border-color: #ced4da;
            border-radius: 8px; /* Make it fully rounded for filter buttons */
        }
        .input-group-append .input-group-text {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }
        /* Responsive adjustments */
        @media (max-width: 767.98px) {
            .card-header {
                align-items: center; /* Kembali ke tengah untuk mobile */
            }
            .card-title {
                text-align: center;
                width: 100%;
            }
            .card-header .info-line {
                text-align: center;
                width: 100%;
            }
            .card-tools {
                justify-content: center; /* Tombol di tengah untuk mobile */
            }
            .btn {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Presensi Harian untuk {{ $assignment->mataPelajaran->nama_mapel ?? 'N/A' }} - Kelas {{ $assignment->kelas->nama_kelas ?? 'N/A' }}
                    </h3>
                    {{-- Informasi tambahan termasuk Kelompok Mata Pelajaran --}}
                    <div class="info-line">
                        <strong>Kelompok Mapel:</strong> {{ $assignment->mataPelajaran->kelompok ?? '-' }} &nbsp; | &nbsp;
                        <strong>Tahun Ajaran:</strong> {{ $assignment->tahunAjaran->nama ?? '-' }} &nbsp; | &nbsp;
                        <strong>Semester:</strong> {{ $assignment->semester->nama ?? '-' }} &nbsp; | &nbsp;
                        <strong>Tanggal:</strong> {{ \Carbon\Carbon::today()->translatedFormat('d F Y') }}
                    </div>
                    <div class="card-tools">
                        {{-- KOREKSI SANGAT PENTING: Mengubah nama rute --}}
                        <a href="{{ route('guru.assignments.lesson_schedules.fill_attendance', ['assignment' => $assignment->id, 'lesson_schedule' => 'today']) }}" class="btn btn-primary btn-sm">Isi / Edit Presensi Hari Ini</a>
                        {{-- KOREKSI SANGAT PENTING: Mengubah nama rute untuk Riwayat Presensi --}}
                        <a href="{{ route('guru.assignments.lesson_schedules.attendance_summary', $assignment->id) }}" class="btn btn-secondary btn-sm">Riwayat Presensi</a>
                    </div>
                </div>
                <div class="card-body">
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
                    @if(session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            {{ session('info') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="mb-3">
                        <form action="{{ route('guru.assignments.lesson_schedules.index', $assignment->id) }}" method="GET" class="form-inline">
                            <label for="filter_date" class="mr-2">Filter Tanggal:</label>
                            <input type="text" id="filter_date" name="date" class="form-control flatpickr-input" placeholder="Pilih Tanggal" value="{{ $selectedDate }}">
                            <button type="submit" class="btn btn-info btn-sm ml-2">Tampilkan</button>
                            @if($selectedDate)
                                <a href="{{ route('guru.assignments.lesson_schedules.index', $assignment->id) }}" class="btn btn-secondary btn-sm ml-1">Reset Filter</a>
                            @endif
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Tanggal</th>
                                    <th>Mulai</th>
                                    <th>Selesai</th>
                                    <th>Topik</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($lessonSchedules as $schedule)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ \Carbon\Carbon::parse($schedule->date)->translatedFormat('d F Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                                        <td>{{ $schedule->topic }}</td>
                                        <td>
                                            <a href="{{ route('guru.assignments.lesson_schedules.fill_attendance', ['assignment' => $assignment->id, 'lesson_schedule' => $schedule->id]) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i> Edit Presensi
                                            </a>
                                            <form action="{{ route('guru.assignments.lesson_schedules.destroy', ['assignment' => $assignment->id, 'lesson_schedule' => $schedule->id]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal pelajaran ini? Ini juga akan menghapus semua data presensi terkait.');">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada jadwal pelajaran ditemukan untuk tanggal ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $lessonSchedules->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
    <script>
        $(document).ready(function() {
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

            flatpickr("#filter_date", {
                dateFormat: "Y-m-d",
                locale: "id",
                allowInput: true
            });
        });
    </script>
@endpush
