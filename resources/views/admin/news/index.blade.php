@extends('layouts.app_admin') {{-- Asumsi Anda memiliki layout admin --}}

@section('title', 'Manajemen Berita')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Manajemen Berita</h1>

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

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <a href="{{ route('admin.news.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Berita Baru
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>Judul</th>
                            <th>Gambar</th>
                            <th>Deskripsi Singkat</th>
                            <th>Tanggal Publikasi</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($news as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->title }}</td>
                                <td>
                                    @if($item->image_path)
                                        <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->title }}" class="img-thumbnail" style="width: 100px;">
                                    @else
                                        Tidak ada gambar
                                    @endif
                                </td>
                                <td>{{ Str::limit($item->short_description, 100) }}</td>
                                <td>{{ $item->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.news.show', $item->id) }}" class="btn btn-info btn-sm">Lihat</a>
                                    <a href="{{ route('admin.news.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus berita ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada berita yang ditambahkan.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {{ $news->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
