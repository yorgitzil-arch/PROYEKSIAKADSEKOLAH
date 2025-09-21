@extends('layouts.app_guru')

@section('title', 'Berikan Tugas Baru')
@section('page_title', 'Berikan Tugas Baru')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Form Pemberian Tugas</h3>
                </div>
                <form action="{{ route('guru.assignments-given.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        {{-- Dropdown Mata Pelajaran --}}
                        <div class="form-group">
                            <label for="mata_pelajaran_id">Mata Pelajaran: <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-book"></i></span>
                                </div>
                                <select name="mata_pelajaran_id" id="mata_pelajaran_id" class="form-control @error('mata_pelajaran_id') is-invalid @enderror" required>
                                    <option value="">Pilih Mata Pelajaran</option>
                                    @foreach ($mataPelajaranOptions as $id => $name)
                                        <option value="{{ $id }}" {{ old('mata_pelajaran_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('mata_pelajaran_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Dropdown Kelas --}}
                        <div class="form-group">
                            <label for="kelas_id">Kelas: <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-school"></i></span>
                                </div>
                                <select name="kelas_id" id="kelas_id" class="form-control @error('kelas_id') is-invalid @enderror" required>
                                    <option value="">Pilih Kelas</option>
                                    @foreach ($kelasOptions as $id => $name)
                                        <option value="{{ $id }}" {{ old('kelas_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('kelas_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Input Judul Tugas --}}
                        <div class="form-group">
                            <label for="title">Judul Tugas: <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-heading"></i></span>
                                </div>
                                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Masukkan judul tugas" required>
                                @error('title') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Textarea Deskripsi --}}
                        <div class="form-group">
                            <label for="description">Deskripsi Tugas:</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="Masukkan deskripsi tugas (opsional)">{{ old('description') }}</textarea>
                            @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        {{-- Input Tanggal Batas Waktu --}}
                        <div class="form-group">
                            <label for="due_date">Batas Waktu Pengumpulan:</label>
                            <div class="input-group date" id="due_date_picker" data-target-input="nearest">
                                <div class="input-group-prepend" data-target="#due_date_picker" data-toggle="datetimepicker">
                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                </div>
                                <input type="text" name="due_date" id="due_date" class="form-control datetimepicker-input @error('due_date') is-invalid @enderror" data-target="#due_date_picker" value="{{ old('due_date') }}">
                                @error('due_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Input File Tugas --}}
                        <div class="form-group">
                            <label for="file_assignment">File Tugas (Opsional):</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="file_assignment" id="file_assignment" class="custom-file-input @error('file_assignment') is-invalid @enderror">
                                    <label class="custom-file-label" for="file_assignment">Pilih file</label>
                                </div>
                                @error('file_assignment') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <small class="form-text text-muted">Format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, JPEG, PNG (Max 10MB)</small>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Berikan Tugas</button>
                        <a href="{{ route('guru.assignments-given.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('adminlte/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<script src="{{ asset('adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script>
    $(function () {
        // Inisialisasi datetimepicker
        $('#due_date_picker').datetimepicker({
            format: 'YYYY-MM-DD',
            locale: 'id', // Atur locale jika Anda ingin format tanggal Indonesia
            minDate: moment().startOf('day') // Hanya tanggal hari ini atau setelahnya
        });

        // Inisialisasi custom file input
        bsCustomFileInput.init();

        // Update label file input saat file dipilih
        $('#file_assignment').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName);
        });
    });
</script>
@endpush
