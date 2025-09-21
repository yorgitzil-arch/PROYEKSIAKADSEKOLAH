@extends('layouts.app_admin')

@section('title', 'Detail Penugasan Guru')
@section('page_title', 'Detail Penugasan Guru')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<style>
    /*
     * Custom Styles for Modern & Professional UI
     *
     * ------------------------------------------------
     * General Styling & Card Layout
     * ------------------------------------------------
     */
    body {
        background-color: #f4f6f9; /* Light gray background for a clean look */
    }

    .card {
        border-radius: 12px; /* A subtle, modern curve */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); /* A softer, more elegant shadow */
        background-color: #ffffff;
        margin-bottom: 2rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px); /* Gentle lift effect on hover */
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    .card-header {
        border-bottom: 1px solid #e9ecef; /* A cleaner border line */
        padding: 1.5rem 2rem; /* Generous padding */
        background-color: #ffffff; /* Consistent background */
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }

    .card-title {
        font-size: 1.6rem; /* Slightly larger title */
        font-weight: 700; /* Bolder font weight */
        color: #212529; /* Darker text for better contrast */
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px; /* Space between icon and text */
    }

    .card-body {
        padding: 2rem;
    }

    /*
     * ------------------------------------------------
     * Form & Data Display
     * ------------------------------------------------
     */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 8px; /* Space between label icon and text */
    }

    .form-group p {
        margin: 0;
        padding: 1rem 1.25rem;
        background-color: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        color: #343a40;
        word-wrap: break-word;
        font-size: 1rem;
        line-height: 1.5;
        font-weight: 500;
    }

    .alert {
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /*
     * ------------------------------------------------
     * Buttons & Badges
     * ------------------------------------------------
     */
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

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        color: #ffffff;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
    }

    .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #212529;
    }

    .btn-warning:hover {
        background-color: #e0a800;
        border-color: #d39e00;
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
        color: #ffffff;
    }

    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }
    
    /* Mengubah .btn-action-group agar tombol tidak full-width */
    .btn-action-group {
        margin-top: 2rem;
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        justify-content: space-between; /* Menempatkan tombol di kedua sisi */
        align-items: center;
    }
    
    /* Container untuk tombol Edit dan Hapus agar bisa diletakkan di kanan */
    .btn-right-group {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .badge {
        font-size: 85%;
        padding: .5em .9em;
        border-radius: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
    }
    
    .badge-success { background-color: #28a745; color: #fff; }
    .badge-warning { background-color: #ffc107; color: #212529; }
    .badge-danger { background-color: #dc3545; color: #fff; }
    .badge-secondary { background-color: #6c757d; color: #fff; }

    /*
     * ------------------------------------------------
     * Responsive Adjustments
     * ------------------------------------------------
     */
    @media (max-width: 991.98px) {
        .col-lg-8.offset-lg-2 {
            width: 100%;
            margin-left: 0;
            padding: 0 15px;
        }
    }

    @media (max-width: 767.98px) {
        .card-header {
            flex-direction: column;
            align-items: flex-start;
            padding: 1.25rem 1.5rem;
        }
        .card-title {
            font-size: 1.4rem;
        }
        .btn-action-group {
            /* Tetap menggunakan justify-content: space-between */
            flex-direction: row; /* Pastikan tombol tetap di satu baris jika cukup */
            justify-content: space-between;
        }
        .btn-action-group .btn {
            width: auto; /* Membatasi lebar agar tidak full-width */
            justify-content: center;
        }
        .form-group p {
            font-size: 0.95rem;
            padding: 0.75rem 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle"></i> Detail Penugasan Mengajar
                </h3>
            </div>
            <div class="card-body">
                @if (!isset($assignment) || is_null($assignment->id))
                <div class="alert alert-danger text-center">
                    <i class="fas fa-exclamation-triangle"></i> Penugasan tidak ditemukan atau tidak valid. Silakan kembali ke daftar penugasan.
                </div>
                @else
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="fas fa-user-tie"></i> Guru</label>
                            <p>{{ $assignment->guru->name ?? 'N/A' }}</p>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-school"></i> Kelas</label>
                            <p>{{ $assignment->kelas->nama_kelas ?? 'N/A' }}</p>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-graduation-cap"></i> Jurusan</label>
                            <p>{{ $assignment->kelas->jurusan->nama_jurusan ?? 'N/A' }}</p>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-lightbulb"></i> Tipe Mengajar</label>
                            <p>{{ $assignment->tipe_mengajar ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="fas fa-book-open"></i> Mata Pelajaran</label>
                            <p>{{ $assignment->mataPelajaran->nama_mapel ?? 'N/A' }}</p>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-calendar-alt"></i> Tahun Ajaran</label>
                            <p>{{ $assignment->tahunAjaran->nama ?? 'N/A' }}</p>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-calendar-week"></i> Semester</label>
                            <p>{{ $assignment->semester->nama ?? 'N/A' }}</p>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-check-circle"></i> Status Konfirmasi</label>
                            <p>
                                @if ($assignment->status_konfirmasi == 'Dikonfirmasi')
                                <span class="badge badge-success">Dikonfirmasi</span>
                                @elseif ($assignment->status_konfirmasi == 'Pending')
                                <span class="badge badge-warning">Pending</span>
                                @elseif ($assignment->status_konfirmasi == 'Ditolak')
                                <span class="badge badge-danger">Ditolak</span>
                                @else
                                <span class="badge badge-secondary">{{ $assignment->status_konfirmasi ?? 'N/A' }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                @endif
                
                <div class="btn-action-group">
                    <a href="{{ route('admin.guru-assignments.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                    </a>
                    <div class="btn-right-group">
                        @if (isset($assignment) && $assignment->id)
                        <a href="{{ route('admin.guru-assignments.edit', $assignment->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.guru-assignments.destroy', $assignment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus penugasan ini? Tindakan ini tidak dapat dibatalkan.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    $(function () {
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