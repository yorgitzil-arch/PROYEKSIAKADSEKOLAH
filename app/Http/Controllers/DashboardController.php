<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Admin;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\MataPelajaran;
use App\Models\Assignment;
use App\Models\Announcement;
use App\Models\CarouselItem;
use App\Models\SchoolProfile; 
use App\Models\Award;
use App\Models\PublicTeacher;
use App\Models\AdmissionInfo;
use App\Models\ContactInfo;
use App\Models\Footer;
use App\Models\GuruStudentAnnouncement;
use App\Models\AdminStudentAnnouncement;
use App\Models\ContactMessage;
use App\Models\TahunAjaran;
use App\Models\Semester;
use App\Models\RekapNilaiMapel;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function adminDashboard()
    {
        // Statistik Pengguna
        $totalAdmins = Admin::count();
        $totalGurus = Guru::count();
        $totalSiswa = Siswa::count();
        $totalSiswaPending = Siswa::where('status', 'pending')->count();
        $totalSiswaConfirmed = Siswa::where('status', 'dikonfirmasi')->count();

        // Statistik Data Master
        $totalKelas = Kelas::count();
        $totalMataPelajaran = MataPelajaran::count();
        $totalJurusans = Jurusan::count();

        // Statistik Konten Publik
        $totalAnnouncements = Announcement::count();
        $latestAnnouncements = Announcement::orderBy('created_at', 'desc')->limit(3)->get();

        $totalCarouselItems = CarouselItem::count();
        $latestCarouselItems = CarouselItem::orderBy('created_at', 'desc')->limit(3)->get();

        $schoolProfileExists = SchoolProfile::exists();
        $schoolProfile = SchoolProfile::first(); // Data schoolProfile diambil di sini

        $totalAwards = Award::count();
        $latestAwards = Award::orderBy('award_date', 'desc')->limit(3)->get();

        $totalPublicTeachers = PublicTeacher::count();
        $latestPublicTeachers = PublicTeacher::with('guru')->orderBy('display_order', 'asc')->limit(3)->get();

        $admissionInfoExists = AdmissionInfo::exists();
        $admissionInfo = AdmissionInfo::first();

        $contactInfoExists = ContactInfo::exists();
        $contactInfo = ContactInfo::first();

        $footerExists = Footer::exists();
        $footer = Footer::first();


        // Total Data Publikasi (Gabungan dari Pengumuman, Penghargaan, CarouselItem)
        $totalPublikasi = $totalAnnouncements + $totalAwards + $totalCarouselItems;

        // Jumlah Pesan Belum Dibaca
        $pesanBelumDibaca = ContactMessage::where('is_read', false)->count();

        // Data untuk Grafik Pendaftaran Siswa (per bulan)
        $registrations = Siswa::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, count(*) as total')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $registrationMonths = $registrations->pluck('month')->toArray();
        $registrationCounts = $registrations->pluck('total')->toArray();

        // Jika tidak ada data, inisialisasi dengan nilai default untuk Chart.js
        if (empty($registrationMonths)) {
            $registrationMonths = [
                Carbon::now()->subMonths(2)->format('Y-m'),
                Carbon::now()->subMonth()->format('Y-m'),
                Carbon::now()->format('Y-m')
            ];
            $registrationCounts = [0, 0, 0];
        }


        return view('admin.dashboard', compact(
            'totalAdmins',
            'totalGurus',
            'totalSiswa',
            'totalSiswaPending',
            'totalSiswaConfirmed',
            'totalKelas',
            'totalMataPelajaran',
            'totalJurusans',
            'totalAnnouncements',
            'latestAnnouncements',
            'totalCarouselItems',
            'latestCarouselItems',
            'schoolProfileExists',
            'schoolProfile',
            'totalAwards',
            'latestAwards',
            'totalPublicTeachers',
            'latestPublicTeachers',
            'admissionInfoExists',
            'admissionInfo',
            'contactInfoExists',
            'contactInfo',
            'footerExists',
            'footer',
            'totalPublikasi',
            'pesanBelumDibaca',
            'registrationMonths',
            'registrationCounts'
        ));
    }

    public function guruDashboard()
    {
        if (Auth::guard('guru')->check()) {
            $guru = Auth::guard('guru')->user();
            $schoolProfile = SchoolProfile::firstOrCreate([]);

            return view('guru.dashboard', [
                'guru' => $guru,
                'schoolProfile' => $schoolProfile 
            ]);
        }
        return redirect()->route('guru.login');
    }

    public function siswaDashboard()
    {
        if (Auth::guard('siswa')->check()) {
            $siswa = Auth::guard('siswa')->user();
            $activeTahunAjaran = TahunAjaran::where('is_active', true)->first();
            $activeSemester = Semester::where('is_active', true)->first();
            
            // Mengambil semua data nilai siswa untuk diarsip dan dikelompokkan
            $gradeArchive = RekapNilaiMapel::where('siswa_id', $siswa->id)
                ->with(['tahunAjaran', 'semester', 'mataPelajaran.guru'])
                ->get()
                ->groupBy(function($item) {
                    // Mengelompokkan berdasarkan Tahun Ajaran dan Semester
                    $tahunAjaranName = $item->tahunAjaran->nama ?? 'Tidak Diketahui';
                    $semesterName = $item->semester->nama ?? 'Tidak Diketahui';
                    return $tahunAjaranName . ' - ' . $semesterName;
                })
                ->map(function($group) {
                    $totalNilai = 0;
                    $count = 0;
                    $grades = [];

                    foreach ($group as $item) {
                        $nilai = $item->nilai_pengetahuan_angka;
                        if (!is_null($nilai)) {
                            $totalNilai += $nilai;
                            $count++;
                        }
                        
                        $grades[] = [
                            'subject_name' => optional($item->mataPelajaran)->nama_mapel ?? 'Mata Pelajaran Tidak Dikenal',
                            'teacher_name' => optional($item->guruPengampu)->name?? 'Guru Tidak Dikenal',
                            'score' => $nilai,
                        ];
                    }
                    
                    $average = ($count > 0) ? round($totalNilai / $count, 2) : 0;
                    
                    return [
                        'grades' => $grades,
                        'average' => $average,
                        'tahun_ajaran' => optional($group->first()->tahunAjaran)->nama,
                        'semester' => optional($group->first()->semester)->nama,
                    ];
                });

            // Mengambil data untuk grafik nilai
            $rekapNilai = RekapNilaiMapel::where('siswa_id', $siswa->id)
                ->where('tahun_ajaran_id', optional($activeTahunAjaran)->id)
                ->where('semester_id', optional($activeSemester)->id)
                ->with('mataPelajaran')
                ->get();

            $subjectAverages = $rekapNilai->groupBy(function($rekap) {
                return optional($rekap->mataPelajaran)->id;
            })->filter()->map(function ($subjectRekaps) {
                $firstRekap = $subjectRekaps->first();
                $subjectName = optional($firstRekap->mataPelajaran)->nama_mapel ?? 'Mata Pelajaran Tidak Dikenal';
                return [
                    'subject_name' => $subjectName,
                    'average_score' => round($subjectRekaps->avg('nilai_pengetahuan_angka'), 2),
                ];
            })->values()->toArray();

            $guruAnnouncements = GuruStudentAnnouncement::where('kelas_id', $siswa->kelas_id)
                ->orWhereNull('kelas_id')
                ->with('guru')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            $adminStudentAnnouncements = AdminStudentAnnouncement::orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
            $schoolProfile = SchoolProfile::firstOrCreate([]);

            // Mengambil semua tahun ajaran dan semester untuk dropdown filter
            $allTahunAjaran = TahunAjaran::orderBy('nama', 'desc')->get();
            $allSemester = Semester::orderBy('nama', 'asc')->get();

            return view('siswa.siswa', [
                'siswa' => $siswa,
                'subjectAverages' => $subjectAverages,
                'guruAnnouncements' => $guruAnnouncements,
                'adminStudentAnnouncements' => $adminStudentAnnouncements,
                'schoolProfile' => $schoolProfile,
                'gradeArchive' => $gradeArchive,
                'allTahunAjaran' => $allTahunAjaran,
                'allSemester' => $allSemester,
            ]);
        }
        return redirect()->route('siswa.login');
    }
}