<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NilaiAkademik; 
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\TahunAjaran; 
use App\Models\Semester;  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\SchoolProfile; 

class GradeManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
        try {
            // Get filter values from request
            $search = $request->input('search');
            $filterSiswa = $request->input('filter_siswa');
            $filterGuru = $request->input('filter_guru');
            $filterMapel = $request->input('filter_mapel');
            $filterKelas = $request->input('filter_kelas');
            $filterTahunAjaran = $request->input('filter_tahun_ajaran');
            $filterSemester = $request->input('filter_semester');

            $query = NilaiAkademik::with([
                'siswa',
                'mataPelajaran',
                'assignment.guru',
                'assignment.kelas',
                'tahunAjaran',
                'semester'
            ]);

            if ($search) {
                $query->where(function ($q) use ($search) {
                    // Cari di nama siswa
                    $q->whereHas('siswa', function ($q2) use ($search) {
                        $q2->where('name', 'like', '%' . $search . '%');
                    })
                    // Cari di nama mata pelajaran
                    ->orWhereHas('mataPelajaran', function ($q2) use ($search) {
                        $q2->where('nama_mapel', 'like', '%' . $search . '%');
                    })
                    // Cari di jenis nilai (misal: 'ulangan_harian', 'tugas')
                    ->orWhere('jenis_nilai', 'like', '%' . $search . '%')
                    // Cari di nama nilai (misal: 'Ulangan Harian Bab 1')
                    ->orWhere('nama_nilai', 'like', '%' . $search . '%');
                });
            }

            if ($filterSiswa) {
                $query->where('siswa_id', $filterSiswa);
            }

            if ($filterGuru) {
                // Filter berdasarkan guru yang memberikan assignment
                $query->whereHas('assignment', function ($q) use ($filterGuru) {
                    $q->where('guru_id', $filterGuru);
                });
            }

            if ($filterMapel) {
                // NilaiAkademik memiliki mata_pelajaran_id secara langsung
                $query->where('mata_pelajaran_id', $filterMapel);
            }

            if ($filterKelas) {
                // Filter berdasarkan kelas dari assignment
                $query->whereHas('assignment', function ($q) use ($filterKelas) {
                    $q->where('kelas_id', $filterKelas);
                });
            }

            if ($filterTahunAjaran) {
                $query->where('tahun_ajaran_id', $filterTahunAjaran);
            }

            if ($filterSemester) {
                $query->where('semester_id', $filterSemester);
            }

            // Order and paginate results
            // Mengurutkan berdasarkan tanggal nilai terbaru atau ID terbaru
            $grades = $query->orderBy('tanggal_nilai', 'desc')->paginate(10); 

            // Data untuk filter dropdowns
            $siswas = Siswa::orderBy('name')->get();
            $gurus = Guru::orderBy('name')->get();
            $mataPelajarans = MataPelajaran::orderBy('nama_mapel')->get();
            $kelas = Kelas::orderBy('nama_kelas')->get();
            $tahunAjarans = TahunAjaran::orderBy('nama', 'desc')->get(); 
            $semesters = Semester::orderBy('nama', 'asc')->get();  

            $schoolProfile = SchoolProfile::firstOrCreate([]);

            return view('admin.grade-management.index', compact(
                'grades', 'search', 'siswas', 'gurus', 'mataPelajarans', 'kelas',
                'filterSiswa', 'filterGuru', 'filterMapel', 'filterKelas',
                'tahunAjarans', 'semesters', 'filterTahunAjaran', 'filterSemester',
                'schoolProfile' 
            ));

        } catch (\Exception $e) {
            Log::error("Error in Admin GradeManagementController index: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat data nilai. ' . $e->getMessage());
        }
    }

}