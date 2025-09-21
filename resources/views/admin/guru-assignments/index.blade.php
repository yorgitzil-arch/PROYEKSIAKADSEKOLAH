@extends('layouts.app_admin')

@section('title', 'Manajemen Penugasan Guru')
@section('page_title', 'Manajemen Penugasan Guru')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<style>
    /* CSS Variables for consistent theming */
    :root {
        --primary-color: #007bff;
        --secondary-color: #6c757d;
        --success-color: #28a745;
        --warning-color: #ffc107;
        --danger-color: #dc3545;
        --info-color: #17a2b8;
        --light-bg: #f8f9fa;
        --dark-text: #343a40;
        --light-text: #fff;
        --border-color: #e9ecef;
    }

    body {
        background-color: #f4f6f9;
        font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }

    /* Card Styling */
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    .card-header {
        border-bottom: 1px solid var(--border-color);
        padding: 1.5rem 2rem;
        background-color: var(--light-bg);
        border-radius: 12px 12px 0 0;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .card-title {
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--dark-text);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-tools {
        width: 100%;
        display: flex;
        justify-content: flex-start;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .input-group.input-group-sm {
        width: auto;
        min-width: 180px;
        max-width: 250px;
    }

    .form-control,
    .filter-select {
        border-radius: 8px;
        border: 1px solid var(--border-color);
        padding: 0.75rem 1rem;
        height: auto;
        font-size: 0.95rem;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-control:focus,
    .filter-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .input-group-append .btn {
        border-radius: 0 8px 8px 0 !important;
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: var(--light-text);
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .input-group-append .btn:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }

    .btn {
        border-radius: 8px;
        padding: 0.75rem 1.25rem;
        font-weight: 600;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-success {
        background: linear-gradient(45deg, var(--success-color), #218838);
        border: none;
        color: var(--light-text);
        box-shadow: 0 4px 8px rgba(40, 167, 69, 0.2);
    }

    .btn-success:hover {
        box-shadow: 0 6px 12px rgba(40, 167, 69, 0.3);
        background: linear-gradient(45deg, #218838, var(--success-color));
    }

    .btn-outline-secondary {
        border-color: var(--secondary-color);
        color: var(--secondary-color);
    }

    .btn-outline-secondary:hover {
        background-color: var(--secondary-color);
        color: var(--light-text);
    }

    /* Table Styling */
    .table-responsive {
        border-radius: 0 0 12px 12px;
        overflow-x: auto;
    }

    .table {
        background-color: #fff;
        margin-bottom: 0;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }

    .table thead th {
        background-color: var(--light-bg);
        color: var(--dark-text);
        border-bottom: 2px solid var(--border-color);
        padding: 1rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .table tbody td,
    .table tbody th {
        padding: 1rem;
        vertical-align: middle;
        border-top: 1px solid var(--border-color);
    }

    /* Button Action Icon */
    .btn-action-icon {
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .btn-action-icon:hover {
        transform: scale(1.1);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .btn-info {
        background-color: var(--info-color);
        border-color: var(--info-color);
        color: var(--light-text);
    }

    .btn-info:hover {
        background-color: #138496;
        border-color: #117a8b;
    }

    .btn-warning {
        background-color: var(--warning-color);
        border-color: var(--warning-color);
        color: var(--dark-text);
    }

    .btn-warning:hover {
        background-color: #e0a800;
        border-color: #d39e00;
    }

    .btn-danger {
        background-color: var(--danger-color);
        border-color: var(--danger-color);
        color: var(--light-text);
    }

    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }

    /* Badge Styling */
    .badge {
        font-size: 85%;
        padding: .5em .9em;
        border-radius: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
    }

    .badge-success {
        background-color: var(--success-color);
        color: var(--light-text);
    }

    .badge-warning {
        background-color: var(--warning-color);
        color: var(--dark-text);
    }

    .badge-info {
        background-color: var(--info-color);
        color: var(--light-text);
    }

    .badge-danger {
        background-color: var(--danger-color);
        color: var(--light-text);
    }

    .badge-secondary {
        background-color: var(--secondary-color);
        color: var(--light-text);
    }

    /* Card Footer */
    .card-footer.clearfix {
        padding: 1.5rem 2rem;
        border-top: 1px solid var(--border-color);
        background-color: #fff;
        border-radius: 0 0 12px 12px;
    }

    /* Responsive Adjustments */
    @media (max-width: 991.98px) {
        .card-header {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
        }
        .header-content {
            flex-direction: column;
            align-items: flex-start;
        }
        .card-tools {
            flex-direction: column;
            align-items: stretch;
            width: 100%;
        }
        .input-group.input-group-sm {
            max-width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="header-content">
                    <h3 class="card-title">
                        <i class="fas fa-chalkboard-teacher"></i> Manajemen Penugasan Guru
                    </h3>
                    <a href="{{ route('admin.guru-assignments.create') }}" class="btn btn-success" data-toggle="tooltip" title="Tambah Penugasan Mengajar Baru">
                        <i class="fas fa-plus-circle"></i> Tambah
                    </a>
                </div>
                <div class="card-tools">
                    <form action="{{ route('admin.guru-assignments.index') }}" method="GET" class="d-flex flex-wrap align-items-center" style="gap: 10px;">
                        <div class="input-group input-group-sm">
                            <select name="tahun_ajaran_id" class="form-control filter-select">
                                <option value="">Tahun Ajaran</option>
                                @foreach($tahunAjarans as $ta)
                                    <option value="{{ $ta->id }}" {{ request()->query('tahun_ajaran_id') == $ta->id ? 'selected' : '' }}>
                                        {{ $ta->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-group input-group-sm">
                            <select name="semester_id" class="form-control filter-select">
                                <option value="">Semester</option>
                                @foreach($semesters as $s)
                                    <option value="{{ $s->id }}" {{ request()->query('semester_id') == $s->id ? 'selected' : '' }}>
                                        {{ $s->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" class="form-control float-right" placeholder="Cari..." value="{{ $search }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default" data-toggle="tooltip" title="Cari / Filter">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        @if($search || request()->query('tahun_ajaran_id') || request()->query('semester_id'))
                        <a href="{{ route('admin.guru-assignments.index') }}" class="btn btn-outline-secondary" data-toggle="tooltip" title="Reset Filter">
                            <i class="fas fa-sync-alt"></i> Reset
                        </a>
                        @endif
                    </form>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-head-fixed text-nowrap">
                        <thead>
                            <tr>
                                <th style="width: 50px">#</th>
                                <th>Guru</th>
                                <th>Kelas</th>
                                <th>Jurusan</th>
                                <th>Mata Pelajaran</th>
                                <th>Kelompok Mapel</th>
                                <th>Tahun Ajaran</th>
                                <th>Semester</th>
                                <th>Tipe Mengajar</th>
                                <th>Status</th>
                                <th style="width: 150px" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($assignments as $assignment)
                            <tr>
                                <td>{{ $loop->iteration + ($assignments->currentPage() - 1) * $assignments->perPage() }}</td>
                                <td>{{ $assignment->guru->name ?? '-' }}</td>
                                <td>{{ $assignment->kelas->nama_kelas ?? '-' }}</td>
                                <td>{{ $assignment->kelas->jurusan->nama_jurusan ?? '-' }}</td>
                                <td>{{ $assignment->mataPelajaran->nama_mapel ?? '-' }}</td>
                                <td>{{ $assignment->mataPelajaran->kelompok ?? '-' }}</td>
                                <td>{{ $assignment->tahunAjaran->nama ?? '-' }}</td>
                                <td>{{ $assignment->semester->nama ?? '-' }}</td>
                                <td>{{ $assignment->tipe_mengajar ?? '-'}}</td>
                                <td>
                                    @if ($assignment->status_konfirmasi == 'Dikonfirmasi')
                                    <span class="badge badge-success">Dikonfirmasi</span>
                                    @elseif ($assignment->status_konfirmasi == 'Pending')
                                    <span class="badge badge-warning">Pending</span>
                                    @elseif ($assignment->status_konfirmasi == 'Ditolak')
                                    <span class="badge badge-danger">Ditolak</span>
                                    @else
                                    <span class="badge badge-secondary">{{ $assignment->status_konfirmasi }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <a href="{{ route('admin.guru-assignments.show', $assignment->id) }}" class="btn btn-info btn-sm btn-action-icon mr-1" data-toggle="tooltip" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.guru-assignments.edit', $assignment->id) }}" class="btn btn-warning btn-sm btn-action-icon mr-1" data-toggle="tooltip" title="Edit Penugasan">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.guru-assignments.destroy', $assignment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus penugasan ini? Tindakan ini tidak dapat dibatalkan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm btn-action-icon" data-toggle="tooltip" title="Hapus Penugasan">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center py-4 text-muted">
                                    <i class="fas fa-clipboard-list fa-3x mb-2"></i><br>
                                    Tidak ada penugasan guru yang ditemukan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer clearfix">
                {{ $assignments->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    $(function() {
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

        @if (session('warning'))
        toastr.warning("{{ session('warning') }}");
        @endif

        @if ($errors->any())
        @foreach ($errors->all() as $error)
        toastr.error("{{ $error }}");
        @endforeach
        @endif

        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endpush
