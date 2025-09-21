@extends('layouts.app_admin')

@section('title', 'Manajemen Akun Siswa')
@section('page_title', 'Manajemen Akun Siswa')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-primary"> {{-- Tambahkan card-outline dan card-primary untuk border warna --}}
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-graduate mr-1"></i> Daftar Akun Siswa {{-- Ikon lebih relevan --}}
                    </h3>
                    <div class="card-tools">
                        <form action="{{ route('admin.siswa-management.index') }}" method="GET" class="input-group input-group-sm d-inline-flex" style="width: 250px;"> {{-- Gunakan d-inline-flex --}}
                            <input type="text" name="search" class="form-control float-right" placeholder="Cari Siswa (Nama, NIS, NISN, Kelas)" value="{{ $search }}"> {{-- Update placeholder --}}
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                        <a href="{{ route('admin.siswa-management.create') }}" class="btn btn-success btn-sm ml-2"> {{-- Ubah ke btn-success untuk warna lebih menarik --}}
                            <i class="fas fa-user-plus"></i> Tambah Akun Siswa {{-- Ikon lebih spesifik --}}
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-3" role="alert"> {{-- Tambahkan margin --}}
                            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert"> {{-- Tambahkan margin --}}
                            <i class="fas fa-times-circle mr-2"></i> {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert"> {{-- Tambahkan margin --}}
                            <h5><i class="icon fas fa-exclamation-triangle mr-2"></i> Terjadi Kesalahan Validasi!</h5> {{-- Ubah ikon --}}
                            <ul class="mb-0"> {{-- Hapus margin bawah default dari ul --}}
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive"> {{-- Tambahkan div ini untuk responsivitas tabel --}}
                        <table class="table table-hover table-head-fixed text-nowrap"> {{-- Ganti table-striped, tambahkan table-hover dan table-head-fixed, text-nowrap --}}
                            <thead>
                            <tr>
                                <th style="width: 50px;">#</th> {{-- Lebar tetap untuk nomor --}}
                                <th>NIS</th>
                                <th>NISN</th> {{-- === KOREKSI PENTING: TAMBAHKAN KOLOM NISN DI SINI === --}}
                                <th>Nama Lengkap</th> {{-- Ubah ke "Nama Lengkap" untuk kejelasan --}}
                                <th>Email</th>
                                <th>Kelas</th>
                                <th style="width: 150px;">Aksi</th> {{-- Lebar tetap untuk aksi --}}
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($siswas as $siswa)
                                <tr>
                                    <td>{{ $loop->iteration + ($siswas->currentPage() - 1) * $siswas->perPage() }}</td>
                                    <td>{{ $siswa->nis }}</td>
                                    <td>{{ $siswa->nisn ?? '-' }}</td> {{-- === KOREKSI PENTING: TAMPILKAN DATA NISN DI SINI === --}}
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($siswa->foto_profile_path)
                                                <img src="{{ asset('storage/' . $siswa->foto_profile_path) }}" class="img-circle img-size-32 mr-2" alt="User Image" style="object-fit: cover;">
                                            @else
                                                <img src="{{ asset('adminlte/dist/img/default-avatar.png') }}" class="img-circle img-size-32 mr-2" alt="Default Avatar" style="object-fit: cover;"> {{-- Tambahkan default avatar jika tidak ada foto --}}
                                            @endif
                                            <span>{{ $siswa->name }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $siswa->email ?? '-' }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $siswa->kelas->nama_kelas ?? 'Belum Ditentukan' }}</span> {{-- Gunakan badge untuk kelas --}}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.siswa-management.edit', $siswa->id) }}" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Edit Akun"> {{-- Tambah tooltip --}}
                                            <i class="fas fa-edit"></i> {{-- Hapus teks "Edit" --}}
                                        </a>
                                        <form action="{{ route('admin.siswa-management.destroy', $siswa->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus akun siswa {{ $siswa->name }} ini? Data yang terhubung juga akan terhapus.');"> {{-- Konfirmasi lebih detail --}}
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus Akun"> {{-- Tambah tooltip --}}
                                                <i class="fas fa-trash"></i> {{-- Hapus teks "Hapus" --}}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4"> {{-- Ubah colspan menjadi 7 karena ada NISN --}}
                                        <i class="fas fa-box-open fa-2x text-muted mb-2"></i><br>
                                        Tidak ada akun siswa yang terdaftar.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div> {{-- Tutup table-responsive --}}
                </div>
                <div class="card-footer clearfix">
                    {{ $siswas->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            // Inisialisasi tooltip
            $('[data-toggle="tooltip"]').tooltip();

            // Optional: Jika ingin menggunakan DataTables (butuh konfigurasi lebih lanjut)
            // Jika Anda ingin tabel bisa sorting, searching, pagination otomatis tanpa custom search form
            // $(document).ready(function() {
            //     $('#siswaTable').DataTable({
            //         "paging": true,
            //         "lengthChange": false,
            //         "searching": false, // Karena Anda sudah punya search form sendiri
            //         "ordering": true,
            //         "info": true,
            //         "autoWidth": false,
            //         "responsive": true,
            //     });
            // });
        });
    </script>
@endpush

@push('styles')
    <style>
        /* Tambahkan style kustom jika diperlukan */
        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05); /* Sedikit highlight biru saat hover */
        }
        .img-size-32 {
            width: 32px;
            height: 32px;
        }
        .card-tools .input-group-sm {
            max-width: 250px; /* Batasi lebar form search */
        }
    </style>
@endpush
