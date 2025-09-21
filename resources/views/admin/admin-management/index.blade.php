@extends('layouts.app_admin')

@section('title', 'Manajemen Admin')
@section('page_title', 'Manajemen Admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Administrator</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.admin-management.create') }}" class="btn btn-primary btn-sm rounded-lg">
                            <i class="fas fa-plus mr-1"></i> Tambah Admin Baru
                        </a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show rounded-lg m-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show rounded-lg m-3" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($admins as $admin)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $admin->name }}</td>
                                        <td>{{ $admin->email }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm delete-btn rounded-lg" data-toggle="modal" data-target="#deleteConfirmationModal" data-admin-id="{{ $admin->id }}">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada admin lain yang terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-lg">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus admin ini? Tindakan ini tidak dapat dibatalkan.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-lg" data-dismiss="modal">Batal</button>
                    <form id="delete-form" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger rounded-lg">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Menangkap event klik pada tombol hapus
            $('.delete-btn').on('click', function() {
                var adminId = $(this).data('admin-id');
                var form = $('#delete-form');
                
                // Mengatur action form dengan id admin yang sesuai
                var actionUrl = "{{ route('admin.admin-management.destroy', ':id') }}";
                actionUrl = actionUrl.replace(':id', adminId);
                form.attr('action', actionUrl);
            });

            // Tambahkan event listener untuk mencegah form submit jika modal belum dikonfirmasi
            $('#deleteConfirmationModal').on('submit', '#delete-form', function(e) {
                // Biarkan form disubmit, karena kita sudah mengonfirmasi melalui modal
            });
        });
    </script>
@endpush
