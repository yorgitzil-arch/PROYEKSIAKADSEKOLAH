@extends('layouts.app_guru') {{-- Menggunakan layout app_guru --}}

@section('title', 'Isi/Edit Presensi')
@section('page_title', 'Isi/Edit Presensi')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        .card {
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
            background-color: #ffffff;
            color: #343a40;
            margin-bottom: 30px;
        }
        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, .125);
            padding: 1.25rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #343a40;
            margin-bottom: 0;
        }
        .table thead th {
            background-color: #f8f9fa;
            color: #495057;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }
        .table tbody tr:hover {
            background-color: #f2f2f2;
        }
        .btn {
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
            color: #fff;
        }
        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(40, 167, 69, 0.2);
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            color: #fff;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(108, 117, 125, 0.2);
        }
        .form-control {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 0.75rem 1rem;
            height: auto;
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        .form-check-label {
            margin-left: 0.5rem;
        }
        .form-check-inline .form-check-input {
            margin-right: 0.25rem;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-check mr-1"></i> Isi/Edit Presensi untuk Jadwal
                        <span class="font-weight-bold">
                            {{ \Carbon\Carbon::parse($lessonSchedule->date)->translatedFormat('d F Y') }}
                            ({{ \Carbon\Carbon::parse($lessonSchedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($lessonSchedule->end_time)->format('H:i') }})
                        </span>
                        @if($lessonSchedule->topic)
                            <br><small>Topik: {{ $lessonSchedule->topic }}</small>
                        @endif
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('guru.assignments.lesson_schedules.index', $lessonSchedule->assignment->id) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Jadwal
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Alert Messages --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                            <i class="fas fa-times-circle mr-2"></i> {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    {{-- KOREKSI: Menggunakan lesson_schedule di rute --}}
                    <form action="{{ route('guru.assignments.lesson_schedules.store_attendance', ['assignment' => $lessonSchedule->assignment->id, 'lesson_schedule' => $lessonSchedule->id]) }}" method="POST">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>Nama Siswa</th>
                                        <th>Status Presensi</th>
                                        <th>Keterangan (Opsional)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($siswas as $siswa)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $siswa->name }}</td>
                                            <td>
                                                <input type="hidden" name="siswa_ids[]" value="{{ $siswa->id }}">
                                                <div class="form-check form-check-inline">
                                                    {{-- KOREKSI: Mengakses properti status dari objek attendance --}}
                                                    <input class="form-check-input" type="radio" name="status[{{ $siswa->id }}]" id="statusHadir{{ $siswa->id }}" value="Hadir" {{ (old('status.' . $siswa->id, $attendances[$siswa->id]->status ?? '')) == 'Hadir' ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="statusHadir{{ $siswa->id }}">Hadir</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    {{-- KOREKSI: Mengakses properti status dari objek attendance --}}
                                                    <input class="form-check-input" type="radio" name="status[{{ $siswa->id }}]" id="statusSakit{{ $siswa->id }}" value="Sakit" {{ (old('status.' . $siswa->id, $attendances[$siswa->id]->status ?? '')) == 'Sakit' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="statusSakit{{ $siswa->id }}">Sakit</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    {{-- KOREKSI: Mengakses properti status dari objek attendance --}}
                                                    <input class="form-check-input" type="radio" name="status[{{ $siswa->id }}]" id="statusIzin{{ $siswa->id }}" value="Izin" {{ (old('status.' . $siswa->id, $attendances[$siswa->id]->status ?? '')) == 'Izin' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="statusIzin{{ $siswa->id }}">Izin</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    {{-- KOREKSI: Mengakses properti status dari objek attendance --}}
                                                    <input class="form-check-input" type="radio" name="status[{{ $siswa->id }}]" id="statusAlpha{{ $siswa->id }}" value="Alpha" {{ (old('status.' . $siswa->id, $attendances[$siswa->id]->status ?? '')) == 'Alpha' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="statusAlpha{{ $siswa->id }}">Alpha</label>
                                                </div>
                                            </td>
                                            <td>
                                                {{-- KOREKSI: Mengakses properti keterangan dari objek attendance --}}
                                                {{-- Menggunakan old() untuk mempertahankan nilai input jika validasi gagal --}}
                                                <input type="text" name="keterangan[{{ $siswa->id }}]" class="form-control form-control-sm" value="{{ old('keterangan.' . $siswa->id, $attendances[$siswa->id]->keterangan ?? '') }}" placeholder="Keterangan (jika ada)">
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Tidak ada siswa ditemukan untuk kelas ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-success"><i class="fas fa-save mr-1"></i> Simpan Presensi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.id.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/js/tempusdominus-bootstrap-4.min.js"></script>

    <script>
        $(document).ready(function() {
            // Konfigurasi Toastr
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };

            @if (session('success'))
                toastr.success("{{ session('success') }}");
            @endif

            @if (session('error'))
                toastr.error("{{ session('error') }}");
            @endif
        });
    </script>
@endpush
