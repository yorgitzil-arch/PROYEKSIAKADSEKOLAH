@extends('layouts.app_guru')

@section('title', 'Daftar Siswa Wali Kelas')
@section('page_title', 'Daftar Siswa Kelas ' . ($kelasWali->nama_kelas ?? ''))

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
        .profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ced4da;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-users mr-1"></i> Siswa Kelas {{ $kelasWali->nama_kelas ?? '-' }}
                        @if($kelasWali && $kelasWali->jurusan)
                            ({{ $kelasWali->jurusan->nama_jurusan }})
                        @endif
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-primary mr-2">T.A: {{ $activeTahunAjaran->nama ?? '-' }}</span>
                        <span class="badge badge-secondary">Semester: {{ $activeSemester->nama?? '-' }}</span>
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

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th style="width: 80px;">Foto</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th style="width: 150px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($siswas as $siswa)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if($siswa->foto_profile_path)
                                                <img src="{{ asset('storage/' . $siswa->foto_profile_path) }}" alt="Foto {{ $siswa->name }}" class="profile-img">
                                            @else
                                                <img src="https://placehold.co/40x40/cccccc/000000?text=No+Photo" alt="No Photo" class="profile-img">
                                            @endif
                                        </td>
                                        <td>{{ $siswa->nis }}</td>
                                        <td>{{ $siswa->name }}</td>
                                        <td>{{ $siswa->email }}</td>
                                        <td>
                                            <span class="badge badge-{{ $siswa->status == 'confirmed' ? 'success' : 'warning' }}">
                                                {{ ucfirst($siswa->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('guru.wali-kelas.raports.show', $siswa->id) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-file-alt mr-1"></i> Lihat Rapor
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-3">Tidak ada siswa di kelas ini.</td>
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
