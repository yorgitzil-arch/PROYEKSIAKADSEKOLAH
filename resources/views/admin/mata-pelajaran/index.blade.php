@extends('layouts.app_admin')

@section('title', 'Manajemen Mata Pelajaran')
@section('page_title', 'Manajemen Mata Pelajaran')

@push('styles')
    <!-- CSS untuk Toastr untuk notifikasi yang rapi -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        /* Gaya kustom untuk tampilan yang lebih bersih dan modern */
        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, .125);
            padding-bottom: 1rem;
        }

        .card-header .card-title {
            display: flex;
            align-items: center;
        }

        .card-tools .input-group {
            max-width: 300px;
        }

        .table thead th {
            vertical-align: middle;
            font-weight: 600;
        }

        .badge {
            font-size: 85%;
            padding: 0.4em 0.6em;
        }

        .btn-action-icon {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease-in-out;
        }
        
        .btn-action-icon:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        .empty-state {
            color: #6c757d;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-primary shadow"> {{-- Menambahkan shadow untuk efek visual --}}
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-book-open mr-1"></i> Daftar Mata Pelajaran
                    </h3>
                    <div class="card-tools d-flex align-items-center">
                        <form action="{{ route('admin.mata-pelajaran.index') }}" method="GET" class="input-group input-group-sm mr-2">
                            <input type="text" name="search" class="form-control float-right" placeholder="Cari Mata Pelajaran..." value="{{ $search }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                                @if($search)
                                    <a href="{{ route('admin.mata-pelajaran.index') }}" class="btn btn-outline-secondary" data-toggle="tooltip" title="Reset Filter">Reset</a>
                                @endif
                            </div>
                        </form>
                        <a href="{{ route('admin.mata-pelajaran.create') }}" class="btn btn-success btn-sm" data-toggle="tooltip" title="Tambah Mata Pelajaran Baru">
                            <i class="fas fa-plus mr-1"></i> Tambah Mapel
                        </a>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-head-fixed text-nowrap">
                            <thead>
                            <tr>
                                <th style="width: 50px"><i class="fas fa-hashtag"></i></th>
                                <th><i class="fas fa-book mr-1"></i> Nama Mata Pelajaran</th>
                                <th><i class="fas fa-barcode mr-1"></i> Kode Mata Pelajaran</th>
                                <th style="width: 120px"><i class="fas fa-cogs"></i> Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($mataPelajarans as $mapel)
                                <tr>
                                    <td>{{ $loop->iteration + ($mataPelajarans->currentPage() - 1) * $mataPelajarans->perPage() }}</td>
                                    <td>{{ $mapel->nama_mapel }}</td>
                                    <td><span class="badge badge-secondary">{{ $mapel->kode_mapel ?? '-' }}</span></td>
                                    <td>
                                        <a href="{{ route('admin.mata-pelajaran.edit', $mapel->id) }}" class="btn btn-warning btn-sm btn-action-icon mr-1" data-toggle="tooltip" title="Edit Mata Pelajaran">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.mata-pelajaran.destroy', $mapel->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata pelajaran {{ $mapel->nama_mapel }}? Tindakan ini tidak dapat dibatalkan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm btn-action-icon" data-toggle="tooltip" title="Hapus Mata Pelajaran">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 empty-state">
                                        <i class="fas fa-box-open fa-2x mb-2"></i><br>
                                        Tidak ada mata pelajaran yang terdaftar.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="card-footer clearfix">
                    {{ $mataPelajarans->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS untuk Toastr dan Tooltip -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(function() {
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

            // Inisialisasi tooltip pada elemen dengan atribut data-toggle="tooltip"
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush
