@extends('layouts.app_guru')

@section('title', 'Presensi Harian')
@section('page_title', 'Presensi Harian')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Presensi Harian untuk {{ $assignment->mataPelajaran->nama_mapel ?? 'N/A' }} - Kelas {{ $assignment->kelas->nama_kelas ?? 'N/A' }} ({{ \Carbon\Carbon::today()->translatedFormat('d F Y') }})</h3>
                    <div class="card-tools">
                        <a href="{{ route('guru.assignments.attendance.form', ['assignment' => $assignment->id, 'date' => \Carbon\Carbon::today()->format('Y-m-d')]) }}" class="btn btn-primary btn-sm">Isi / Edit Presensi Hari Ini</a>
                        <a href="{{ route('guru.assignments.attendance.history', $assignment->id) }}" class="btn btn-secondary btn-sm">Riwayat Presensi</a>
                    </div>
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

                    @if($students->isEmpty())
                        <div class="alert alert-info text-center">
                            Tidak ada siswa di kelas ini.
                        </div>
                    @else
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Status Presensi Hari Ini</th>
                                <th>Keterangan</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($students as $index => $student)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $student->nis }}</td>
                                    <td>{{ $student->name }}</td>
                                    <td>
                                        @php
                                            $status = $attendanceRecords[$student->id]->status ?? 'alpha';
                                        @endphp
                                        @if($status == 'hadir')
                                            <span class="badge badge-success">Hadir</span>
                                        @elseif($status == 'sakit')
                                            <span class="badge badge-warning">Sakit</span>
                                        @elseif($status == 'izin')
                                            <span class="badge badge-info">Izin</span>
                                        @else
                                            <span class="badge badge-danger">Alpha</span>
                                        @endif
                                    </td>
                                    <td>{{ $attendanceRecords[$student->id]->keterangan ?? '-' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
