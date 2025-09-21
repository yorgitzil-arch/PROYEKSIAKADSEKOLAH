@extends('layouts.app_siswa')

@section('title', 'Data Diri Siswa')
@section('page_title', 'Data Diri Lengkap')

@section('content')
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Data Diri Siswa: {{ $siswa->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('siswa.data-diri.edit') }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit Data Diri
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            @if($siswa->foto_profile_path)
                                <img src="{{ asset('storage/' . $siswa->foto_profile_path) }}" class="img-fluid img-circle mb-1" style="width: 150px; height: 150px; object-fit: auto;" alt="Foto Profil Siswa">
                            @else
                                <img src="{{ asset('adminlte/dist/img/user2-160x160.jpg') }}" class="img-fluid img-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;" alt="Default Foto Profil">
                            @endif
                            <h3 class="profile-username text-center">{{ $siswa->name }}</h3>
                            <p class="text-muted text-center">{{ $siswa->nis }}</p>
                            <p class="text-muted text-center">Status:
                                @if($siswa->status == 'pending')
                                    <span class="badge badge-warning">Pending Konfirmasi Admin</span>
                                @else
                                    <span class="badge badge-success">Terkonfirmasi</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-8">
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Email</b> <a class="float-right">{{ $siswa->email }}</a>
                                </li>
                                {{-- === KOREKSI PENTING: TAMBAHKAN NISN DI SINI === --}}
                                <li class="list-group-item">
                                    <b>NISN</b> <a class="float-right">{{ $siswa->nisn ?? '-' }}</a>
                                </li>
                                {{-- ================================================= --}}
                                <li class="list-group-item">
                                    <b>Tempat, Tanggal Lahir</b> <a class="float-right">{{ $siswa->tempat_lahir ?? '-' }}, {{ $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('d M Y') : '-' }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Jenis Kelamin</b> <a class="float-right">{{ $siswa->jenis_kelamin ?? '-' }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Agama</b> <a class="float-right">{{ $siswa->agama ?? '-' }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Alamat</b> <a class="float-right">{{ $siswa->alamat ?? '-' }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Nomor Telepon</b> <a class="float-right">{{ $siswa->nomor_telepon ?? '-' }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Kelas</b> <a class="float-right">{{ $siswa->kelas->nama_kelas ?? '-' }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Jurusan</b> <a class="float-right">{{ $siswa->jurusan->nama_jurusan ?? '-' }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Wali Kelas</b> <a class="float-right">{{ $siswa->waliKelas->name ?? '-' }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <h4 class="mt-4">Data Orang Tua</h4>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Nama Ayah</b> <a class="float-right">{{ $siswa->nama_ayah ?? '-' }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Pekerjaan Ayah</b> <a class="float-right">{{ $siswa->pekerjaan_ayah ?? '-' }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Nama Ibu</b> <a class="float-right">{{ $siswa->nama_ibu ?? '-' }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Pekerjaan Ibu</b> <a class="float-right">{{ $siswa->pekerjaan_ibu ?? '-' }}</a>
                        </li>
                    </ul>

                    <h4 class="mt-4">Dokumen Pendukung</h4>
                    <ul class="list-group list-group-unbordered mb-3">
                        @php
                            $documents = [
                                'ijazah' => 'Ijazah',
                                'raport' => 'Raport',
                                'kk' => 'Kartu Keluarga (KK)',
                                'ktp_ortu' => 'KTP Orang Tua',
                                'akta_lahir' => 'Akta Lahir',
                                'sk_lulus' => 'Surat Keterangan Lulus (SKL)',
                                'kis' => 'Kartu Indonesia Sehat (KIS)',
                                'kks' => 'Kartu Keluarga Sejahtera (KKS) / Bantuan Sosial',
                            ];
                        @endphp
                        @foreach($documents as $type => $label)
                            <li class="list-group-item">
                                <b>{{ $label }}</b>
                                @php $pathColumn = $type . '_path'; @endphp
                                @if($siswa->$pathColumn)
                                    <a href="{{ asset('storage/' . $siswa->$pathColumn) }}" target="_blank" class="float-right btn btn-sm btn-primary"><i class="fas fa-eye"></i> Lihat</a>
                                @else
                                    <span class="float-right text-muted">Belum diunggah</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
