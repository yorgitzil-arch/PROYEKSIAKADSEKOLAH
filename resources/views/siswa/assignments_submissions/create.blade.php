@extends('layouts.app_siswa')

@section('title', 'Kumpul Tugas')
@section('page_title', 'Kumpul Tugas')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Kumpulkan Tugas: {{ $assignmentGiven->title }}</h3>
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
                    @if(session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            {{ session('info') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <dl class="row">
                        <dt class="col-sm-3">Mata Pelajaran:</dt>
                        <dd class="col-sm-9">{{ $assignmentGiven->mataPelajaran->nama_mapel ?? 'N/A' }}</dd>

                        <dt class="col-sm-3">Guru Pengajar:</dt>
                        <dd class="col-sm-9">{{ $assignmentGiven->guru->name ?? 'N/A' }}</dd>

                        <dt class="col-sm-3">Kelas:</dt>
                        <dd class="col-sm-9">{{ $assignmentGiven->kelas->nama_kelas ?? 'N/A' }}</dd>

                        <dt class="col-sm-3">Deskripsi Tugas:</dt>
                        <dd class="col-sm-9">{{ $assignmentGiven->description ?? '-' }}</dd>

                        <dt class="col-sm-3">Batas Waktu:</dt>
                        <dd class="col-sm-9">
                            @if($assignmentGiven->due_date)
                                {{ \Carbon\Carbon::parse($assignmentGiven->due_date)->format('d F Y H:i') }}
                                @if(\Carbon\Carbon::now()->greaterThan($assignmentGiven->due_date))
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
                            @if($assignmentGiven->file_path)
                                <a href="{{ route('guru.assignments-given.download-file', $assignmentGiven->id) }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-download"></i> Unduh File Tugas
                                </a>
                            @else
                                Tidak ada file tugas.
                            @endif
                        </dd>
                    </dl>
                    <hr>

                    @if($assignmentGiven->due_date && \Carbon\Carbon::now()->greaterThan($assignmentGiven->due_date))
                        <div class="alert alert-danger text-center">
                            Maaf, batas waktu pengumpulan tugas ini sudah lewat. Anda tidak dapat mengumpulkan tugas.
                        </div>
                        <a href="{{ route('siswa.assignments-submissions.index') }}" class="btn btn-secondary">Kembali ke Daftar Tugas</a>
                    @else
                        <form action="{{ route('siswa.assignments-submissions.store', $assignmentGiven->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="file_submission">File Jawaban Anda (Opsional)</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="file_submission" id="file_submission" class="custom-file-input @error('file_submission') is-invalid @enderror">
                                        <label class="custom-file-label" for="file_submission">Pilih file jawaban</label>
                                    </div>
                                </div>
                                @error('file_submission')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <small class="form-text text-muted">Format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, JPEG, PNG (Max 10MB)</small>
                            </div>

                            <div class="form-group">
                                <label for="notes">Catatan untuk Guru (Opsional)</label>
                                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="4">{{ old('notes') }}</textarea>
                                @error('notes')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Kumpulkan Tugas</button>
                            <a href="{{ route('siswa.assignments-submissions.index') }}" class="btn btn-secondary">Batal</a>
                        </form>

                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- bs-custom-file-input for file input styling -->
    <script src="{{ asset('adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            bsCustomFileInput.init(); // Inisialisasi custom file input
        });
    </script>
@endpush
