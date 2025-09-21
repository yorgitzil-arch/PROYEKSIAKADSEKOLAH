@extends('layouts.app') {{-- Menggunakan layout app dari Argon Dashboard --}}

@section('content')
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Presensi</h6>
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="{{ route('public.home') }}"><i class="fas fa-home"></i></a></li> 
                            {{-- Link kembali ke daftar penugasan --}}
                            <li class="breadcrumb-item"><a href="{{ route('guru.assignments.index') }}">Daftar Mengajar</a></li>
                            {{-- Link kembali ke jadwal presensi spesifik assignment --}}
                            <li class="breadcrumb-item"><a href="{{ route('guru.assignments.lesson_schedules.index', $assignment->id) }}">Jadwal Presensi</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Isi Presensi</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">Isi Presensi untuk {{ $assignment->mataPelajaran->nama_mapel ?? 'N/A' }} - Kelas {{ $assignment->kelas->nama_kelas ?? 'N/A' }} pada Tanggal {{ $lessonSchedule->date->translatedFormat('d F Y') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Terjadi kesalahan!</strong> Mohon periksa input Anda.
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
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

                    <form action="{{ route('guru.assignments.lesson_schedules.store_attendance', ['assignment' => $assignment->id, 'lessonSchedule' => $lessonSchedule->id]) }}" method="POST">
                        @csrf

                        @if($students->isEmpty())
                            <div class="alert alert-info text-center">
                                Tidak ada siswa di kelas ini.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table align-items-center table-flush">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">No.</th>
                                            <th scope="col">NIS</th>
                                            <th scope="col">Nama Siswa</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Keterangan (Opsional)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($students as $index => $student)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $student->nis }}</td>
                                                <td>{{ $student->name }}</td>
                                                <td>
                                                    <div class="form-group mb-0">
                                                        <select name="attendance[{{ $student->id }}][status]" class="form-control form-control-alternative" required>
                                                            <option value="hadir" {{ old('attendance.' . $student->id . '.status', $attendanceRecords[$student->id]->status ?? '') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                                            <option value="sakit" {{ old('attendance.' . $student->id . '.status', $attendanceRecords[$student->id]->status ?? '') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                                            <option value="izin" {{ old('attendance.' . $student->id . '.status', $attendanceRecords[$student->id]->status ?? '') == 'izin' ? 'selected' : '' }}>Izin</option>
                                                            <option value="alpha" {{ old('attendance.' . $student->id . '.status', $attendanceRecords[$student->id]->status ?? '') == 'alpha' ? 'selected' : '' }}>Alpha</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group mb-0">
                                                        <input type="text" name="attendance[{{ $student->id }}][keterangan]" class="form-control form-control-alternative" value="{{ old('attendance.' . $student->id . '.keterangan', $attendanceRecords[$student->id]->keterangan ?? '') }}" placeholder="Misal: Demam, Izin Keluarga">
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <button type="submit" class="btn btn-success mt-4">Simpan Presensi</button>
                        @endif
                        <a href="{{ route('guru.assignments.lesson_schedules.index', $assignment->id) }}" class="btn btn-secondary mt-4">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection