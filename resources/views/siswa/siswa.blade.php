@extends('layouts.app_siswa')

@section('title', 'Dashboard Siswa')
@section('page_title', 'Dashboard Siswa')

@push('css')
<style>
.chart-container {
/* Menghapus tinggi tetap */
height: 100%;
display: flex;
flex-direction: column;
justify-content: flex-start;
}

    .chart-wrapper {
        position: relative;
        height: 300px; 
        width: 100%;
    }

    #gradeChart {
        position: absolute;
        width: 100% !important;
        height: 100% !important;
    }
    .profile-info {
        background:  #fafafaff;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        width: 90%;
        border-radius:15px;
    }
    .profile-info-container {
        text-align: left;
        padding-left: 60px; 
        padding-bottom:5px;
        color: #2b5b91ff;
    

    }

    .profile-image-wrapper {
        position: relative;
        display: inline-block;
    }

    .edit-profile-icon {
        position: absolute;
        bottom: -5px;
        right: 15px;
        background-color: #007bff;
        color: white;
        padding: 8px;
        border-radius: 50%;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        font-size: 16px;
        z-index: 10;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 35px;
        height: 35px;
    }

    .edit-profile-icon:hover {
        background-color: #acb6c2ff;
        transform: scale(1.1);
        transition: all 0.2s ease-in-out;
    }
    
    .grade-archive-table {
        width: 100%;
        border-collapse: collapse;
    }
    .grade-archive-table th, .grade-archive-table td {
        border: 1px solid #e2e8f0;
        padding: 12px;
        text-align: left;
    }
    .grade-archive-table th {
        background-color: #f8f9fa;
    }
    .grade-archive-table tfoot td {
        font-weight: bold;
        background-color: #e2f0fd;
    }
    .filter-form .form-group {
        margin-right: 15px;
    }
</style>

