@extends('layouts.app_guru')

@section('title', 'Pengumuman Siswa')
@section('page_title', 'Pengumuman Siswa')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Pengumuman untuk Siswa</h3>
                    <div class="card-tools">
                        <a href="{{ route('guru.student-announcements.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Buat Pengumuman Baru
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Pesan Sukses/Error --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if($announcements->isEmpty())
                        <div class="alert alert-info text-center">
                            Anda belum membuat pengumuman apa pun untuk siswa.
                        </div>
                    @else
                        <table id="studentAnnouncementsTable" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>Judul Pengumuman</th>
                                <th>Ditujukan untuk Kelas</th>
                                <th>Isi Pengumuman</th>
                                <th>Tanggal Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($announcements as $announcement)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $announcement->title }}</td>
                                    <td>{{ $announcement->kelas->nama_kelas ?? 'Semua Kelas' }}</td>
                                    <td>{{ Str::limit($announcement->message, 100) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($announcement->created_at)->format('d F Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('guru.student-announcements.show', $announcement->id) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('guru.student-announcements.edit', $announcement->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('guru.student-announcements.destroy', $announcement->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Anda bisa menambahkan DataTables di sini jika diperlukan --}}
@endpush
