@extends('layouts.app_guru')

@section('title', 'Detail Pengumpulan Tugas')
@section('page_title', 'Detail Pengumpulan Tugas')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Pengumpulan</h3>
                </div>
                <div class="card-body">
                    @if (!isset($submission) || is_null($submission->id))
                        <div class="alert alert-danger text-center">
                            Pengumpulan tugas tidak ditemukan atau tidak valid.
                        </div>
                        <a href="{{ route('guru.assignments-given.index') }}" class="btn btn-secondary">Kembali ke Daftar Tugas</a>
                    @else
                        <div class="form-group">
                            <label>Tugas:</label>
                            <p>{{ $submission->assignmentGiven->title ?? 'N/A' }}</p>
                        </div>
                        <div class="form-group">
                            <label>Siswa:</label>
                            <p>{{ $submission->siswa->name ?? 'N/A' }} (NIS: {{ $submission->siswa->nis ?? 'N/A' }})</p>
                        </div>
                        <div class="form-group">
                            <label>Kelas:</label>
                            <p>{{ $submission->assignmentGiven->kelas->nama_kelas ?? 'N/A' }}</p>
                        </div>
                        <div class="form-group">
                            <label>Mata Pelajaran:</label>
                            <p>{{ $submission->assignmentGiven->mataPelajaran->nama_mapel ?? 'N/A' }}</p>
                        </div>
                        <div class="form-group">
                            <label>Waktu Pengumpulan:</label>
                            <p>{{ \Carbon\Carbon::parse($submission->submitted_at)->format('d F Y H:i') ?? 'N/A' }}</p>
                        </div>
                        <div class="form-group">
                            <label>Status Pengumpulan:</label>
                            <p>
                                @if($submission->file_path)
                                    <span class="badge badge-success">Sudah Mengumpulkan</span>
                                @else
                                    <span class="badge badge-warning">Belum Ada File</span>
                                @endif
                            </p>
                        </div>
                        <div class="form-group">
                            <label>File Pengumpulan:</label>
                            @if($submission->file_path)
                                <p><a href="{{ route('guru.assignments-given.download-submission-file', $submission->id) }}" class="btn btn-sm btn-info">Unduh File</a></p>
                            @else
                                <p>Tidak Ada File</p>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Catatan Siswa:</label>
                            <p>{{ $submission->notes ?? 'Tidak ada catatan' }}</p>
                        </div>

                        <a href="{{ route('guru.assignments-given.show', $submission->assignmentGiven->id) }}" class="btn btn-secondary">Kembali ke Detail Tugas</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Beri Nilai & Feedback</h3>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (isset($submission) && !is_null($submission->id))
                        <form action="{{ route('guru.assignments-given.grade-submission', $submission->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="score">Nilai (0-100):</label>
                                <input type="number" name="score" id="score" class="form-control @error('score') is-invalid @enderror" min="0" max="100" value="{{ old('score', $submission->score) }}" required>
                                @error('score') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="feedback">Feedback:</label>
                                <textarea name="feedback" id="feedback" class="form-control @error('feedback') is-invalid @enderror" rows="5">{{ old('feedback', $submission->feedback) }}</textarea>
                                @error('feedback') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Nilai</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
