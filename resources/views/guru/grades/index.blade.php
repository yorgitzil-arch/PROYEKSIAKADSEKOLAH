@extends('layouts.app_guru')

@section('title', 'Manajemen Nilai')
@section('page_title', 'Daftar Penugasan Mata Pelajaran')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
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

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            color: #fff;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2);
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
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chalkboard-teacher mr-1"></i> Penugasan Anda
                    </h3>
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

                    <p class="mb-4">
                        Berikut adalah daftar mata pelajaran yang Anda ampuh untuk Tahun Ajaran
                        **{{ $activeTahunAjaran->tahun_ajaran ?? '-' }}** dan Semester
                        **{{ $activeSemester->nama_semester ?? '-' }}**.
                        Pilih penugasan untuk mulai mengelola nilai siswa.
                    </p>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Kelas</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Semester</th>
                                    <th style="width: 150px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($assignments as $assignment)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $assignment->mataPelajaran->nama_mapel ?? '-' }}</td>
                                        <td>
                                            <span class="badge badge-info">
                                                {{ $assignment->kelas->nama_kelas ?? '-' }}
                                                @if($assignment->kelas && $assignment->kelas->jurusan)
                                                    ({{ $assignment->kelas->jurusan->nama_jurusan }})
                                                @endif
                                            </span>
                                        </td>
                                        <td>{{ $assignment->tahunAjaran->tahun_ajaran ?? '-' }}</td>
                                        <td>{{ $assignment->semester->nama_semester ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('guru.grades.index', ['assignment_id' => $assignment->id]) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-clipboard-list mr-1"></i> Kelola Nilai
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="fas fa-box-open fa-2x text-muted mb-2"></i><br>
                                            Tidak ada penugasan mata pelajaran yang ditemukan untuk Anda di tahun ajaran dan semester aktif ini.
                                        </td>
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
