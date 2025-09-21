@extends('layouts.app_guru')

@section('title', 'Detail Buku Mengajar')
@section('page_title', 'Detail Buku Mengajar')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Buku Mengajar: {{ $teachingMaterial->title ?? 'N/A' }}</h3>
                </div>
                <div class="card-body">
                    @if (!isset($teachingMaterial) || is_null($teachingMaterial->id))
                        <div class="alert alert-danger text-center">
                            Buku mengajar tidak ditemukan atau tidak valid. Silakan kembali ke daftar buku mengajar.
                        </div>
                        <a href="{{ route('guru.teaching-materials.index') }}" class="btn btn-secondary">Kembali ke Daftar Buku Mengajar</a>
                    @else
                        <div class="form-group">
                            <label>Judul:</label>
                            <p>{{ $teachingMaterial->title ?? 'N/A' }}</p>
                        </div>
                        <div class="form-group">
                            <label>Mata Pelajaran:</label>
                            <p>{{ $teachingMaterial->mataPelajaran->nama_mapel ?? 'N/A' }}</p>
                        </div>
                        <div class="form-group">
                            <label>Kelas:</label>
                            <p>{{ $teachingMaterial->kelas->nama_kelas ?? 'N/A' }}</p>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi:</label>
                            <p>{{ $teachingMaterial->description ?? 'N/A' }}</p>
                        </div>
                        <div class="form-group">
                            <label>File:</label>
                            @if($teachingMaterial->file_path)
                                <p><a href="{{ route('guru.teaching-materials.download', $teachingMaterial->id) }}" class="btn btn-sm btn-info">Unduh File</a></p> {{-- <--- PERBAIKAN DI SINI --}}
                            @else
                                <p>Tidak Ada File</p>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Diunggah Oleh:</label>
                            <p>{{ $teachingMaterial->guru->name ?? 'N/A' }}</p>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Unggah:</label>
                            <p>{{ \Carbon\Carbon::parse($teachingMaterial->created_at)->translatedFormat('d F Y H:i') ?? 'N/A' }}</p>
                        </div>

                        <a href="{{ route('guru.teaching-materials.index') }}" class="btn btn-secondary">Kembali</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
