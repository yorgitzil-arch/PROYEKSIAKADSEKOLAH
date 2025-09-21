<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Assignment;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\Siswa;
use App\Models\Attendance;
use App\Models\TahunAjaran; // Import Model TahunAjaran
use App\Models\Semester;    // Import Model Semester
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\SchoolProfile; // <--- TAMBAHKAN INI

class AssignmentController extends Controller
{
    /**
     * Menampilkan daftar penugasan mengajar untuk guru yang sedang login.
     */
    public function index(Request $request) // <--- Tambahkan parameter Request
    {
        if (!Auth::guard('guru')->check()) {
            return redirect()->route('guru.login');
        }

        $guruId = Auth::guard('guru')->id();
        $search = $request->query('search'); // Ambil dari query string
        $tahun_ajaran_id = $request->query('tahun_ajaran_id');
        $semester_id = $request->query('semester_id');

        $query = Assignment::where('guru_id', $guruId)
            // Eager load mataPelajaran untuk mengakses 'kelompok', dan relasi lainnya
            ->with(['mataPelajaran', 'kelas.jurusan', 'tahunAjaran', 'semester']);

        if ($search) {
            $query->whereHas('mataPelajaran', function ($q) use ($search) {
                $q->where('nama_mapel', 'like', '%' . $search . '%')
                  ->orWhere('kelompok', 'like', '%' . $search . '%'); // <--- Tambahkan pencarian berdasarkan kelompok
            })->orWhereHas('kelas', function ($q) use ($search) {
                $q->where('nama_kelas', 'like', '%' . $search . '%');
            });
        }

        // Terapkan filter berdasarkan Tahun Ajaran
        if ($tahun_ajaran_id) {
            $query->where('tahun_ajaran_id', $tahun_ajaran_id);
        }

        // Terapkan filter berdasarkan Semester
        if ($semester_id) {
            $query->where('semester_id', $semester_id);
        }

        $assignments = $query->orderBy('kelas_id')
            ->orderBy('mata_pelajaran_id')
            ->paginate(10);

        // Ambil semua Tahun Ajaran dan Semester untuk dropdown filter di view
        $tahunAjarans = TahunAjaran::orderBy('nama', 'desc')->get();
        $semesters = Semester::orderBy('nama', 'asc')->get();

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        // View yang dipanggil adalah 'guru.assignments.index'
        // <--- Kirim semua variabel yang dibutuhkan ke view
        return view('guru.assignments.index', compact('assignments', 'search', 'tahunAjarans', 'semesters', 'tahun_ajaran_id', 'semester_id', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Mengkonfirmasi penugasan mengajar oleh guru (Dikonfirmasi/Ditolak).
     * Metode ini tidak berubah.
     */
    public function confirm(Request $request, Assignment $assignment)
    {
        if ($assignment->guru_id !== Auth::guard('guru')->id()) {
            Log::warning('Percobaan konfirmasi penugasan tidak sah.', [
                'assignment_id' => $assignment->id,
                'guru_id_attempt' => Auth::guard('guru')->id(),
                'guru_id_owner' => $assignment->guru_id
            ]);
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengkonfirmasi penugasan ini.');
        }

        try {
            $request->validate([
                'status' => 'required|in:Dikonfirmasi,Ditolak',
            ]);

            if ($assignment->status_konfirmasi === 'Pending' || $assignment->status_konfirmasi === 'Menunggu Konfirmasi') { // Sesuaikan dengan nilai di DB
                $assignment->status_konfirmasi = $request->status;
                $assignment->save();

                Log::info('Penugasan Mengajar dikonfirmasi/ditolak oleh Guru.', [
                    'assignment_id' => $assignment->id,
                    'guru_id' => Auth::guard('guru')->id(),
                    'status_baru' => $request->status
                ]);

                return redirect()->back()->with('success', 'Penugasan berhasil ' . strtolower($request->status) . '!');
            }

            Log::info('Percobaan konfirmasi penugasan yang sudah tidak Pending.', [
                'assignment_id' => $assignment->id,
                'guru_id' => Auth::guard('guru')->id(),
                'status_saat_ini' => $assignment->status_konfirmasi
            ]);
            return redirect()->back()->with('info', 'Penugasan ini sudah tidak dalam status Pending.');

        } catch (\Exception $e) {
            Log::error('Gagal mengkonfirmasi Penugasan Mengajar oleh Guru: ' . $e->getMessage(), [
                'assignment_id' => $assignment->id,
                'guru_id' => Auth::guard('guru')->id(),
                'request_data' => $request->all()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengkonfirmasi penugasan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form untuk input nilai siswa berdasarkan penugasan.
     * (Asumsi ini adalah method untuk input nilai)
     */
    public function inputNilai(Assignment $assignment)
    {
        if (!Auth::guard('guru')->check() || $assignment->guru_id !== Auth::guard('guru')->id()) {
            abort(403, 'Anda tidak memiliki akses ke penugasan ini.');
        }

        // Eager load mataPelajaran untuk mendapatkan kelompok
        $assignment->load(['mataPelajaran', 'kelas.jurusan', 'tahunAjaran', 'semester']);

        // Ambil daftar siswa yang terkait dengan kelas di assignment ini
        $students = Siswa::where('kelas_id', $assignment->kelas_id)
                             ->orderBy('name')
                             ->get();

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        // Anda akan mengirimkan $assignment dan $students ke view input nilai
        return view('guru.nilai.input', compact('assignment', 'students', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    // Anda mungkin perlu menambahkan method storeNilai atau updateNilai di sini
    // untuk menangani penyimpanan nilai yang diinput oleh guru.
    // Contoh:
    // public function storeNilai(Request $request, Assignment $assignment)
    // {
    //     // Validasi dan simpan nilai
    //     // ...
    // }
}