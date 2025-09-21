@extends('layouts.app_guru')

@section('title', 'Buat Jadwal Presensi Baru')
@section('page_title', 'Buat Jadwal Presensi Baru')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    {{-- Pastikan hanya satu library datetimepicker yang digunakan untuk menghindari konflik --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/css/tempusdominus-bootstrap-4.min.css" />
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
        .form-group label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
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
        .form-control.is-invalid {
            border-color: #dc3545;
        }
        .invalid-feedback {
            font-size: 0.875rem;
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
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-calendar-plus mr-1"></i> Buat Jadwal Presensi Baru untuk
                        <span class="font-weight-bold">{{ $assignment->mataPelajaran->nama_mapel ?? 'N/A' }}</span> -
                        Kelas <span class="font-weight-bold">{{ $assignment->kelas->nama_kelas ?? 'N/A' }}</span>
                        @if($assignment->kelas && $assignment->kelas->jurusan)
                            ({{ $assignment->kelas->jurusan->nama_jurusan }})
                        @endif
                    </h3>
                </div>
                <form action="{{ route('guru.assignments.lesson_schedules.store', $assignment->id) }}" method="POST">
                    @csrf
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
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                                <i class="fas fa-exclamation-triangle mr-2"></i> Terjadi kesalahan! Mohon periksa input Anda.
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

                        <div class="form-group">
                            <label for="date">Pilih Tanggal Presensi:</label>
                            <div class="input-group date" id="datepicker" data-target-input="nearest">
                                <input type="text" name="date" class="form-control datetimepicker-input @error('date') is-invalid @enderror" data-target="#datepicker" value="{{ old('date') }}" required>
                                <div class="input-group-append" data-target="#datepicker" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                                @error('date')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_time">Waktu Mulai:</label>
                                    <div class="input-group date" id="timepicker-start" data-target-input="nearest">
                                        <input type="text" name="start_time" class="form-control datetimepicker-input @error('start_time') is-invalid @enderror" data-target="#timepicker-start" value="{{ old('start_time') }}" required>
                                        <div class="input-group-append" data-target="#timepicker-start" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-clock"></i></div>
                                        </div>
                                        @error('start_time')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_time">Waktu Selesai:</label>
                                    <div class="input-group date" id="timepicker-end" data-target-input="nearest">
                                        <input type="text" name="end_time" class="form-control datetimepicker-input @error('end_time') is-invalid @enderror" data-target="#timepicker-end" value="{{ old('end_time') }}" required>
                                        <div class="input-group-append" data-target="#timepicker-end" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-clock"></i></div>
                                        </div>
                                        @error('end_time')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="topic">Topik / Materi (Opsional):</label>
                            <textarea name="topic" id="topic" class="form-control @error('topic') is-invalid @enderror" rows="3" placeholder="Contoh: Pembahasan Bab 1: Pengenalan Algoritma">{{ old('topic') }}</textarea>
                            @error('topic')
                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-success"><i class="fas fa-save mr-1"></i> Simpan Jadwal</button>
                        <a href="{{ route('guru.assignments.lesson_schedules.index', $assignment->id) }}" class="btn btn-secondary"><i class="fas fa-times mr-1"></i> Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
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
            @if (session('info'))
                toastr.info("{{ session('info') }}");
            @endif

            // Inisialisasi Datepicker
            $('#datepicker').datetimepicker({
                format: 'YYYY-MM-DD', // Format tanggal yang konsisten
                autoclose: true,
                todayHighlight: true,
                locale: 'id',
                icons: {
                    time: 'fa fa-clock',
                    date: 'fa fa-calendar',
                    up: 'fa fa-chevron-up',
                    down: 'fa fa-chevron-down',
                    previous: 'fa fa-chevron-left',
                    next: 'fa fa-chevron-right',
                    today: 'fa fa-calendar-check-o',
                    clear: 'fa fa-trash',
                    close: 'fa fa-times'
                }
            });

            // Inisialisasi Timepicker untuk waktu mulai
            $('#timepicker-start').datetimepicker({
                format: 'HH:mm', // Format 24 jam
                stepping: 15, // Interval 15 menit
                icons: {
                    time: 'fa fa-clock',
                    up: 'fa fa-chevron-up',
                    down: 'fa fa-chevron-down'
                }
            });

            // Inisialisasi Timepicker untuk waktu selesai
            $('#timepicker-end').datetimepicker({
                format: 'HH:mm', // Format 24 jam
                stepping: 15, // Interval 15 menit
                icons: {
                    time: 'fa fa-clock',
                    up: 'fa fa-chevron-up',
                    down: 'fa fa-chevron-down'
                }
            });
        });
    </script>
@endpush
