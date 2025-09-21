
@extends('layouts.app_admin')

@section('title', 'Detail Pengumuman Sekolah')
@section('page_title', 'Detail Pengumuman Sekolah')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Pengumuman: {{ $adminStudentAnnouncement->title }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.admin-student-announcements.edit', $adminStudentAnnouncement->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.admin-student-announcements.destroy', $adminStudentAnnouncement->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Judul Pengumuman:</dt>
                        <dd class="col-sm-9">{{ $adminStudentAnnouncement->title }}</dd>

                        <dt class="col-sm-3">Dibuat Oleh:</dt>
                        <dd class="col-sm-9">{{ $adminStudentAnnouncement->admin->name ?? 'Admin Tidak Dikenal' }}</dd>

                        <dt class="col-sm-3">Tanggal Dibuat:</dt>
                        <dd class="col-sm-9">{{ \Carbon\Carbon::parse($adminStudentAnnouncement->created_at)->format('d F Y H:i') }}</dd>

                        <dt class="col-sm-3">Pesan:</dt>
                        <dd class="col-sm-9">{{ $adminStudentAnnouncement->message }}</dd>
                    </dl>

                    <div class="mt-4">
                        <a href="{{ route('admin.admin-student-announcements.index') }}" class="btn btn-secondary">Kembali ke Daftar Pengumuman</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Script tambahan jika diperlukan --}}
@endpush
