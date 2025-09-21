@extends('layouts.app_guru')

@section('title', 'Tambah Buku Mengajar')
@section('page_title', 'Tambah Buku Mengajar')

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

                    <form action="{{ route('guru.teaching-materials.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="kelas_id">Kelas:</label>
                            <select name="kelas_id" id="kelas_id" class="form-control @error('kelas_id') is-invalid @enderror" required>
                                <option value="">Pilih Kelas</option>
                                @foreach ($kelasOptions as $id => $nama_kelas)
                                    <option value="{{ $id }}" {{ old('kelas_id') == $id ? 'selected' : '' }}>{{ $nama_kelas }}</option>
                                @endforeach
                            </select>
                            @error('kelas_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="mata_pelajaran_id">Mata Pelajaran:</label>
                            <select name="mata_pelajaran_id" id="mata_pelajaran_id" class="form-control @error('mata_pelajaran_id') is-invalid @enderror" required>
                                <option value="">Pilih Mata Pelajaran</option>
                                @foreach ($mataPelajaranOptions as $id => $nama_mapel)
                                    <option value="{{ $id }}" {{ old('mata_pelajaran_id') == $id ? 'selected' : '' }}>{{ $nama_mapel }}</option>
                                @endforeach
                            </select>
                            @error('mata_pelajaran_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="title">Judul Buku Mengajar:</label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                            @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="description">Deskripsi:</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="5">{{ old('description') }}</textarea>
                            @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="file_path">File Buku Mengajar:</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="file_path" id="file_path" class="custom-file-input @error('file_path') is-invalid @enderror" required>
                                    <label class="custom-file-label" for="file_path">Pilih file</label>
                                </div>
                            </div>
                            <small class="form-text text-muted">Format: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, ZIP, RAR. Max: 20MB.</small>
                            @error('file_path') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Buku Mengajar</button>
                        <a href="{{ route('guru.teaching-materials.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
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
