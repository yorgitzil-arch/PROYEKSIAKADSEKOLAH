@extends('layouts.app_admin')

@section('title', 'Manajemen Semester')
@section('content')

    <div class="row">
        <div class="col-md-12">
            {{-- Menggunakan class card-outline dan card-primary untuk gaya yang lebih konsisten dengan AdminLTE --}}
            <div class="card card-outline card-primary shadow"> 
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list-alt mr-1"></i> Daftar Semester
                    </h3>
                    <div class="card-tools">
                        {{-- Menggunakan class btn-sm dan ikon untuk tombol --}}
                        <a href="{{ route('admin.semester.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus mr-1"></i> Tambah Semester
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Alert Messages --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-times-circle mr-2"></i>
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if (session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="fas fa-info-circle mr-2"></i>
                            {{ session('info') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered"> {{-- Menggunakan table-hover untuk interaktivitas --}}
                            <thead class="thead-light"> {{-- Menggunakan thead-light untuk header tabel --}}
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th>Nama Semester</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Status</th>
                                    <th style="width: 150px;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($semesters as $semester)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $semester->nama }}</td>
                                        <td>{{ $semester->tahunAjaran->nama ?? 'Tidak Diketahui' }}</td>
                                        <td>
                                            @if ($semester->is_active)
                                                <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i> Aktif</span>
                                            @else
                                                <span class="badge badge-secondary"><i class="fas fa-ban mr-1"></i> Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.semester.edit', $semester->id) }}" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.semester.destroy', $semester->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus semester ini? Ini akan menghapus semua data terkait!')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                            @if (!$semester->is_active)
                                                <form action="{{ route('admin.semester.toggle-active', $semester->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm" data-toggle="tooltip" title="Aktifkan" onclick="return confirm('Mengaktifkan semester ini akan menonaktifkan semester lain di tahun ajaran yang sama. Lanjutkan?')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <i class="fas fa-box-open fa-2x text-muted mb-2"></i>
                                            <p>Tidak ada data semester yang tersedia.</p>
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

@push('styles')
    <style>
        /* Gaya kustom minimalis untuk melengkapi AdminLTE */
        .card.shadow {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }
        .table thead th {
            font-weight: 600;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // Inisialisasi tooltip Bootstrap
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush