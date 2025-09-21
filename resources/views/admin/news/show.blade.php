@extends('layouts.app_admin')

@section('title', 'Detail Berita: ' . $news->title)

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Detail Berita</h1>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <a href="{{ route('admin.news.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Berita
                </a>
                <a href="{{ route('admin.news.edit', $news->id) }}" class="btn btn-warning btn-sm float-right">
                    <i class="fas fa-edit"></i> Edit Berita
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h3>{{ $news->title }}</h3>
                        <p class="text-muted">Diterbitkan pada: {{ $news->created_at->format('d F Y H:i') }}</p>
                        <hr>

                        @if($news->image_path)
                            <div class="mb-3 text-center">
                                <img src="{{ Storage::url($news->image_path) }}" alt="{{ $news->title }}" class="img-fluid rounded" style="max-width: 500px;">
                            </div>
                        @else
                            <p>Tidak ada gambar terkait.</p>
                        @endif

                        <h4>Deskripsi Singkat</h4>
                        <p>{{ $news->short_description ?? 'Tidak ada deskripsi singkat.' }}</p>

                        <h4>Konten Berita</h4>
                        <div class="card card-body bg-light">
                            {!! nl2br(e($news->content)) !!} {{-- Menggunakan nl2br dan e() untuk menampilkan teks dengan baris baru --}}
                        </div>

                        @if($news->source_url)
                            <h4 class="mt-3">URL Sumber</h4>
                            <p><a href="{{ $news->source_url }}" target="_blank">{{ $news->source_url }}</a></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
