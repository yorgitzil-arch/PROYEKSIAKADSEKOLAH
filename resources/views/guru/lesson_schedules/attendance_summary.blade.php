@extends('layouts.app_guru')

@section('title', 'Ringkasan Presensi')
@section('page_title', 'Ringkasan Presensi: ' . ($assignment->mataPelajaran->nama_mapel ?? 'N/A') . ' - Kelas ' . ($assignment->kelas->nama_kelas ?? 'N/A'))

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
        .badge {
            font-size: 85%;
            padding: 0.4em 0.6em;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history mr-1"></i> Ringkasan Presensi untuk
                        {{ $assignment->mataPelajaran->nama_mapel ?? 'N/A' }} - Kelas {{ $assignment->kelas->nama_kelas ?? 'N/A' }}
                        @if($assignment->kelas && $assignment->kelas->jurusan)
                            ({{ $assignment->kelas->jurusan->nama_jurusan }})
                        @endif
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('guru.assignments.lesson_schedules.index', $assignment->id) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Jadwal
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

                    <p>Total Pertemuan Terencana: <span class="badge badge-primary">{{ $lessonSchedules->count() }}</span></p>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Nama Siswa</th>
                                    <th class="text-center">Hadir</th>
                                    <th class="text-center">Sakit</th>
                                    <th class="text-center">Izin</th>
                                    <th class="text-center">Alpha</th>
                                    <th class="text-center">Total Presensi Tercatat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rekapPresensi as $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data['siswa']->name }}</td>
                                        <td class="text-center"><span class="badge badge-success">{{ $data['hadir'] }}</span></td>
                                        <td class="text-center"><span class="badge badge-warning">{{ $data['sakit'] }}</span></td>
                                        <td class="text-center"><span class="badge badge-info">{{ $data['izin'] }}</span></td>
                                        <td class="text-center"><span class="badge badge-danger">{{ $data['alpha'] }}</span></td>
                                        <td class="text-center">{{ $data['hadir'] + $data['sakit'] + $data['izin'] + $data['alpha'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-3">Belum ada data presensi untuk penugasan ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
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
        });
    </script>
@endpush
