@extends('layouts.app_admin')

@section('title', 'Manajemen Jurusan')
@section('page_title', 'Daftar Jurusan')

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
                        <i class="fas fa-graduation-cap mr-1"></i> Daftar Jurusan
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.jurusans.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus mr-1"></i> Tambah Jurusan
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Alert Messages digantikan oleh Toastr --}}
                    
                    <form action="{{ route('admin.jurusans.index') }}" method="GET" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari nama atau kode jurusan..." value="{{ $search }}">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                @if($search)
                                    <a href="{{ route('admin.jurusans.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-sync-alt"></i> Reset
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Nama Jurusan</th>
                                    <th>Kode Jurusan</th>
                                    <th>Deskripsi</th>
                                    <th style="width: 150px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($jurusans as $jurusan)
                                    <tr>
                                        <td>{{ $loop->iteration + ($jurusans->currentPage() - 1) * $jurusans->perPage() }}</td>
                                        <td>{{ $jurusan->nama_jurusan }}</td>
                                        <td>{{ $jurusan->kode_jurusan }}</td>
                                        <td>{{ Str::limit($jurusan->deskripsi, 70) ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('admin.jurusans.edit', $jurusan->id) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit mr-1"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.jurusans.destroy', $jurusan->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus jurusan ini?')">
                                                    <i class="fas fa-trash-alt mr-1"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada data jurusan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer clearfix">
                    {{ $jurusans->links('vendor.pagination.bootstrap-4') }}
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
