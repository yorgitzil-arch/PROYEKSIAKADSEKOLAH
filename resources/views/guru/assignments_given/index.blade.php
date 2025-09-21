@extends('layouts.app_guru')

@section('title', 'Tugas Siswa')
@section('page_title', 'Tugas Siswa')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Tugas yang Diberikan</h3>
                    <div class="card-tools">
                        <a href="{{ route('guru.assignments-given.create') }}" class="btn btn-primary btn-sm">Tambah Tugas Baru</a>
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

                    @if($assignmentsGiven->isEmpty())
                        <div class="alert alert-info text-center">
                            Anda belum memberikan tugas kepada siswa.
                        </div>
                    @else
                        <table id="assignmentsGivenTable" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>Judul Tugas</th>
                                <th>Kelas</th>
                                <th>Mata Pelajaran</th>
                                <th>Deadline</th>
                                <th>Lampiran</th>
                                <th style="width: 150px;">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($assignmentsGiven as $index => $assignment)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $assignment->title }}</td>
                                    <td>{{ $assignment->kelas->nama_kelas ?? 'N/A' }}</td>
                                    <td>{{ $assignment->mataPelajaran->nama_mapel ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($assignment->due_date)->translatedFormat('d F Y') }}</td>
                                    <td>
                                        @if($assignment->file_path)
                                            <a href="{{ Storage::url($assignment->file_path) }}" target="_blank" class="btn btn-sm btn-secondary">Lihat File</a>
                                        @else
                                            Tidak Ada
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('guru.assignments-given.show', $assignment->id) }}" class="btn btn-info btn-sm">Lihat</a>
                                        <a href="{{ route('guru.assignments-given.edit', $assignment->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('guru.assignments-given.destroy', $assignment->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus tugas ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3">
                            {{ $assignmentsGiven->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
