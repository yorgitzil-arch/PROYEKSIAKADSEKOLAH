@extends('layouts.app_guru')

@section('title', 'Buat Pengumuman Siswa')
@section('page_title', 'Buat Pengumuman Siswa')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Buat Pengumuman Baru untuk Siswa</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('guru.student-announcements.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="title">Judul Pengumuman <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                            @error('title')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="kelas_id">Ditujukan untuk Kelas</label>
                            <select name="kelas_id" id="kelas_id" class="form-control @error('kelas_id') is-invalid @enderror">
                                @foreach($kelasOptions as $id => $name)
                                    <option value="{{ $id }}" {{ old('kelas_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('kelas_id')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <small class="form-text text-muted">Pilih "Semua Kelas" jika pengumuman ini bersifat umum untuk semua siswa di kelas yang Anda ajar.</small>
                        </div>

                        <div class="form-group">
                            <label for="message">Isi Pengumuman <span class="text-danger">*</span></label>
                            <textarea name="message" id="message" class="form-control @error('message') is-invalid @enderror" rows="6" required>{{ old('message') }}</textarea>
                            @error('message')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Buat Pengumuman</button>
                        <a href="{{ route('guru.student-announcements.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
