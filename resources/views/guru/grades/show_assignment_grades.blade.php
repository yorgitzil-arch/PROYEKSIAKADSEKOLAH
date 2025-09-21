@extends('layouts.app_guru')

@section('title', 'Manajemen Nilai')
@section('page_title', 'Daftar Siswa untuk Penugasan')

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

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            color: #ffffff;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
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
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-users mr-1"></i> Siswa Kelas {{ $assignment->kelas->nama_kelas ?? '-' }}
                        ({{ $assignment->mataPelajaran->nama_mapel ?? '-' }})
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('guru.grades.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Penugasan
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

                    <p class="mb-4">
                        Pilih siswa dari daftar di bawah untuk mulai menginput atau mengedit nilai
                        **{{ $assignment->mataPelajaran->nama_mapel ?? '-' }}**
                        untuk Tahun Ajaran **{{ $activeTahunAjaran->nama ?? '-' }}** dan Semester
                        **{{ $activeSemester->nama ?? '-' }}**.
                    </p>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>NIS</th>
                                    <th>Nama Lengkap</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($siswas as $siswa)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $siswa->nis }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($siswa->foto_profile_path)
                                                    <img src="{{ asset('storage/' . $siswa->foto_profile_path) }}" class="img-circle img-size-32 mr-2" alt="Foto Profil Siswa">
                                                @else
                                                    <img src="{{ asset('adminlte/dist/img/default-avatar.png') }}" class="img-circle img-size-32 mr-2" alt="Default Avatar">
                                                @endif
                                                <span>{{ $siswa->name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('guru.grades.create', ['assignment' => $assignment->id, 'siswa' => $siswa->id]) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-plus-circle mr-1"></i> Input/Edit Nilai
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <i class="fas fa-box-open fa-2x text-muted mb-2"></i><br>
                                            Tidak ada siswa di kelas ini.
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
