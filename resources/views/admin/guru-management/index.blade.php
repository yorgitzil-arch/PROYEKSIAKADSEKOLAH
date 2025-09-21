@extends('layouts.app_admin')

@section('title', 'Manajemen Akun Guru')
@section('page_title', 'Manajemen Akun Guru')

@push('styles')
    <!-- CSS untuk Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        /* Custom CSS untuk tampilan yang lebih modern dan konsisten */
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

        .card-tools {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-left: auto;
        }

        /* Styling untuk Search Bar */
        .input-group.input-group-sm {
            width: 300px !important;
            max-width: 100%;
        }

        .form-control.float-right {
            border-radius: 8px 0 0 8px;
            border: 1px solid #ced4da;
            padding: 0.75rem 1rem;
            height: auto;
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control.float-right:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .input-group-append .btn-default {
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 0 8px 8px 0;
            color: #ffffff;
            padding: 0.75rem 1rem;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .input-group-append .btn-default:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-outline-secondary {
            border-radius: 8px;
            border-color: #6c757d;
            color: #6c757d;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            color: #fff;
        }
        
        /* Styling untuk Tombol Tambah Akun Guru */
        .btn-success {
            background: linear-gradient(45deg, #28a745, #218838);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            color: #ffffff;
            box-shadow: 0 4px 8px rgba(40, 167, 69, 0.2);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(40, 167, 69, 0.3);
            background: linear-gradient(45deg, #218838, #28a745);
        }

        /* Styling untuk Tabel */
        .table {
            width: 100%;
            margin-bottom: 0;
            color: #343a40;
        }

        .table thead th {
            background-color: #f8f9fa;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
            padding: 1rem;
            font-weight: 600;
            text-align: left;
        }

        .table tbody tr {
            transition: background-color 0.2s ease;
        }

        .table tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, .03);
        }

        .table tbody tr:hover {
            background-color: rgba(0, 0, 0, .07);
        }

        .table td, .table th {
            padding: 1rem;
            vertical-align: middle;
            border-top: 1px solid #dee2e6;
        }

        /* Styling untuk Tombol Aksi di Tabel */
        .btn-action-icon {
            width: 36px; /* Increased size for better touch/click target */
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            border-radius: 8px; /* Slightly more rounded */
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
        }

        .btn-warning:hover {
            background-color: #e0a800;
            border-color: #d39e00;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.15);
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            color: #ffffff;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.15);
        }
        
        .badge {
            font-weight: 600;
            padding: 0.5rem 0.8rem;
            border-radius: 12px;
            text-transform: uppercase;
        }

        /* Modal Kustom */
        .modal-content {
            border-radius: 15px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }
        .modal-header {
            border-bottom: none;
            padding: 1.5rem;
            color: #dc3545;
        }
        .modal-footer {
            border-top: none;
            padding: 1.5rem;
            justify-content: center;
            gap: 15px;
        }
        .modal-body {
            padding: 1rem 1.5rem;
        }

        /* Responsive adjustments */
        @media (max-width: 767.98px) {
            .card-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .card-tools {
                margin-top: 15px;
                width: 100%;
                justify-content: flex-start;
            }
            .input-group.input-group-sm {
                width: 100% !important;
            }
            .btn-success,
            .btn-outline-secondary {
                width: 100%;
                margin-top: 10px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-users-cog mr-1"></i> Daftar Akun Guru
                    </h3>
                    <div class="card-tools d-flex align-items-center">
                        <form action="{{ route('admin.guru-management.index') }}" method="GET" class="input-group input-group-sm mr-2">
                            <input type="text" name="search" class="form-control float-right" placeholder="Cari Guru (Nama, NIP, Email)..." value="{{ $search }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default" data-toggle="tooltip" title="Cari">
                                    <i class="fas fa-search"></i>
                                </button>
                                @if($search)
                                <a href="{{ route('admin.guru-management.index') }}" class="btn btn-outline-secondary" data-toggle="tooltip" title="Reset Filter">Reset</a>
                                @endif
                            </div>
                        </form>
                        <a href="{{ route('admin.guru-management.create') }}" class="btn btn-success" data-toggle="tooltip" title="Tambah Akun Guru Baru">
                            <i class="fas fa-user-plus mr-1"></i> Tambah Akun Guru
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-head-fixed text-nowrap">
                            <thead>
                                <tr>
                                    <th style="width: 50px"><i class="fas fa-hashtag"></i></th>
                                    <th><i class="fas fa-id-card-alt mr-1"></i> NIP</th>
                                    <th><i class="fas fa-user mr-1"></i> Nama</th>
                                    <th><i class="fas fa-envelope mr-1"></i> Email</th>
                                    <th style="width: 120px"><i class="fas fa-cogs"></i> Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($gurus as $guru)
                                    <tr>
                                        <td>{{ $loop->iteration + ($gurus->currentPage() - 1) * $gurus->perPage() }}</td>
                                        <td><span class="badge badge-primary">{{ $guru->nip }}</span></td>
                                        <td>{{ $guru->name }}</td>
                                        <td>{{ $guru->email ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('admin.guru-management.edit', $guru->id) }}" class="btn btn-warning btn-sm btn-action-icon mr-1" data-toggle="tooltip" title="Edit Akun Guru">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            {{-- Ganti form onsubmit dengan tombol yang memicu modal --}}
                                            <button type="button" class="btn btn-danger btn-sm btn-action-icon delete-btn" data-toggle="modal" data-target="#deleteModal" data-nama="{{ $guru->name }}" data-id="{{ $guru->id }}" data-url="{{ route('admin.guru-management.destroy', $guru->id) }}" title="Hapus Akun Guru">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <i class="fas fa-users-slash fa-2x text-muted mb-2"></i><br>
                                            Tidak ada akun guru yang terdaftar.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer clearfix">
                    {{ $gurus->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
    
    <!-- Custom Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel"><i class="fas fa-exclamation-triangle mr-2"></i>Konfirmasi Hapus</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <p>Apakah Anda yakin ingin menghapus akun guru **<span id="guruNama"></span>**? Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(function () {
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

            // Menampilkan pesan notifikasi dari session jika ada
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

            // Inisialisasi tooltip
            $('[data-toggle="tooltip"]').tooltip();

            // Handle delete button click untuk menampilkan modal
            $('#deleteModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var namaGuru = button.data('nama');
                var formUrl = button.data('url');
                
                var modal = $(this);
                modal.find('#guruNama').text(namaGuru);
                modal.find('#deleteForm').attr('action', formUrl);
            });
        });
    </script>
@endpush
