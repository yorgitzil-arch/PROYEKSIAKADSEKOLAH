@extends('layouts.app_siswa')

@section('title', 'Riwayat Presensi Saya')
@section('page_title', 'Riwayat Presensi Saya')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Riwayat Presensi Saya ({{ $siswa->name ?? 'Siswa' }})</h3>
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

                    {{-- Cek apakah ada tanggal presensi yang tersedia secara keseluruhan --}}
                    @if($allDates->isEmpty())
                        <div class="alert alert-info text-center">
                            Belum ada riwayat presensi yang tersedia untuk Anda.
                        </div>
                    @else
                        {{-- Iterasi berdasarkan finalAttendanceData yang sudah dikelompokkan per mapel --}}
                        @foreach($finalAttendanceData as $subjectName => $data)
                            <div class="card card-outline card-primary collapsed-card">
                                <div class="card-header">
                                    <h3 class="card-title">{{ $subjectName }}</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                        <tr>
                                            <th rowspan="2" class="align-middle">No.</th>
                                            <th rowspan="2" class="align-middle">Tanggal</th>
                                            <th colspan="2" class="text-center">Status & Keterangan</th>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <th>Keterangan</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php $rowNum = 1; @endphp
                                        @foreach($allDates as $dateString)
                                            <tr>
                                                <td>{{ $rowNum++ }}</td>
                                                <td>{{ \Carbon\Carbon::parse($dateString)->translatedFormat('d F Y') }}</td>
                                                <td>
                                                    @php
                                                        // Ambil status dan keterangan untuk tanggal ini
                                                        $status = $data['dates'][$dateString]['status'] ?? 'Alpha'; // Default ke 'Alpha' jika tidak ada
                                                        $keterangan = $data['dates'][$dateString]['keterangan'] ?? '-';
                                                    @endphp
                                                    {{-- KOREKSI: Gunakan strtolower() untuk perbandingan case-insensitive --}}
                                                    @if(strtolower($status) == 'hadir')
                                                        <span class="badge badge-success">Hadir</span>
                                                    @elseif(strtolower($status) == 'sakit')
                                                        <span class="badge badge-warning">Sakit</span>
                                                    @elseif(strtolower($status) == 'izin')
                                                        <span class="badge badge-info">Izin</span>
                                                    @else
                                                        <span class="badge badge-danger">Alpha</span>
                                                    @endif
                                                </td>
                                                <td>{{ $keterangan }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('siswa.jadwal-pelajaran.index') }}" class="btn btn-secondary">Kembali ke Jadwal Pelajaran</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
