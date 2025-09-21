@extends('layouts.app_admin')

@section('title', 'Riwayat Apresiasi Guru')
@section('page_title', 'Riwayat Apresiasi Guru')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        /* Custom CSS untuk tampilan yang lebih modern */
        .card {
            border-radius: 15px; /* Sudut lebih membulat */
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08); /* Bayangan lebih halus dan dalam */
            background-color: #ffffff;
            color: #343a40;
            margin-bottom: 30px;
        }

        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, .125);
            padding: 1.25rem 1.5rem;
            display: flex;
            justify-content: space-between; /* This is key for spacing title and tools */
            align-items: center;
            flex-wrap: wrap; /* Agar responsif */
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #343a40;
            margin-bottom: 0;
            display: flex; /* Untuk ikon dan teks */
            align-items: center;
            gap: 8px; /* Jarak antara ikon dan teks judul */
        }

        /* Ensure card-tools stretches and aligns its content to the end */
        .card-tools {
            margin-left: auto; /* Pushes the card-tools to the right */
        }


        /* Styling untuk Tombol Kirim Apresiasi Baru */
        .btn-primary {
            background: linear-gradient(45deg, #007bff, #0056b3); /* Gradient biru */
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2); /* Bayangan halus */
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px; /* Jarak antara ikon dan teks */
        }

        .btn-primary:hover {
            transform: translateY(-2px); /* Efek sedikit mengangkat */
            box-shadow: 0 6px 12px rgba(0, 123, 255, 0.3); /* Bayangan lebih jelas */
            background: linear-gradient(45deg, #0056b3, #007bff); /* Balik gradient saat hover */
        }

        /* Styling untuk Tabel */
        .table {
            width: 100%;
            margin-bottom: 0;
            color: #343a40;
        }

        .table thead th {
            background-color: #f8f9fa; /* Latar belakang header tabel */
            color: #495057; /* Teks header tabel */
            border-bottom: 2px solid #dee2e6;
            padding: 1rem;
            font-weight: 600;
            text-align: left;
            vertical-align: middle; /* Pusatkan teks vertikal */
        }

        .table tbody tr {
            transition: background-color 0.2s ease;
        }

        .table tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, .03); /* Sedikit abu-abu untuk baris ganjil */
        }

        .table tbody tr:hover {
            background-color: rgba(0, 0, 0, .07); /* Lebih gelap saat hover */
        }

        .table td, .table th {
            padding: 1rem;
            vertical-align: middle;
            border-top: 1px solid #dee2e6;
        }

        /* Badge Styling for Category */
        .badge {
            font-size: 85%;
            padding: .4em .6em;
            border-radius: .3rem;
            font-weight: 600;
            white-space: nowrap; /* Mencegah teks badge patah baris */
        }
        /* Custom colors for badges based on category */
        .badge-success { background-color: #28a745; color: #fff; } /* Sangat Luar Biasa */
        .badge-info { background-color: #17a2b8; color: #fff; }    /* Baik */
        .badge-danger { background-color: #dc3545; color: #fff; }   /* Buruk */
        .badge-secondary { background-color: #6c757d; color: #fff; } /* Default/Lain-lain */

        /* Alert styling for empty state */
        .alert-info {
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem; /* Memberi jarak dari tepi card */
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 10px;
            color: #0c5460;
            background-color: #d1ecf1;
            border-color: #bee5eb;
        }

        /* Responsive adjustments */
        @media (max-width: 767.98px) {
            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            .card-tools {
                width: 100%;
                /* justify-content: flex-start;  This was the problem, remove or change this */
                margin-left: 0; /* Remove auto margin on small screens if width is 100% */
                text-align: left; /* Align button to left on small screens */
            }
            .btn-primary {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history mr-1"></i> Riwayat Apresiasi yang Anda Kirim
                    </h3>
                    <div class="card-tools"> {{-- This div now handles auto margin to push right --}}
                        <a href="{{ route('admin.appreciation-management.create') }}" class="btn btn-primary" data-toggle="tooltip" title="Kirim Apresiasi Baru">
                            <i class="fas fa-plus-circle mr-1"></i> Kirim Apresiasi Baru
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($sentAppreciations->isEmpty())
                        <div class="alert alert-info text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i><br>
                            <p class="mb-0">Anda belum mengirim apresiasi apa pun kepada guru.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table id="appreciationHistoryTable" class="table table-hover table-head-fixed text-nowrap">
                                <thead>
                                <tr>
                                    <th style="width: 50px"><i class="fas fa-hashtag"></i></th>
                                    <th><i class="fas fa-user-tie mr-1"></i> Guru Penerima</th>
                                    <th><i class="fas fa-heading mr-1"></i> Judul Apresiasi</th>
                                    <th><i class="fas fa-tags mr-1"></i> Kategori</th>
                                    <th><i class="fas fa-calendar-alt mr-1"></i> Tanggal Kirim</th>
                                    <th><i class="fas fa-comment-dots mr-1"></i> Pesan Singkat</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($sentAppreciations as $appreciation)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $appreciation->guru->name ?? '-' }}</td>
                                        <td>{{ $appreciation->title }}</td>
                                        <td>
                                            @php
                                                $badgeClass = '';
                                                switch($appreciation->category) {
                                                    case 'sangat luar biasa':
                                                        $badgeClass = 'badge-success';
                                                        break;
                                                    case 'baik':
                                                        $badgeClass = 'badge-info';
                                                        break;
                                                    case 'buruk':
                                                        $badgeClass = 'badge-danger';
                                                        break;
                                                    default:
                                                        $badgeClass = 'badge-secondary';
                                                }
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ ucfirst($appreciation->category) }}</span>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($appreciation->created_at)->format('d M Y H:i') }}</td>
                                        <td>{{ Str::limit($appreciation->message, 80) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(function() {
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

            // Menampilkan semua error validasi menggunakan Toastr (jika ada)
            @if ($errors->any())
            @foreach ($errors->all() as $error)
            toastr.error("{{ $error }}");
            @endforeach
            @endif

            // Inisialisasi Tooltip
            $('[data-toggle="tooltip"]').tooltip();

            // Optional: DataTables script (jika ingin mengaktifkannya)
            // if ($.fn.DataTable) { // Check if DataTables is loaded
            //     $('#appreciationHistoryTable').DataTable({
            //         "paging": true,
            //         "lengthChange": false,
            //         "searching": true,
            //         "ordering": true,
            //         "info": true,
            //         "autoWidth": false,
            //         "responsive": true,
            //     });
            // }
        });
    </script>
@endpush
