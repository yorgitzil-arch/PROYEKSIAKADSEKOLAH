@extends('layouts.app_admin')

@section('title', 'Edit Berita: ' . $news->title)

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Edit Berita</h1>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <a href="{{ route('admin.news.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Berita
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.news.update', $news->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') {{-- Gunakan metode PUT/PATCH untuk update --}}

                    <div class="form-group">
                        <label for="title">Judul Berita <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $news->title) }}" required>
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

                        @if($news->image_path)
                            <div class="mt-2">
                                <p>Gambar saat ini:</p>
                                <img src="{{ Storage::url($news->image_path) }}" alt="{{ $news->title }}" class="img-thumbnail" style="max-width: 200px;">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="clear_image" id="clear_image" value="1">
                                    <label class="form-check-label" for="clear_image">
                                        Hapus gambar ini
                                    </label>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="short_description">Deskripsi Singkat</label>
                        <textarea name="short_description" id="short_description" class="form-control @error('short_description') is-invalid @enderror" rows="3">{{ old('short_description', $news->short_description) }}</textarea>
                        @error('short_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="content">Konten Berita <span class="text-danger">*</span></label>
                        <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" rows="10" required>{{ old('content', $news->content) }}</textarea>
                        @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="source_url">URL Sumber (Opsional)</label>
                        <input type="url" name="source_url" id="source_url" class="form-control @error('source_url') is-invalid @enderror" value="{{ old('source_url', $news->source_url) }}">
                        @error('source_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Perbarui Berita</button>
                </form>
            </div>
        </div>
    </div>
@endsection
