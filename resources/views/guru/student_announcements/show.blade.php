@extends('layouts.app_guru')

@section('title', 'Detail Pengumuman Siswa')
@section('page_title', 'Detail Pengumuman Siswa')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Pengumuman: {{ $studentAnnouncement->title }}</h3>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Judul Pengumuman:</dt>
                        <dd class="col-sm-9">{{ $studentAnnouncement->title }}</dd>

                        <dt class="col-sm-3">Dibuat Oleh Guru:</dt>
                        <dd class="col-sm-9">{{ $studentAnnouncement->guru->name ?? 'N/A' }}</dd>

                        <dt class="col-sm-3">Ditujukan untuk Kelas:</dt>
                        <dd class="col-sm-9">{{ $studentAnnouncement->kelas->nama_kelas ?? 'Semua Kelas' }}</dd>

                        <dt class="col-sm-3">Isi Pengumuman:</dt>
                        <dd class="col-sm-9">{{ $studentAnnouncement->message }}</dd>

                        <dt class="col-sm-3">Tanggal Dibuat:</dt>
                        <dd class="col-sm-9">{{ \Carbon\Carbon::parse($studentAnnouncement->created_at)->format('d F Y H:i') }}</dd>
                    </dl>

                    <div class="mt-4">
                        <a href="{{ route('guru.student-announcements.index') }}" class="btn btn-secondary">Kembali ke Daftar Pengumuman</a>
                        <a href="{{ route('guru.student-announcements.edit', $studentAnnouncement->id) }}" class="btn btn-warning">Edit Pengumuman</a>
                        <form action="{{ route('guru.student-announcements.destroy', $studentAnnouncement->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')">Hapus Pengumuman</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
