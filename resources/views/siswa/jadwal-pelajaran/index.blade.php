@extends('layouts.app_siswa')

@section('title', 'Jadwal Pelajaran')
@section('page_title', 'Jadwal Pelajaran')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Jadwal Pelajaran Kelas {{ $siswa->kelas->nama_kelas ?? 'N/A' }}{{ $siswa->kelas->jurusan->nama_jurusan ?? '' ? ' - ' . $siswa->kelas->jurusan->nama_jurusan : '' }}</h3>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if(session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            {{ session('info') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if($jadwalPelajaran->isEmpty())
                        <div class="alert alert-info text-center">
                            Belum ada jadwal pelajaran yang tersedia untuk Kelas Anda.
                        </div>
                    @else
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>Mata Pelajaran</th>
                                <th>Guru</th>
                                <th>Tipe Mengajar</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($jadwalPelajaran as $index => $jadwal)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $jadwal->mataPelajaran->nama_mapel ?? 'N/A' }}</td>
                                    <td>{{ $jadwal->guru->name ?? 'N/A' }}</td>
                                    <td>{{ $jadwal->tipe_mengajar ?? 'N/A' }}</td>
                                    <td>
                                        @php
                                            $teachingMaterial = \App\Models\TeachingMaterial::where('mata_pelajaran_id', $jadwal->mata_pelajaran_id)
                                                                ->where('guru_id', $jadwal->guru_id)
                                                                ->first();
                                        @endphp
                                        @if($teachingMaterial && $teachingMaterial->file_path)
                                            <a href="{{ route('siswa.jadwal-pelajaran.download-material', $teachingMaterial->id) }}" class="btn btn-sm btn-info">Materi Ajar</a>
                                        @else
                                            <button class="btn btn-sm btn-secondary" disabled>Materi Ajar (Tidak Ada)</button>
                                        @endif
                                        <a href="{{ route('siswa.jadwal-pelajaran.attendance-history') }}" class="btn btn-sm btn-success">Lihat Presensi</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3">
                            {{ $jadwalPelajaran->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
