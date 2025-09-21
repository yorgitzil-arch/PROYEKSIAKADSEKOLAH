@extends('layouts.app_admin')

@section('title', 'Detail Siswa')
@section('page_title', 'Detail Data Siswa')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<style>
    /* Custom styles for a minimalist and clean look */
    :root {
        --primary-color: #007bff;
        --secondary-color: #6c757d;
        --success-color: #28a745;
        --warning-color: #ffc107;
        --info-color: #17a2b8;
        --light-bg: #f8f9fa;
        --dark-text: #343a40;
        --border-color: #e9ecef;
    }

    body {
        font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        background-color: #f4f6f9;
    }

    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: box-shadow 0.3s ease;
    }

    .card-header {
        border-bottom: 1px solid var(--border-color);
        padding: 1.25rem 2rem;
        background-color: var(--light-bg);
        border-radius: 12px 12px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .card-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--dark-text);
        margin: 0;
    }

    .card-tools .btn {
        border-radius: 8px;
        padding: 0.65rem 1.25rem;
        font-weight: 600;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.9rem;
    }

    .card-body {
        padding: 2rem;
    }

    .profile-user-img-container {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        overflow: hidden;
        margin: 0 auto 15px auto;
        border: 3px solid #ced4da;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        display: block;
    }

    .profile-user-img {
        object-fit: cover;
        width: 100%;
        height: 100%;
        display: block;
    }

    .list-group-item {
        border: 1px solid var(--border-color);
        border-radius: 8px;
        margin-bottom: 0.5rem;
        padding: 0.75rem 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .list-group-item b {
        color: var(--dark-text);
        display: flex;
        align-items: center;
    }

    .list-group-item i {
        color: var(--info-color);
        width: 25px;
        text-align: center;
    }

    .badge {
        font-size: 85%;
        padding: 0.4em 0.6em;
        border-radius: 8px;
        font-weight: 600;
    }

    h4 {
        font-weight: 600;
        color: var(--dark-text);
        border-bottom: 2px solid var(--primary-color);
        padding-bottom: 0.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
    }

    hr {
        border-top: 1px solid var(--border-color);
        margin-top: 2rem;
        margin-bottom: 2rem;
    }

    .text-info {
        color: var(--info-color) !important;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-10 offset-md-1">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-1"></i> Detail Siswa: <span class="font-weight-bold">{{ $siswa->name }}</span>
                </h3>
                <div class="card-tools">
                    <a href="{{ route('admin.student-data.edit', $siswa->id) }}" class="btn btn-warning btn-sm mr-1" data-toggle="tooltip" title="Edit Data Siswa">
                        <i class="fas fa-edit"></i> Edit Data
                    </a>
                    <a href="{{ route('admin.student-data.index') }}" class="btn btn-secondary btn-sm" data-toggle="tooltip" title="Kembali ke Daftar Siswa">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center border-right">
                        <div class="profile-user-img-container mb-3">
                            @if($siswa->foto_profile_path)
                                <img class="profile-user-img img-fluid img-circle" src="{{ asset('storage/' . $siswa->foto_profile_path) }}" alt="Foto Profil Siswa" style="object-fit: cover;">
                            @else
                                <img class="profile-user-img img-fluid img-circle" src="{{ asset('adminlte/dist/img/default-avatar.png') }}" alt="Default Foto Profil" style="object-fit: cover;">
                            @endif
                        </div>
                        <h3 class="profile-username text-center mb-0">{{ $siswa->name }}</h3>
                        <p class="text-muted text-center">{{ $siswa->nis }}</p>
                        <p class="text-muted text-center mt-2">Status:
                            @if($siswa->status == 'pending')
                                <span class="badge badge-warning"><i class="fas fa-hourglass-half mr-1"></i> Pending</span>
                            @else
                                <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i> Dikonfirmasi</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-8">
                        <h4 class="mb-3"><i class="fas fa-user-circle mr-2 text-info"></i>Informasi Pribadi</h4>
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b><i class="fas fa-at mr-2"></i>Email</b> <a class="float-right">{{ $siswa->email ?? '-' }}</a>
                            </li>
                            <li class="list-group-item">
                                <b><i class="fas fa-id-badge mr-2"></i>NISN</b> <a class="float-right">{{ $siswa->nisn ?? '-' }}</a>
                            </li>
                            <li class="list-group-item">
                                <b><i class="fas fa-birthday-cake mr-2"></i>Tempat, Tanggal Lahir</b> <a class="float-right">{{ ($siswa->tempat_lahir && $siswa->tanggal_lahir) ? $siswa->tempat_lahir . ', ' . $siswa->tanggal_lahir->format('d M Y') : '-' }}</a>
                            </li>
                            <li class="list-group-item">
                                <b><i class="fas fa-venus-mars mr-2"></i>Jenis Kelamin</b> <a class="float-right">{{ $siswa->jenis_kelamin ?? '-' }}</a>
                            </li>
                            <li class="list-group-item">
                                <b><i class="fas fa-hands-praying mr-2"></i>Agama</b> <a class="float-right">{{ $siswa->agama ?? '-' }}</a>
                            </li>
                            <li class="list-group-item">
                                <b><i class="fas fa-map-marker-alt mr-2"></i>Alamat</b> <a class="float-right">{{ $siswa->alamat ?? '-' }}</a>
                            </li>
                            <li class="list-group-item">
                                <b><i class="fas fa-phone mr-2"></i>Nomor Telepon</b> <a class="float-right">{{ $siswa->nomor_telepon ?? '-' }}</a>
                            </li>
                            <li class="list-group-item">
                                <b><i class="fas fa-school mr-2"></i>Kelas</b> <a class="float-right badge badge-info">{{ $siswa->kelas->nama_kelas ?? '-' }}</a>
                            </li>
                            <li class="list-group-item">
                                <b><i class="fas fa-pencil-ruler mr-2"></i>Jurusan</b> <a class="float-right badge badge-secondary">{{ $siswa->jurusan->nama_jurusan ?? '-' }}</a>
                            </li>
                            <li class="list-group-item">
                                <b><i class="fas fa-chalkboard-teacher mr-2"></i>Wali Kelas</b> <a class="float-right">{{ $siswa->waliKelas->name ?? '-' }}</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <hr>

                <h4 class="mt-4 mb-3"><i class="fas fa-users mr-2 text-info"></i>Data Orang Tua</h4>
                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b><i class="fas fa-user-tie mr-2"></i>Nama Ayah</b> <a class="float-right">{{ $siswa->nama_ayah ?? '-' }}</a>
                    </li>
                    <li class="list-group-item">
                        <b><i class="fas fa-briefcase mr-2"></i>Pekerjaan Ayah</b> <a class="float-right">{{ $siswa->pekerjaan_ayah ?? '-' }}</a>
                    </li>
                    <li class="list-group-item">
                        <b><i class="fas fa-user-alt mr-2"></i>Nama Ibu</b> <a class="float-right">{{ $siswa->nama_ibu ?? '-' }}</a>
                    </li>
                    <li class="list-group-item">
                        <b><i class="fas fa-briefcase mr-2"></i>Pekerjaan Ibu</b> <a class="float-right">{{ $siswa->pekerjaan_ibu ?? '-' }}</a>
                    </li>
                </ul>

                <hr>

                <h4 class="mt-4 mb-3"><i class="fas fa-file-alt mr-2 text-info"></i>Dokumen Pendukung</h4>
                <ul class="list-group list-group-unbordered mb-3">
                    @php
                        $documents = [
                            'ijazah' => ['label' => 'Ijazah', 'icon' => 'file-alt'],
                            'raport' => ['label' => 'Raport', 'icon' => 'file-signature'],
                            'kk' => ['label' => 'Kartu Keluarga (KK)', 'icon' => 'id-card'],
                            'ktp_ortu' => ['label' => 'KTP Orang Tua', 'icon' => 'id-card-alt'],
                            'akta_lahir' => ['label' => 'Akta Lahir', 'icon' => 'book-open'],
                            'sk_lulus' => ['label' => 'Surat Keterangan Lulus (SKL)', 'icon' => 'graduation-cap'],
                            'kis' => ['label' => 'Kartu Indonesia Sehat (KIS)', 'icon' => 'heartbeat'],
                            'kks' => ['label' => 'Kartu Keluarga Sejahtera (KKS) / Bantuan Sosial', 'icon' => 'hand-holding-usd'],
                        ];
                    @endphp
                    @foreach($documents as $type => $data)
                        <li class="list-group-item">
                            <b><i class="fas fa-{{ $data['icon'] }} mr-2"></i>{{ $data['label'] }}</b>
                            @php $pathColumn = $type . '_path'; @endphp
                            @if($siswa->$pathColumn)
                                <a href="{{ route('admin.student-data.download-document', ['siswa' => $siswa->id, 'documentType' => $type]) }}" class="float-right btn btn-sm btn-primary">
                                    <i class="fas fa-download mr-1"></i> Download
                                </a>
                            @else
                                <span class="float-right text-muted"><i class="fas fa-times-circle mr-1"></i> Belum diunggah</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endpush
