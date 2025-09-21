@extends('layouts.app_guru')

@section('title', 'Buku Mengajar')
@section('page_title', 'Buku Mengajar')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Buku Mengajar</h3>
                    <div class="card-tools">
                        <a href="{{ route('guru.teaching-materials.create') }}" class="btn btn-primary btn-sm">Tambah Buku Mengajar</a>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if(session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            {{ session('info') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if($teachingMaterials->isEmpty())
                        <div class="alert alert-info text-center">
                            Belum ada buku mengajar yang diunggah.
                        </div>
                    @else
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>Judul</th>
                                <th>Mata Pelajaran</th>
                                <th>Kelas</th>
                                <th>Deskripsi</th>
                                <th>File</th>
                                <th style="width: 150px;">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($teachingMaterials as $index => $material)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $material->title }}</td>
                                    <td>{{ $material->mataPelajaran->nama_mapel ?? 'N/A' }}</td>
                                    <td>{{ $material->kelas->nama_kelas ?? 'N/A' }}</td>
                                    <td>{{ Str::limit($material->description, 50) }}</td>
                                    <td>
                                        @if($material->file_path)
                                            <a href="{{ route('guru.teaching-materials.download', $material->id) }}" class="btn btn-sm btn-info">Unduh</a>
                                        @else
                                            Tidak Ada
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('guru.teaching-materials.show', $material->id) }}" class="btn btn-sm btn-primary">Lihat</a>
                                        <a href="{{ route('guru.teaching-materials.edit', $material->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('guru.teaching-materials.destroy', $material->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus buku mengajar ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3">
                            {{ $teachingMaterials->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
