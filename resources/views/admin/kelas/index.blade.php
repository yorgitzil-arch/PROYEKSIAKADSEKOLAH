@extends('layouts.app_admin')

@section('title', 'Manajemen Kelas')
@section('page_title', 'Manajemen Kelas')

@push('styles')
    {{-- Toastr for notifications --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        .card-header .card-title {
            display: flex;
            align-items: center;
        }
        .card.card-outline.card-info.shadow {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }
        .card-tools .input-group {
            max-width: 300px;
        }
        .table thead th {
            vertical-align: middle;
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
        }
        .modal-footer .btn {
            border-radius: 4px;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            {{-- Menambahkan kelas shadow untuk tampilan yang seragam --}}
            <div class="card card-outline card-info shadow">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chalkboard-teacher mr-1"></i> Daftar Kelas
                    </h3>
                    <div class="card-tools d-flex align-items-center">
                        <form action="{{ route('admin.kelas.index') }}" method="GET" class="form-inline mr-2">
                            <select name="jurusan_id" class="form-control form-control-sm mr-1">
                                <option value="">Semua Jurusan</option>
                                @foreach($jurusans as $jurusan)
                                    <option value="{{ $jurusan->id }}" {{ request('jurusan_id') == $jurusan->id ? 'selected' : '' }}>{{ $jurusan->nama_jurusan }}</option>
                                @endforeach
                            </select>
                            <select name="tingkat" class="form-control form-control-sm mr-1">
                                <option value="">Semua Tingkat</option>
                                @foreach($tingkats as $tingkat)
                                    <option value="{{ $tingkat }}" {{ request('tingkat') == $tingkat ? 'selected' : '' }}>{{ $tingkat }}</option>
                                @endforeach
                            </select>
                            <div class="input-group input-group-sm">
                                <input type="text" name="search" class="form-control float-right" placeholder="Cari Nama Kelas..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                    @if(request('search') || request('jurusan_id') || request('tingkat'))
                                        <a href="{{ route('admin.kelas.index') }}" class="btn btn-outline-secondary" data-toggle="tooltip" title="Reset Filter">Reset</a>
                                    @endif
                                </div>
                            </div>
                        </form>

                        <a href="{{ route('admin.kelas.create') }}" class="btn btn-info btn-sm" data-toggle="tooltip" title="Tambah Kelas Baru">
                            <i class="fas fa-plus mr-1"></i>Tambah Kelas
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-head-fixed text-nowrap">
                            <thead>
                                <tr>
                                    <th style="width: 50px"><i class="fas fa-hashtag"></i></th>
                                    <th><i class="fas fa-tag mr-1"></i> Nama Kelas</th>
                                    <th><i class="fas fa-sort-numeric-up-alt mr-1"></i> Tingkat</th>
                                    <th><i class="fas fa-pencil-ruler mr-1"></i> Jurusan</th>
                                    <th><i class="fas fa-user-tie mr-1"></i> Wali Kelas</th>
                                    <th style="width: 120px"><i class="fas fa-cogs"></i> Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse ($kelas as $kela)
                                <tr>
                                    <td>{{ $loop->iteration + ($kelas->currentPage() - 1) * $kelas->perPage() }}</td>
                                    <td>{{ $kela->nama_kelas }}</td>
                                    <td><span class="badge badge-primary">{{ $kela->tingkat }}</span></td>
                                    <td>
                                        @if($kela->jurusan)
                                            <span class="badge badge-secondary">{{ $kela->jurusan->nama_jurusan }}</span>
                                        @else
                                            <span class="badge badge-light text-muted"><i class="fas fa-minus-circle mr-1"></i> Tidak Ada Jurusan</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($kela->waliKelas)
                                            <span class="badge badge-info">{{ $kela->waliKelas->name }}</span>
                                        @else
                                            <span class="badge badge-light text-muted"><i class="fas fa-user-times mr-1"></i> Belum Ditentukan</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.kelas.edit', $kela->id) }}" class="btn btn-warning btn-sm btn-action-icon mr-1" data-toggle="tooltip" title="Edit Kelas">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        {{-- MODIFIKASI: Menggunakan tombol untuk memicu modal --}}
                                        <button type="button" class="btn btn-danger btn-sm btn-action-icon" data-toggle="modal" data-target="#deleteModal" data-nama-kelas="{{ $kela->nama_kelas }}" data-action="{{ route('admin.kelas.destroy', $kela->id) }}" title="Hapus Kelas">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-box-open fa-2x text-muted mb-2"></i><br>
                                        Tidak ada kelas yang terdaftar.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer clearfix">
                    {{ $kelas->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Penghapusan --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Penghapusan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus kelas <strong id="namaKelasModal"></strong>? Tindakan ini tidak dapat dibatalkan.</p>
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

            // Menampilkan pesan notifikasi dari session
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

            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Logic untuk modal konfirmasi penghapusan
            $('#deleteModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var namaKelas = button.data('nama-kelas');
                var action = button.data('action');
                var modal = $(this);
                modal.find('#namaKelasModal').text(namaKelas);
                modal.find('#deleteForm').attr('action', action);
            });
        });
    </script>
@endpush
