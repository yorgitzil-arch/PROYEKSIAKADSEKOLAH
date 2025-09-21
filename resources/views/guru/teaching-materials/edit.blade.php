@extends('layouts.app_guru')

@section('title', 'Edit Buku Mengajar')
@section('page_title', 'Edit Buku Mengajar')

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

                    @if (!isset($teachingMaterial) || is_null($teachingMaterial->id))
                        <div class="alert alert-danger text-center">
                            Buku mengajar tidak ditemukan atau tidak valid. Silakan kembali ke daftar buku mengajar.
                        </div>
                        <a href="{{ route('guru.teaching-materials.index') }}" class="btn btn-secondary">Kembali ke Daftar Buku Mengajar</a>
                    @else
                        <form action="{{ route('guru.teaching-materials.update', $teachingMaterial->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="kelas_id">Kelas:</label>
                                <select name="kelas_id" id="kelas_id" class="form-control @error('kelas_id') is-invalid @enderror" required>
                                    <option value="">Pilih Kelas</option>
                                    @foreach ($kelasOptions as $id => $nama_kelas)
                                        <option value="{{ $id }}" {{ old('kelas_id', $teachingMaterial->kelas_id) == $id ? 'selected' : '' }}>{{ $nama_kelas }}</option>
                                    @endforeach
                                </select>
                                @error('kelas_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="mata_pelajaran_id">Mata Pelajaran:</label>
                                <select name="mata_pelajaran_id" id="mata_pelajaran_id" class="form-control @error('mata_pelajaran_id') is-invalid @enderror" required>
                                    <option value="">Pilih Mata Pelajaran</option>
                                    @foreach ($mataPelajaranOptions as $id => $nama_mapel)
                                        <option value="{{ $id }}" {{ old('mata_pelajaran_id', $teachingMaterial->mata_pelajaran_id) == $id ? 'selected' : '' }}>{{ $nama_mapel }}</option>
                                    @endforeach
                                </select>
                                @error('mata_pelajaran_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="title">Judul Buku Mengajar:</label>
                                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $teachingMaterial->title) }}" required>
                                @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="description">Deskripsi:</label>
                                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="5">{{ old('description', $teachingMaterial->description) }}</textarea>
                                @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="file_path">File Buku Mengajar (Opsional):</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="file_path" id="file_path" class="custom-file-input @error('file_path') is-invalid @enderror">
                                        <label class="custom-file-label" for="file_path">Pilih file</label>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Format: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, ZIP, RAR. Max: 20MB.</small>
                                @error('file_path') <span class="text-danger">{{ $message }}</span> @enderror

                                @if($teachingMaterial->file_path)
                                    <p class="mt-2">File saat ini: <a href="{{ Storage::url($teachingMaterial->file_path) }}" target="_blank">{{ basename($teachingMaterial->file_path) }}</a></p>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="remove_file" name="remove_file" value="1">
                                        <label class="form-check-label" for="remove_file">Hapus file saat ini</label>
                                    </div>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-primary">Update Buku Mengajar</button>
                            <a href="{{ route('guru.teaching-materials.index') }}" class="btn btn-secondary">Batal</a>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@push('scripts')
    <!-- bs-custom-file-input for file input styling -->
    <script src="{{ asset('adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            bsCustomFileInput.init(); // Inisialisasi custom file input
        });
    </script>
@endpush
