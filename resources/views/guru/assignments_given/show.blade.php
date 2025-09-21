@extends('layouts.app_guru')

@section('title', 'Detail Tugas Siswa')
@section('page_title', 'Detail Tugas Siswa')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Tugas</h3>
                </div>
                <div class="card-body">
                    @if (!isset($assignmentGiven) || is_null($assignmentGiven->id))
                        <div class="alert alert-danger text-center">
                            Tugas tidak ditemukan atau tidak valid. Silakan kembali ke daftar tugas.
                        </div>
                        <a href="{{ route('guru.assignments-given.index') }}" class="btn btn-secondary">Kembali ke Daftar Tugas</a>
                    @else
                        <div class="form-group">
                            <label>Judul Tugas:</label>
                            <p>{{ $assignmentGiven->title ?? 'N/A' }}</p>
                        </div>
                        <div class="form-group">
                            <label>Kelas:</label>
                            <p>{{ $assignmentGiven->kelas->nama_kelas ?? 'N/A' }}</p>
                        </div>
                        <div class="form-group">
                            <label>Mata Pelajaran:</label>
                            <p>{{ $assignmentGiven->mataPelajaran->nama_mapel ?? 'N/A' }}</p>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi:</label>
                            <p>{{ $assignmentGiven->description ?? 'N/A' }}</p>
                        </div>
                        <div class="form-group">
                            <label>Lampiran File:</label>
                            @if($assignmentGiven->file_path)
                                <p><a href="{{ Storage::url($assignmentGiven->file_path) }}" target="_blank" class="btn btn-sm btn-info">Unduh File</a></p>
                            @else
                                <p>Tidak Ada File</p>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Tanggal Deadline:</label>
                            <p>{{ \Carbon\Carbon::parse($assignmentGiven->due_date)->translatedFormat('d F Y') ?? 'N/A' }}</p>
                        </div>

                        <a href="{{ route('guru.assignments-given.index') }}" class="btn btn-secondary">Kembali</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pengumpulan Tugas Siswa</h3>
                </div>
                <div class="card-body">
                    @if($assignmentGiven->submissions->isEmpty())
                        <div class="alert alert-info text-center">
                            Belum ada siswa yang mengumpulkan tugas ini.
                        </div>
                    @else
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Siswa</th>
                                <th>Status</th>
                                <th>Nilai</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($assignmentGiven->submissions as $submission)
                                <tr>
                                    <td>{{ $submission->siswa->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($submission->file_path)
                                            <span class="badge badge-success">Sudah Mengumpulkan</span>
                                        @else
                                            <span class="badge badge-warning">Belum Ada File</span>
                                        @endif
                                    </td>
                                    <td>{{ $submission->score ?? 'Belum Dinilai' }}</td>
                                    <td>
                                        <a href="{{ route('guru.assignments-given.show-submission', $submission->id) }}" class="btn btn-primary btn-sm">Lihat & Nilai</a>
                                        @if($submission->file_path)
                                            <a href="{{ route('guru.assignments-given.download-submission-file', $submission->id) }}" class="btn btn-secondary btn-sm">Unduh File</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Siswa Belum Mengumpulkan</h3>
                </div>
                <div class="card-body">
                    @if($unsubmittedSiswa->isEmpty())
                        <div class="alert alert-success text-center">
                            Semua siswa di kelas ini sudah mengumpulkan tugas.
                        </div>
                    @else
                        <ul class="list-group">
                            @foreach($unsubmittedSiswa as $siswa)
                                <li class="list-group-item">{{ $siswa->name }} (NIS: {{ $siswa->nis }})</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