@endpush

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
            <div class="row d-flex align-items-stretch">
        <div class="col-md-6 mb-4 d-flex">
        <div class="card h-100 w-100">
            <h4 class="mb-3" style="font-size:20px;padding-top:10px; padding-left:25px;"><strong>Informasi Siswa</strong></h4>
                <div class="card-body" style="padding-left:60px;">
                    <div class="profile-info">
                        <div class="text-center" style="padding-top:20px;">
                            <div class="profile-image-wrapper">
                                        <img src="{{ asset('storage/' . Auth::guard('siswa')->user()->foto_profile_path) }}"
                                            class="img-circle elevation-2"
                                            alt="User Image"
                                            style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #dfefffff;">
                                        <a href="{{ route('siswa.data-diri.edit') }}" class="edit-profile-icon">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                            </div>
                                    <p style="font-size: 25px; color: #114e94ff;"><strong> {{ $siswa->name }}</strong></p>
                                </div>
                                    <div class="profile-info-container">
                                        <p>NIS    : <strong>{{ $siswa->nis }}</strong></p>
                                        <p>Kelas  : <strong>{{ $siswa->kelas->nama_kelas ?? 'Belum Ditentukan' }}</strong></p>
                                        <p>Jurusan  : <strong>{{ $siswa->kelas->jurusan->nama_jurusan ?? 'Belum Ditentukan' }}</strong></p>
                                        <p>Email    : <strong>{{ $siswa->email ?? 'Belum Ditentukan' }}</strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Bagian Kanan: Kurva Nilai --}}
                    <div class="col-md-6 mb-4 d-flex">
                        <div class="card chart-container h-100 w-100 p-3">
                            <h4 class="mb-3" style="text-align:center; font-size:20px;"><strong>Progres Nilai Anda</strong></h4>
                            <div class="chart-wrapper">
                                <canvas id="gradeChart"></canvas>
                            </div>
                            <p id="chartStatus" class="text-center text-muted mt-3" style="display: none;">Memuat grafik...</p>
                            <p id="noChartData" class="text-center text-muted mt-3" style="display: none;">Belum ada data nilai untuk ditampilkan.</p>
                        </div>
                    </div>
                </div>

                {{-- Arsip Nilai Siswa --}}
                <h4 class="mt-4">Nilai Semester anda</h4>
                <div class="card card-info card-outline">
                    <div class="card-body">
                        {{-- Form Filter --}}
                        <div class="d-flex flex-wrap align-items-center mb-4 filter-form">
                            <div class="form-group mr-4">
                                <label for="tahun_ajaran_filter">Tahun Ajaran:</label>
                                <select class="form-control" id="tahun_ajaran_filter">
                                    @foreach($allTahunAjaran as $ta)
                                        <option value="{{ $ta->id }}">{{ $ta->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="semester_filter">Semester:</label>
                                <select class="form-control" id="semester_filter">
                                    @foreach($allSemester as $semester)
                                        <option value="{{ $semester->id }}">{{ $semester->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        {{-- Tabel Nilai --}}
                        @php
                            $activeTahunAjaranId = optional(App\Models\TahunAjaran::where('is_active', true)->first())->nama;
                            $activeSemesterId = optional(App\Models\Semester::where('is_active', true)->first())->nama;
                            $activeKey = $activeTahunAjaranId . ' - ' . $activeSemesterId;
                            $activeSemesterData = $gradeArchive[$activeKey] ?? null;
                        @endphp

                        @if($activeSemesterData && count($activeSemesterData['grades']) > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered grade-archive-table">
                                    <thead>
                                        <tr>
                                            <th>Mata Pelajaran</th>
                                            <th>Guru</th>
                                            <th>Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($activeSemesterData['grades'] as $grade)
                                            <tr>
                                                <td>{{ $grade['subject_name'] }}</td>
                                                <td>{{ $grade['teacher_name'] }}</td>
                                                <td>{{ $grade['score'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2" class="text-right"><strong>Rata-rata Nilai Semester:</strong></td>
                                            <td><strong>{{ $activeSemesterData['average'] }}</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <p class="text-center text-muted mt-2"><small><i>Nilai yang ditampilkan adalah untuk semester yang sedang aktif.</i></small></p>
                        @else
                            <div class="alert alert-info text-center mt-3">
                                Belum ada arsip nilai untuk ditampilkan pada semester ini.
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Pengumuman dari Guru --}}
                <h4 class="mt-4">Pengumuman dari Guru</h4>
                <div class="card card-warning card-outline">
                    <div class="card-body p-0">
                        @if($guruAnnouncements->isEmpty())
                            <div class="alert alert-info m-3 text-center">
                                Tidak ada pengumuman dari guru untuk Anda.
                            </div>
                        @else
                            <ul class="products-list product-list-in-card pl-2 pr-2">
                                @foreach($guruAnnouncements as $announcement)
                                    <li class="item">
                                        <div class="product-img">
                                            <i class="fas fa-chalkboard-teacher text-primary"></i>
                                        </div>
                                        <div class="product-info">
                                            <a href="#" class="product-title">{{ $announcement->title }}
                                                <span class="badge badge-info float-right">{{ \Carbon\Carbon::parse($announcement->created_at)->diffForHumans() }}</span>
                                            </a>
                                            <span class="product-description">
                                                Dari: {{ $announcement->guru->name ?? 'Guru Tidak Dikenal' }}
                                                @if($announcement->kelas_id)
                                                    (Untuk Kelas {{ $announcement->kelas->nama_kelas ?? 'N/A' }})
                                                @else
                                                    (Untuk Semua Kelas)
                                                @endif
                                                <br>
                                                {{ Str::limit($announcement->message, 80) }}
                                            </span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <div class="card-footer text-center">
                        <a href="#" class="small-box-footer text-muted">Lihat Semua Pengumuman Guru <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                {{-- Pengumuman Khusus Siswa dari Admin --}}
                <h4 class="mt-4">Pengumuman Khusus Siswa dari Admin</h4>
                <div class="card card-primary card-outline">
                    <div class="card-body p-0">
                        @if($adminStudentAnnouncements->isEmpty())
                            <div class="alert alert-info m-3 text-center">
                                Tidak ada pengumuman khusus siswa dari admin saat ini.
                            </div>
                        @else
                            <ul class="products-list product-list-in-card pl-2 pr-2">
                                @foreach($adminStudentAnnouncements as $announcement)
                                    <li class="item">
                                        <div class="product-img">
                                            <i class="fas fa-bullhorn text-primary"></i>
                                        </div>
                                        <div class="product-info">
                                            <a href="#" class="product-title">{{ $announcement->title }}
                                                <span class="badge badge-primary float-right">{{ \Carbon\Carbon::parse($announcement->created_at)->diffForHumans() }}</span>
                                            </a>
                                            <span class="product-description">
                                                {{ Str::limit($announcement->message, 80) }}
                                            </span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <div class="card-footer text-center">
                        <a href="#" class="small-box-footer text-muted">Lihat Semua Pengumuman Khusus Siswa <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                {{-- Logout form dengan ID unik --}}
                <form id="logout-form-siswa-dashboard" action="{{ route('logout') }}" method="post" class="mt-4">
                    @csrf
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function loadChartJs(callback) {
if (typeof Chart !== 'undefined') {
console.log('Chart.js sudah dimuat.');
callback();
return;
}

        console.warn('Chart.js CDN utama gagal dimuat atau belum siap. Mencoba fallback CDN.');
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js';
        script.onload = () => {
            console.log('Chart.js fallback CDN dimuat.');
            callback();
        };
        script.onerror = () => {
            console.error('Kedua Chart.js CDN gagal dimuat!');
            const chartContainer = document.querySelector('.chart-container');
            if (chartContainer) {
                chartContainer.innerHTML = '<p class="text-center text-danger">Error: Gagal memuat library grafik. Periksa koneksi internet.</p>';
                chartContainer.style.height = 'auto';
            }
        };
        document.head.appendChild(script);
    }

    $(document).ready(function () {
        console.log('jQuery document ready event fired.');
        const chartStatusElement = document.getElementById('chartStatus');
        const noChartDataElement = document.getElementById('noChartData');

        if (chartStatusElement) chartStatusElement.style.display = 'block';

        loadChartJs(function() {
            console.log('Chart.js is ready. Attempting to initialize chart.');

            const subjectAverages = @json($subjectAverages);

            const labels = subjectAverages.map(item => item.subject_name);
            const data = subjectAverages.map(item => item.average_score);

            console.log('subjectAverages dari PHP:', subjectAverages);
            console.log('Labels untuk Chart.js:', labels);
            console.log('Data untuk Chart.js:', data);

            const ctx = document.getElementById('gradeChart');
            console.log('Canvas element (ctx):', ctx);

            if (ctx) {
                if (labels.length > 0) {
                    const chartContext = ctx.getContext('2d');
                    if (chartContext) {
                        new Chart(chartContext, {
                            type: 'line',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Nilai' ,
                                    data: data,
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                    tension: 0.4,
                                    fill: true,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        max: 100,
                                        title: {
                                            display: true,
                                            text: 'Nilai'
                                        }
                                    },
                                    x: {
                                        title: {
                                            display: true,
                                            text: 'Mata Pelajaran'
                                        }
                                    }
                                },
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'top',
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                let label = context.dataset.label || '';
                                                if (label) {
                                                    label += ': ';
                                                }
                                                if (context.parsed.y !== null) {
                                                    label += context.parsed.y + ' poin';
                                                }
                                                return label;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                        console.log('Chart successfully initialized.');
                        if (chartStatusElement) chartStatusElement.style.display = 'none';
                        if (noChartDataElement) noChartDataElement.style.display = 'none';
                    } else {
                        console.error('Failed to get 2D context from canvas.');
                        const chartContainer = document.querySelector('.chart-container');
                        if (chartContainer) {
                            chartContainer.innerHTML = '<p class="text-center text-danger">Error: Gagal mendapatkan konteks grafik.</p>';
                            chartContainer.style.height = 'auto';
                        }
                    }
                } else {
                    console.log('No data for chart. Displaying message.');
                    if (chartStatusElement) chartStatusElement.style.display = 'none';
                    if (noChartDataElement) noChartDataElement.style.display = 'block';
                    const chartContainer = document.querySelector('.chart-container');
                    if (chartContainer) {
                        chartContainer.style.height = 'auto';
                    }
                }
            } else {
                console.error('Canvas element with ID "gradeChart" not found.');
                const chartContainer = document.querySelector('.chart-container');
                if (chartContainer) {
                    chartContainer.innerHTML = '<p class="text-center text-danger">Error: Elemen grafik tidak ditemukan.</p>';
                    chartContainer.style.height = 'auto';
                }
            }
        }); 
    });
</script>

@endpush