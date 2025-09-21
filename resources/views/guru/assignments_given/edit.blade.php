@extends('layouts.app_guru')

@section('title', 'Edit Tugas Siswa')
@section('page_title', 'Edit Tugas Siswa')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
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

                    @if (!isset($assignmentGiven) || is_null($assignmentGiven->id))
                        <div class="alert alert-danger text-center">
                            Tugas tidak ditemukan atau tidak valid. Silakan kembali ke daftar tugas.
                        </div>
                        <a href="{{ route('guru.assignments-given.index') }}" class="btn btn-secondary">Kembali ke Daftar Tugas</a>
                    @else
                        <form action="{{ route('guru.assignments-given.update', $assignmentGiven->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="kelas_id">Kelas:</label>
                                <select name="kelas_id" id="kelas_id" class="form-control @error('kelas_id') is-invalid @enderror" required>
                                    <option value="">Pilih Kelas</option>
                                    @foreach ($kelasOptions as $id => $nama_kelas)
                                        <option value="{{ $id }}" {{ old('kelas_id', $assignmentGiven->kelas_id) == $id ? 'selected' : '' }}>{{ $nama_kelas }}</option>
                                    @endforeach
                                </select>
                                @error('kelas_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="mata_pelajaran_id">Mata Pelajaran:</label>
                                <select name="mata_pelajaran_id" id="mata_pelajaran_id" class="form-control @error('mata_pelajaran_id') is-invalid @enderror" required>
                                    <option value="">Pilih Mata Pelajaran</option>
                                    @foreach ($mataPelajaranOptions as $id => $nama_mapel)
                                        <option value="{{ $id }}" {{ old('mata_pelajaran_id', $assignmentGiven->mata_pelajaran_id) == $id ? 'selected' : '' }}>{{ $nama_mapel }}</option>
                                    @endforeach
                                </select>
                                @error('mata_pelajaran_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="title">Judul Tugas:</label>
                                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $assignmentGiven->title) }}" required>
                                @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="description">Deskripsi:</label>
                                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="5">{{ old('description', $assignmentGiven->description) }}</textarea>
                                @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="file_assignment">Lampiran File (Opsional):</label>
                                <input type="file" name="file_assignment" id="file_assignment" class="form-control-file @error('file_assignment') is-invalid @enderror">
                                <small class="form-text text-muted">Format: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, JPG, JPEG, PNG. Max: 10MB.</small>
                                @error('file_assignment') <span class="text-danger">{{ $message }}</span> @enderror

                                @if($assignmentGiven->file_path)
                                    <p class="mt-2">File saat ini: <a href="{{ Storage::url($assignmentGiven->file_path) }}" target="_blank">{{ basename($assignmentGiven->file_path) }}</a></p>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="remove_file" name="remove_file" value="1">
                                        <label class="form-check-label" for="remove_file">Hapus file saat ini</label>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="due_date">Tanggal Deadline:</label>
                                <input type="date" name="due_date" id="due_date" class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date', $assignmentGiven->due_date ? \Carbon\Carbon::parse($assignmentGiven->due_date)->format('Y-m-d') : '') }}" required>
                                @error('due_date') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Update Tugas</button>
                            <a href="{{ route('guru.assignments-given.index') }}" class="btn btn-secondary">Batal</a>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
