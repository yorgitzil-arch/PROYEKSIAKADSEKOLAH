<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\RekapNilaiMapel;
use App\Models\TahunAjaran;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SchoolProfile; // <--- TAMBAHKAN INI

class MyGradesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:siswa');
    }

    public function index()
    {
        // KOREKSI PENTING: Langsung gunakan objek siswa yang sudah diotentikasi.
        // Karena model Siswa adalah Authenticatable, Auth::guard('siswa')->user() sudah mengembalikan objek Siswa.
        $siswa = Auth::guard('siswa')->user();

        // Mengambil tahun ajaran dan semester aktif
        $activeTahunAjaran = TahunAjaran::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        // Cek apakah tahun ajaran atau semester aktif ditemukan
        if (!$activeTahunAjaran || !$activeSemester) {
            // --- Tambahkan ini untuk $schoolProfile ---
            $schoolProfile = SchoolProfile::firstOrCreate([]);
            // -----------------------------------------
            return view('siswa.my-grades.index', compact('schoolProfile'))->with('error', 'Tidak ada Tahun Ajaran atau Semester aktif yang ditemukan. Harap hubungi admin.'); // <--- Tambahkan 'schoolProfile' di compact
        }

        // Ambil semua rekap nilai mata pelajaran untuk siswa yang login
        // Filter berdasarkan tahun ajaran dan semester aktif
        // Eager load relasi mataPelajaran, guruPengampu, dan KELAS
        $grades = RekapNilaiMapel::where('siswa_id', $siswa->id)
            ->where('tahun_ajaran_id', $activeTahunAjaran->id) // Hapus optional() karena sudah dicek di atas
            ->where('semester_id', $activeSemester->id)       // Hapus optional() karena sudah dicek di atas
            ->with(['mataPelajaran', 'guruPengampu', 'kelas'])
            ->orderBy('tahun_ajaran_id', 'desc')
            ->orderBy('semester_id', 'desc')
            ->orderBy('mapel_id')
            ->paginate(10);

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('siswa.my-grades.index', compact('grades', 'siswa', 'activeTahunAjaran', 'activeSemester', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }
}