@extends('layouts.app_admin')

@section('title', 'Profil Sekolah')
@section('page_title', 'Manajemen Profil Sekolah')

@section('content')
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Form Profil Sekolah</h3>
                </div>
                {{-- Form ini akan digunakan untuk membuat atau mengedit profil --}}
                <form action="{{ route('admin.school-profile.store-update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if (session('info'))
                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                {{ session('info') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <h5><i class="icon fas fa-ban"></i> Terjadi Kesalahan!</h5>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="name">Nama Sekolah</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $schoolProfile->name) }}">
                            @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="history">Sejarah Sekolah</label>
                            <textarea name="history" id="history" class="form-control @error('history') is-invalid @enderror" rows="5">{{ old('history', $schoolProfile->history) }}</textarea>
                            @error('history')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="vision">Visi Sekolah</label>
                            <textarea name="vision" id="vision" class="form-control @error('vision') is-invalid @enderror" rows="3">{{ old('vision', $schoolProfile->vision) }}</textarea>
                            @error('vision')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="mission">Misi Sekolah</label>
                            <textarea name="mission" id="mission" class="form-control @error('mission') is-invalid @enderror" rows="3">{{ old('mission', $schoolProfile->mission) }}</textarea>
                            @error('mission')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="address">Alamat Sekolah</label>
                            <input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', $schoolProfile->address) }}">
                            @error('address')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="phone">Telepon</label>
                            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $schoolProfile->phone) }}">
                            @error('phone')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email Kontak</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $schoolProfile->email) }}">
                            @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="website">Website</label>
                            <input type="url" name="website" id="website" class="form-control @error('website') is-invalid @enderror" value="{{ old('website', $schoolProfile->website) }}">
                            @error('website')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                       <div class="form-group">
    <label for="logo">Logo Sekolah</label>
    <div class="input-group">
        <div class="custom-file">
            <input type="file" name="logo" id="logo" class="custom-file-input @error('logo') is-invalid @enderror">
            <label class="custom-file-label" for="logo">Pilih file</label>
        </div>
    </div>
    @error('logo')
        <span class="text-danger text-sm">{{ $message }}</span>
    @enderror
    @if ($schoolProfile->logo_path)
        <div class="mt-2 d-flex align-items-center">
            <p class="mb-0 mr-2">Logo saat ini:</p>
            <img src="{{ asset('storage/' . $schoolProfile->logo_path) }}" alt="Logo Sekolah" style="max-width: 150px; height: auto;">
            {{-- Tombol Hapus Logo sekarang berada di dalam form-nya sendiri --}}
            <form action="{{ route('admin.school-profile.deleteLogo') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus logo ini?');" class="ml-2">
                @csrf
                <button type="submit" class="btn btn-sm btn-danger">Hapus Logo</button>
            </form>
        </div>
    @endif
</div>

{{-- --- PENAMBAHAN UNTUK BANNER SEKOLAH --- --}}
<div class="form-group">
    <label for="banner">Banner Sekolah</label>
    <div class="input-group">
        <div class="custom-file">
            <input type="file" name="banner" id="banner" class="custom-file-input @error('banner') is-invalid @enderror">
            <label class="custom-file-label" for="banner">Pilih file</label>
        </div>
    </div>
    @error('banner')
        <span class="text-danger text-sm">{{ $message }}</span>
    @enderror
    @if ($schoolProfile->banner_path)
        <div class="mt-2 d-flex align-items-center">
            <p class="mb-0 mr-2">Banner saat ini:</p>
            <img src="{{ asset('storage/' . $schoolProfile->banner_path) }}" alt="Banner Sekolah" style="max-width: 300px; height: auto;">
            {{-- Tombol Hapus Banner sekarang berada di dalam form-nya sendiri --}}
            <form action="{{ route('admin.school-profile.deleteBanner') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus banner ini?');" class="ml-2">
                @csrf
                <button type="submit" class="btn btn-sm btn-danger">Hapus Banner</button>
            </form>
        </div>
    @endif
</div>
{{-- --- AKHIR PENAMBAHAN UNTUK BANNER SEKOLAH --- --}}

</div>
<div class="card-footer">
    <button type="submit" class="btn btn-primary">Simpan Profil</button>
    @if ($schoolProfile->exists)
        {{-- Tombol Reset sekarang berada di dalam form-nya sendiri --}}
        <form action="{{ route('admin.school-profile.resetProfile') }}" method="POST" onsubmit="return confirm('PERINGATAN! Ini akan menghapus semua data profil sekolah, logo, dan banner. Apakah Anda yakin ingin mereset profil?');" class="d-inline-block ml-2">
            @csrf
            <button type="submit" class="btn btn-warning">Reset Profil</button>
        </form>
    @endif
</div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Script untuk menampilkan nama file yang dipilih pada input logo
            const logoInput = document.getElementById('logo');
            if (logoInput) {
                logoInput.addEventListener('change', function(e) {
                    const fileName = e.target.files[0] ? e.target.files[0].name : 'Pilih file';
                    const nextSibling = e.target.nextElementSibling;
                    if (nextSibling) {
                        nextSibling.innerText = fileName;
                    }
                });
            }

            // Script baru untuk menampilkan nama file yang dipilih pada input banner
            const bannerInput = document.getElementById('banner');
            if (bannerInput) {
                bannerInput.addEventListener('change', function(e) {
                    const fileName = e.target.files[0] ? e.target.files[0].name : 'Pilih file';
                    const nextSibling = e.target.nextElementSibling;
                    if (nextSibling) {
                        nextSibling.innerText = fileName;
                    }
                });
            }
        });

        function confirmDeleteLogo() {
            if (confirm('Apakah Anda yakin ingin menghapus logo ini?')) {
                document.getElementById('delete-logo-form').submit();
            }
        }

        function confirmDeleteBanner() {
            if (confirm('Apakah Anda yakin ingin menghapus banner ini?')) {
                document.getElementById('delete-banner-form').submit();
            }
        }

        function confirmResetProfile() {
            if (confirm('PERINGATAN! Ini akan menghapus semua data profil sekolah, logo, dan banner. Apakah Anda yakin ingin mereset profil?')) {
                document.getElementById('reset-profile-form').submit();
            }
        }
    </script>
@endpush