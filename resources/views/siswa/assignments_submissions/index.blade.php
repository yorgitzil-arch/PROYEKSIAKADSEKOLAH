@extends('layouts.app_siswa')

@section('title', 'Tugas Saya')
@section('page_title', 'Tugas Saya')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Tugas untuk Kelas Anda ({{ $siswa->kelas->nama_kelas ?? 'N/A' }}{{ $siswa->kelas->jurusan->nama_jurusan ?? '' ? ' - ' . $siswa->kelas->jurusan->nama_jurusan : '' }})</h3>
                </div>
                <div class="card-body">
                    {{-- Pesan Sukses/Error --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
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

                    @if($assignmentsGiven->isEmpty())
                        <div class="alert alert-info text-center">
                            Tidak ada tugas yang diberikan untuk Kelas Anda saat ini.
                        </div>
                    @else
                        <table id="myAssignmentsTable" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>Judul Tugas</th>
                                <th>Mata Pelajaran</th>
                                <th>Guru</th>
                                <th>Batas Waktu</th>
                                <th>Status Pengumpulan</th>
                                <th>Nilai</th>
                                <th style="width: 150px;">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($assignmentsGiven as $assignment)
                                @php
                                    // Cek apakah siswa sudah mengumpulkan tugas ini
                                    // Karena di controller sudah eager load submissions khusus siswa ini,
                                    // kita bisa langsung ambil dari relasi
                                    $submission = $assignment->submissions->where('siswa_id', $siswa->id)->first();
                                    $hasSubmitted = !is_null($submission);
                                    $isOverdue = $assignment->due_date && \Carbon\Carbon::now()->greaterThan($assignment->due_date);
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $assignment->title }}</td>
                                    <td>{{ $assignment->mataPelajaran->nama_mapel ?? 'N/A' }}</td>
                                    <td>{{ $assignment->guru->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($assignment->due_date)
                                            {{ \Carbon\Carbon::parse($assignment->due_date)->format('d M Y H:i') }}
                                            @if($isOverdue && !$hasSubmitted)
                                                <span class="badge badge-danger ml-1">Lewat Batas</span>
                                            @elseif($isOverdue && $hasSubmitted)
                                                <span class="badge badge-warning ml-1">Terlambat Dikumpulkan</span>
                                            @else
                                                <span class="badge badge-success ml-1">Aktif</span>
                                            @endif
                                        @else
                                            Tidak Ada Batas
                                        @endif
                                    </td>
                                    <td>
                                        @if($hasSubmitted)
                                            <span class="badge badge-success">Sudah Dikumpulkan</span>
                                        @else
                                            <span class="badge badge-secondary">Belum Dikumpulkan</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($hasSubmitted && is_numeric($submission->score))
                                            <span class="badge badge-{{ $submission->score >= 75 ? 'primary' : 'warning' }}">{{ $submission->score }}</span>
                                        @elseif($hasSubmitted && !is_numeric($submission->score))
                                            <span class="badge badge-info">Menunggu Penilaian</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($assignment->file_path)
                                            <a href="{{ route('guru.assignments-given.download-file', $assignment->id) }}" class="btn btn-sm btn-outline-info" title="Unduh File Tugas">
                                                <i class="fas fa-download"></i> Tugas
                                            </a>
                                        @endif

                                        @if($hasSubmitted)
                                            <a href="{{ route('siswa.assignments-submissions.show', $submission->id) }}" class="btn btn-sm btn-primary ml-1" title="Lihat Detail Pengumpulan">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                        @else
                                            <a href="{{ route('siswa.assignments-submissions.create', $assignment->id) }}" class="btn btn-sm btn-success ml-1 {{ $isOverdue ? 'disabled' : '' }}" title="{{ $isOverdue ? 'Batas waktu sudah lewat' : 'Kumpulkan Tugas' }}">
                                                <i class="fas fa-upload"></i> Kumpul
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3">
                            {{ $assignmentsGiven->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- DataTables script (optional, but good for tables) --}}
    {{-- <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script> --}}
    {{-- <script>
        $(function () {
            $('#myAssignmentsTable').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script> --}}
@endpush
