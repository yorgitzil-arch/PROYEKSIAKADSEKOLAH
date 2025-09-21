@extends('layouts.app_admin')

@section('title', 'Buat Pengumuman Sekolah')
@section('page_title', 'Buat Pengumuman Sekolah')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Buat Pengumuman Baru untuk Siswa</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.admin-student-announcements.store') }}" method="POST">
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
                            <label for="message">Pesan Pengumuman <span class="text-danger">*</span></label>
                            <textarea name="message" id="message" class="form-control @error('message') is-invalid @enderror" rows="7" required>{{ old('message') }}</textarea>
                            @error('message')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Buat Pengumuman</button>
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
