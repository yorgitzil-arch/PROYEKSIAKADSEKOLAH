@extends('layouts.app_admin')

@section('title', 'Tambah Berita Baru')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Tambah Berita Baru</h1>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <a href="{{ route('admin.news.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Berita
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="title">Judul Berita <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="image">Gambar Berita</label>
                        <input type="file" name="image" id="image" class="form-control-file @error('image') is-invalid @enderror">
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="short_description">Deskripsi Singkat</label>
                        <textarea name="short_description" id="short_description" class="form-control @error('short_description') is-invalid @enderror" rows="3">{{ old('short_description') }}</textarea>
                        @error('short_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="content">Konten Berita <span class="text-danger">*</span></label>
                        <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" rows="10" required>{{ old('content') }}</textarea>
                        @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="source_url">URL Sumber (Opsional)</label>
                        <input type="url" name="source_url" id="source_url" class="form-control @error('source_url') is-invalid @enderror" value="{{ old('source_url') }}">
                        @error('source_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Berita</button>
                </form>
            </div>
        </div>
    </div>
@endsection
