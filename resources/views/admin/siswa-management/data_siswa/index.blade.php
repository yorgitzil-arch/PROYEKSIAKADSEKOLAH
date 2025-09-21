@extends('layouts.app_admin')

@section('title', 'Manajemen Data Siswa')
@section('page_title', 'Data Siswa Terdaftar')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<style>
    /* Custom CSS for Minimalist and Elegant Design */
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

    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: box-shadow 0.3s ease;
    }
    
    .card-header {
        border-bottom: 1px solid var(--border-color);
        padding: 1.25rem 2rem;
        background-color: var(--light-bg);
        border-radius: 12px 12px 0 0;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .card-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--dark-text);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Mengubah card-tools agar menempel di sudut kanan */
    .card-tools {
        margin-left: auto; /* Menggeser ke kanan */
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .form-control,
    .filter-select {
        border-radius: 8px;
        border: 1px solid var(--border-color);
        padding: 0.5rem 1rem;
        height: auto;
        font-size: 0.9rem;
    }

    .btn {
        border-radius: 8px;
        padding: 0.65rem 1.25rem;
        font-weight: 600;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.9rem;
    }

    .btn-action-icon {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        border-radius: 50%;
        font-size: 0.8rem;
    }

    .table-responsive {
        border-radius: 0 0 12px 12px;
        overflow-x: auto;
    }

    .table {
        background-color: #fff;
        margin-bottom: 0;
    }

    .table thead th {
        background-color: var(--light-bg);
        color: var(--dark-text);
        border-bottom: 2px solid var(--border-color);
        padding: 0.75rem 1rem;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
    }

    .table tbody td,
    .table tbody th {
        padding: 0.6rem 1rem; /* Smaller padding for minimalist columns */
        vertical-align: middle;
        border-top: 1px solid var(--border-color);
        font-size: 0.9rem; /* Smaller font size for table content */
    }

    .img-size-32 {
        width: 28px;
        height: 28px;
    }

    .badge {
        font-size: 80%;
        padding: .4em .8em;
        border-radius: 8px;
        font-weight: 600;
    }

    .card-footer.clearfix {
        padding: 1.25rem 2rem;
        border-top: 1px solid var(--border-color);
        background-color: #fff;
        border-radius: 0 0 12px 12px;
    }

    .form-group-compact {
        margin-bottom: 0;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-database mr-1"></i> Data Siswa Terdaftar
                </h3>
                <div class="card-tools">
                    <a href="{{ route('admin.student-data.create') }}" class="btn btn-success" data-toggle="tooltip" title="Tambah Siswa Baru">
                        <i class="fas fa-plus-circle"></i> Tambah Siswa
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
                
                {{-- Filter Form --}}
                <form action="{{ route('admin.student-data.index') }}" method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-12 d-flex flex-wrap align-items-end" style="gap: 10px;">
                            <div class="form-group-compact flex-grow-1">
                                <label for="search" class="sr-only">Cari Siswa</label>
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Cari NIS, NISN, nama..." value="{{ $search }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group-compact">
                                <label for="status" class="sr-only">Status</label>
                                <select name="status" id="status" class="form-control filter-select">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ $statusFilter == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ $statusFilter == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                                </select>
                            </div>
                            <div class="form-group-compact">
                                <label for="kelas_id" class="sr-only">Kelas</label>
                                <select name="kelas_id" id="kelas_id" class="form-control filter-select">
                                    <option value="">Semua Kelas</option>
                                    @foreach($kelas as $k)
                                        <option value="{{ $k->id }}" {{ $kelasFilter == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group-compact">
                                <label for="jurusan_id" class="sr-only">Jurusan</label>
                                <select name="jurusan_id" id="jurusan_id" class="form-control filter-select">
                                    <option value="">Semua Jurusan</option>
                                    @foreach($jurusans as $j)
                                        <option value="{{ $j->id }}" {{ $jurusanFilter == $j->id ? 'selected' : '' }}>{{ $j->nama_jurusan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if($search || $statusFilter || $kelasFilter || $jurusanFilter)
                            <div class="form-group-compact">
                                <a href="{{ route('admin.student-data.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-redo"></i> Reset
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover table-head-fixed text-nowrap">
                        <thead>
                            <tr>
                                <th style="width: 30px;">#</th>
                                <th>NIS</th>
                                <th>NISN</th>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Kelas</th>
                                <th>Jurusan</th>
                                <th>Wali Kelas</th>
                                <th style="width: 150px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($siswas as $siswa)
                            <tr>
                                <td>{{ $loop->iteration + ($siswas->currentPage() - 1) * $siswas->perPage() }}</td>
                                <td>{{ $siswa->nis }}</td>
                                <td>{{ $siswa->nisn ?? '-' }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($siswa->foto_profile_path)
                                            <img src="{{ asset('storage/' . $siswa->foto_profile_path) }}" class="img-circle img-size-32 mr-2" alt="User Image" style="object-fit: cover;">
                                        @else
                                            <img src="{{ asset('adminlte/dist/img/default-avatar.png') }}" class="img-circle img-size-32 mr-2" alt="Default Avatar" style="object-fit: cover;">
                                        @endif
                                        <span>{{ $siswa->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $siswa->email ?? '-' }}</td>
                                <td>
                                    @if($siswa->status == 'pending')
                                        <span class="badge badge-warning"><i class="fas fa-hourglass-half mr-1"></i> Pending</span>
                                    @else
                                        <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i> Dikonfirmasi</span>
                                    @endif
                                </td>
                                <td><span class="badge badge-info">{{ $siswa->kelas->nama_kelas ?? '-' }}</span></td>
                                <td><span class="badge badge-secondary">{{ $siswa->jurusan->nama_jurusan ?? '-' }}</span></td>
                                <td>{{ $siswa->waliKelas->name ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.student-data.show', $siswa->id) }}" class="btn btn-info btn-sm btn-action-icon mr-1" data-toggle="tooltip" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.student-data.edit', $siswa->id) }}" class="btn btn-warning btn-sm btn-action-icon mr-1" data-toggle="tooltip" title="Edit Data">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.student-data.destroy', $siswa->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus siswa {{ $siswa->name }} ini beserta semua datanya?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm btn-action-icon" data-toggle="tooltip" title="Hapus Data">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4 text-muted">
                                    <i class="fas fa-box-open fa-2x mb-2"></i><br>
                                    Tidak ada data siswa terdaftar.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer clearfix">
                {{ $siswas->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
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

        @if ($errors->any())
        @foreach ($errors->all() as $error)
        toastr.error("{{ $error }}");
        @endforeach
        @endif
        
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endpush
