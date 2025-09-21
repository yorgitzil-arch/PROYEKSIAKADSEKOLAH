@extends('layouts.app_admin')

@section('title', 'Edit Pengumuman Sekolah')
@section('page_title', 'Edit Pengumuman Sekolah')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Edit Pengumuman: {{ $adminStudentAnnouncement->title }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.admin-student-announcements.update', $adminStudentAnnouncement->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- Penting untuk method PUT --}}

                        <div class="form-group">
                            <label for="title">Judul Pengumuman <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $adminStudentAnnouncement->title) }}" required>
                            @error('title')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="message">Pesan Pengumuman <span class="text-danger">*</span></label>
                            <textarea name="message" id="message" class="form-control @error('message') is-invalid @enderror" rows="7" required>{{ old('message', $adminStudentAnnouncement->message) }}</textarea>
                            @error('message')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Perbarui Pengumuman</button>
                        <a href="{{ route('admin.admin-student-announcements.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Script tambahan jika diperlukan --}}
@endpush
