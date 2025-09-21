@extends('layouts.app_siswa')

@section('title', 'Detail Pengumpulan Tugas')
@section('page_title', 'Detail Pengumpulan Tugas')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Pengumpulan Tugas: {{ $submission->assignmentGiven->title ?? 'N/A' }}</h3>
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
                    @if(session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            {{ session('info') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <dl class="row">
                        <dt class="col-sm-3">Judul Tugas:</dt>
                        <dd class="col-sm-9">{{ $submission->assignmentGiven->title ?? 'N/A' }}</dd>

                        <dt class="col-sm-3">Mata Pelajaran:</dt>
                        <dd class="col-sm-9">{{ $submission->assignmentGiven->mataPelajaran->nama_mapel ?? 'N/A' }}</dd>

                        <dt class="col-sm-3">Guru Pemberi Tugas:</dt>
                        <dd class="col-sm-9">{{ $submission->assignmentGiven->guru->name ?? 'N/A' }}</dd>

                        <dt class="col-sm-3">Kelas:</dt>
                        <dd class="col-sm-9">{{ $submission->assignmentGiven->kelas->nama_kelas ?? 'N/A' }}</dd>

                        <dt class="col-sm-3">Deskripsi Tugas:</dt>
                        <dd class="col-sm-9">{{ $submission->assignmentGiven->description ?? '-' }}</dd>

                        <dt class="col-sm-3">Batas Waktu Pengumpulan:</dt>
                        <dd class="col-sm-9">
                            @if($submission->assignmentGiven->due_date)
                                {{ \Carbon\Carbon::parse($submission->assignmentGiven->due_date)->format('d F Y H:i') }}
                                @if(\Carbon\Carbon::now()->greaterThan($submission->assignmentGiven->due_date))
                                    <span class="badge badge-danger ml-2">Lewat Batas</span>
                                @else
                                    <span class="badge badge-success ml-2">Aktif</span>
                                @endif
                            @else
                                Tidak Ada Batas
                            @endif
                        </dd>

                        <dt class="col-sm-3">File Tugas dari Guru:</dt>
                        <dd class="col-sm-9">
                            @if($submission->assignmentGiven->file_path)
                                <a href="{{ route('guru.assignments-given.download-file', $submission->assignmentGiven->id) }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-download"></i> Unduh File Tugas
                                </a>
                            @else
                                Tidak ada file tugas.
                            @endif
                        </dd>
                    </dl>

                    <hr>

                    <h4>Detail Pengumpulan Anda</h4>
                    <dl class="row">
                        <dt class="col-sm-3">Waktu Dikumpulkan:</dt>
                        <dd class="col-sm-9">
                            {{ \Carbon\Carbon::parse($submission->submitted_at)->format('d F Y H:i') }}
                            @if($submission->assignmentGiven->due_date && \Carbon\Carbon::parse($submission->submitted_at)->greaterThan($submission->assignmentGiven->due_date))
                                <span class="badge badge-danger ml-2">Terlambat</span>
                            @else
                                <span class="badge badge-success ml-2">Tepat Waktu</span>
                            @endif
                        </dd>

                        <dt class="col-sm-3">Catatan Anda:</dt>
                        <dd class="col-sm-9">{{ $submission->notes ?? '-' }}</dd>

                        <dt class="col-sm-3">File Jawaban Anda:</dt>
                        <dd class="col-sm-9">
                            @if($submission->file_path)
                                <a href="{{ route('siswa.assignments-submissions.download-file', $submission->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download"></i> Unduh File Jawaban
                                </a>
                            @else
                                Tidak ada file jawaban.
                            @endif
                        </dd>

                        <dt class="col-sm-3">Nilai:</dt>
                        <dd class="col-sm-9">
                            @if(is_numeric($submission->score))
                                <span class="badge badge-{{ $submission->score >= 75 ? 'primary' : 'warning' }}">{{ $submission->score }}</span>
                            @else
                                <span class="badge badge-info">Belum Dinilai</span>
                            @endif
                        </dd>

                        <dt class="col-sm-3">Feedback Guru:</dt>
                        <dd class="col-sm-9">{{ $submission->feedback ?? '-' }}</dd>
                    </dl>

                    <div class="mt-4">
                        <a href="{{ route('siswa.assignments-submissions.index') }}" class="btn btn-secondary">Kembali ke Daftar Tugas</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Script tambahan jika diperlukan --}}
@endpush
