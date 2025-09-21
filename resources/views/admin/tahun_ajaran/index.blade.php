@extends('layouts.app_admin')

@section('title', 'Manajemen Tahun Ajaran')
@section('page_title', 'Manajemen Tahun Ajaran')

@push('styles')
    {{-- Menambahkan link Toastr untuk notifikasi yang konsisten --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        /* Menggunakan kelas AdminLTE, hanya tambahkan shadow jika diperlukan */
        .card.card-outline.card-primary.shadow {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            {{-- Menggunakan card-outline dan shadow untuk konsistensi --}}
            <div class="card card-outline card-primary shadow">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-calendar-alt mr-1"></i> Daftar Tahun Ajaran
                    </h3>
                    {{-- Tombol "Tambah" ditempatkan di dalam card-tools --}}
                    <div class="card-tools">
                        <a href="{{ route('admin.tahun-ajaran.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus mr-1"></i> Tambah Tahun Ajaran
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Menggunakan alert AdminLTE yang lebih modern dan konsisten --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-times-circle mr-2"></i> {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if (session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="fas fa-info-circle mr-2"></i> {{ session('info') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tahunAjarans as $tahunAjaran)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $tahunAjaran->nama }}</td>
                                        <td>
                                            @if ($tahunAjaran->is_active)
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-secondary">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.tahun-ajaran.edit', $tahunAjaran->id) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.tahun-ajaran.destroy', $tahunAjaran->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus tahun ajaran ini? Ini akan menghapus semua semester dan data terkait di dalamnya!')">
                                                    <i class="fas fa-trash-alt"></i> Hapus
                                                </button>
                                            </form>
                                            @if (!$tahunAjaran->is_active)
                                                <form action="{{ route('admin.tahun-ajaran.toggle-active', $tahunAjaran->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Mengaktifkan tahun ajaran ini akan menonaktifkan tahun ajaran lainnya. Lanjutkan?')">
                                                        <i class="fas fa-check-circle"></i> Aktifkan
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada data tahun ajaran.</td>
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
    {{-- Menambahkan script Toastr untuk notifikasi yang konsisten --}}
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

            // Menampilkan pesan notifikasi dari session
            @if (session('success'))
                toastr.success("{{ session('success') }}");
            @endif
            @if (session('error'))
                toastr.error("{{ session('error') }}");
            @endif
            @if (session('info'))
                toastr.info("{{ session('info') }}");
            @endif
        });
    </script>
@endpush
