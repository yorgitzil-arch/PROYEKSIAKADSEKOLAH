<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Siswa;
use App\Models\NilaiAkademik;
use App\Models\NilaiKeterampilan;
use App\Models\NilaiSikap;
use App\Models\TahunAjaran; // Import model TahunAjaran
use App\Models\Semester;   // Import model Semester
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Carbon\Carbon; // Pastikan Carbon di-import jika digunakan
use App\Models\SchoolProfile; // <--- TAMBAHKAN INI

class GradeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:guru'); // Pastikan ini sesuai guard guru Anda
    }

    /**
     * Menampilkan daftar penugasan (assignments) guru
     * atau daftar siswa untuk penugasan tertentu.
     */
    public function index(Request $request)
    {
        try {
            $guruId = Auth::guard('guru')->id();
            $activeTahunAjaran = TahunAjaran::where('is_active', true)->first();
            $activeSemester = Semester::where('is_active', true)->first();

            if (!$activeTahunAjaran || !$activeSemester) {
                return redirect()->back()->with('error', 'Tidak ada Tahun Ajaran atau Semester aktif yang ditemukan.');
            }

            // Ambil semua Tahun Ajaran dan Semester untuk filter dropdown
            $tahunAjarans = TahunAjaran::orderBy('nama', 'desc')->get();
            $semesters = Semester::orderBy('nama', 'asc')->get();

            // Ambil nilai search dari request
            $search = $request->input('search');

            // --- Tambahkan ini untuk $schoolProfile ---
            $schoolProfile = SchoolProfile::firstOrCreate([]);
            // -----------------------------------------

            // Jika ada assignment_id di request, berarti guru ingin melihat daftar siswa untuk assignment tersebut
            if ($request->has('assignment_id')) {
                $assignment = Assignment::with(['mataPelajaran', 'kelas.jurusan'])
                                        ->where('guru_id', $guruId)
                                        ->where('id', $request->assignment_id)
                                        ->firstOrFail();

                // Pastikan assignment terkait dengan tahun ajaran dan semester aktif
                if ($assignment->tahun_ajaran_id != $activeTahunAjaran->id || $assignment->semester_id != $activeSemester->id) {
                    return redirect()->route('guru.grades.index')->with('error', 'Penugasan tidak valid untuk Tahun Ajaran atau Semester aktif saat ini.');
                }

                $siswas = Siswa::where('kelas_id', $assignment->kelas_id)
                                 ->orderBy('name')->get(); // Menggunakan 'name' sesuai model Siswa Anda

                return view('guru.grades.show_assignment_grades', compact('assignment', 'siswas', 'activeTahunAjaran', 'activeSemester', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
            }

            // Jika tidak ada assignment_id, tampilkan daftar assignments guru
            $query = Assignment::with(['mataPelajaran', 'kelas.jurusan'])
                                 ->where('guru_id', $guruId);

            // Terapkan filter jika ada
            if ($request->filled('tahun_ajaran_id')) {
                $query->where('tahun_ajaran_id', $request->tahun_ajaran_id);
            }
            if ($request->filled('semester_id')) {
                $query->where('semester_id', $request->semester_id);
            }

            // Terapkan filter pencarian jika ada
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->whereHas('mataPelajaran', function($q2) use ($search) {
                        $q2->where('nama_mapel', 'like', '%' . $search . '%');
                    })->orWhereHas('kelas', function($q2) use ($search) {
                        $q2->where('nama_kelas', 'like', '%' . $search . '%');
                    });
                });
            }


            // Default filter ke tahun ajaran dan semester aktif jika tidak ada filter yang dipilih
            if (!$request->filled('tahun_ajaran_id') && !$request->filled('semester_id')) {
                $query->where('tahun_ajaran_id', $activeTahunAjaran->id)
                      ->where('semester_id', $activeSemester->id);
            }

            // KOREKSI PENTING: Menggunakan paginate() daripada get()
            $assignments = $query->orderBy('kelas_id')->paginate(10); // Sesuaikan angka 10 sesuai kebutuhan

            // Pastikan semua variabel yang dibutuhkan view dikirimkan, termasuk $search
            return view('guru.assignments.index', compact('assignments', 'activeTahunAjaran', 'activeSemester', 'tahunAjarans', 'semesters', 'search', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error("Assignment not found for Guru ID: " . Auth::guard('guru')->id() . " - " . $e->getMessage());
            return redirect()->route('guru.grades.index')->with('error', 'Penugasan tidak ditemukan atau tidak valid.');
        } catch (\Exception $e) {
            Log::error("Error in Guru GradeController index: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat data nilai. ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form input nilai untuk siswa pada assignment tertentu.
     */
    public function create(Assignment $assignment, Siswa $siswa)
    {
        $guruId = Auth::guard('guru')->id();
        // Pastikan guru ini memiliki assignment ini
        if ($assignment->guru_id !== $guruId) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke penugasan ini.');
        }
        // Pastikan siswa ini ada di kelas assignment
        if ($siswa->kelas_id !== $assignment->kelas_id) {
            return redirect()->back()->with('error', 'Siswa tidak ditemukan di kelas penugasan ini.');
        }

        $activeTahunAjaran = TahunAjaran::where('is_active', true)->firstOrFail();
        $activeSemester = Semester::where('is_active', true)->firstOrFail();

        // Ambil nilai akademik, keterampilan, dan sikap yang sudah ada untuk siswa ini
        $nilaiAkademik = NilaiAkademik::where('assignment_id', $assignment->id)
                                     ->where('siswa_id', $siswa->id)
                                     ->where('mata_pelajaran_id', $assignment->mata_pelajaran_id)
                                     ->where('tahun_ajaran_id', $activeTahunAjaran->id)
                                     ->where('semester_id', $activeSemester->id)
                                     ->get();

        $nilaiKeterampilan = NilaiKeterampilan::where('assignment_id', $assignment->id)
                                              ->where('siswa_id', $siswa->id)
                                              ->where('mata_pelajaran_id', $assignment->mata_pelajaran_id)
                                              ->where('tahun_ajaran_id', $activeTahunAjaran->id)
                                              ->where('semester_id', $activeSemester->id)
                                              ->get();

        $nilaiSikap = NilaiSikap::where('assignment_id', $assignment->id)
                               ->where('siswa_id', $siswa->id)
                               ->where('mata_pelajaran_id', $assignment->mata_pelajaran_id)
                               ->where('tahun_ajaran_id', $activeTahunAjaran->id)
                               ->where('semester_id', $activeSemester->id)
                               ->get();

        // Ambil KKM dari mata pelajaran terkait assignment
        $kkm = $assignment->mataPelajaran->kkm ?? 75; // Default KKM jika tidak ditemukan

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('guru.grades.create_edit', compact(
            'assignment', 'siswa',
            'nilaiAkademik', 'nilaiKeterampilan', 'nilaiSikap',
            'activeTahunAjaran', 'activeSemester', 'kkm', 'schoolProfile' // <--- Tambahkan 'schoolProfile' di compact
        ));
    }

    /**
     * Menyimpan atau memperbarui nilai akademik.
     */
    public function storeAkademik(Request $request, Assignment $assignment, Siswa $siswa)
    {
        $guruId = Auth::guard('guru')->id();
        if ($assignment->guru_id !== $guruId) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke penugasan ini.');
        }
        if ($siswa->kelas_id !== $assignment->kelas_id) {
            return redirect()->back()->with('error', 'Siswa tidak ditemukan di kelas penugasan ini.');
        }

        $activeTahunAjaran = TahunAjaran::where('is_active', true)->firstOrFail();
        $activeSemester = Semester::where('is_active', true)->firstOrFail();

        $request->validate([
            'jenis_nilai' => ['required', Rule::in(['ulangan_harian', 'tugas', 'uts', 'uas', 'sumatif_lain'])],
            'nama_nilai' => 'nullable|string|max:255',
            'nilai' => 'required|integer|min:0|max:100',
            'tanggal_nilai' => 'required|date',
            'nilai_id' => 'nullable|exists:nilai_akademik,id', // Untuk update existing
            'keterangan' => 'nullable|string|max:500',
        ]);

        try {
            // Ambil KKM dari MataPelajaran yang terkait dengan Assignment
            $kkm = $assignment->mataPelajaran->kkm ?? 75; // Default 75 jika tidak ditemukan

            // Hitung predikat
            $nilaiAngka = $request->nilai;
            $predikat = $this->determinePredikat($nilaiAngka, $kkm); // Panggil fungsi helper

            $data = $request->except(['_token', '_method', 'nilai_id']);
            $data['assignment_id'] = $assignment->id;
            $data['siswa_id'] = $siswa->id;
            $data['mata_pelajaran_id'] = $assignment->mata_pelajaran_id;
            $data['tahun_ajaran_id'] = $activeTahunAjaran->id;
            $data['semester_id'] = $activeSemester->id;
            $data['kkm'] = $kkm;
            $data['nilai_predikat'] = $predikat;
            $data['created_by_guru_id'] = Auth::guard('guru')->id();

            if ($request->filled('nilai_id')) {
                $nilaiAkademik = NilaiAkademik::findOrFail($request->nilai_id);
                $nilaiAkademik->update($data);
                Log::info("Nilai Akademik updated: Siswa {$siswa->name}, Assignment {$assignment->id}, Jenis {$data['jenis_nilai']}");
                $message = 'Nilai Akademik berhasil diperbarui.';
            } else {
                NilaiAkademik::create($data);
                Log::info("Nilai Akademik created: Siswa {$siswa->name}, Assignment {$assignment->id}, Jenis {$data['jenis_nilai']}");
                $message = 'Nilai Akademik berhasil ditambahkan.';
            }

            return redirect()->route('guru.grades.create', ['assignment' => $assignment->id, 'siswa' => $siswa->id])->with('success', $message);
        } catch (\Exception $e) {
            Log::error("Error saving Nilai Akademik: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan nilai akademik. ' . $e->getMessage());
        }
    }

    /**
     * Menyimpan atau memperbarui nilai keterampilan.
     */
    public function storeKeterampilan(Request $request, Assignment $assignment, Siswa $siswa)
    {
        $guruId = Auth::guard('guru')->id();
        if ($assignment->guru_id !== $guruId) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke penugasan ini.');
        }
        if ($siswa->kelas_id !== $assignment->kelas_id) {
            return redirect()->back()->with('error', 'Siswa tidak ditemukan di kelas penugasan ini.');
        }

        $activeTahunAjaran = TahunAjaran::where('is_active', true)->firstOrFail();
        $activeSemester = Semester::where('is_active', true)->firstOrFail();

        $request->validate([
            'jenis_keterampilan' => ['required', Rule::in(['praktik', 'proyek', 'portofolio', 'unjuk_kerja', 'lain-lain'])],
            'nama_penilaian' => 'nullable|string|max:255',
            'nilai' => 'required|integer|min:0|max:100',
            'deskripsi_keterampilan' => 'nullable|string|max:1000',
            'tanggal_nilai_keterampilan' => 'required|date',
            'nilai_keterampilan_id' => 'nullable|exists:nilai_keterampilan,id',
        ]);

        try {
            $data = $request->except(['_token', '_method', 'nilai_keterampilan_id']);
            // Sesuaikan nama field ke yang ada di tabel
            $data['deskripsi'] = $data['deskripsi_keterampilan'];
            $data['tanggal_nilai'] = $data['tanggal_nilai_keterampilan'];

            $data['assignment_id'] = $assignment->id;
            $data['siswa_id'] = $siswa->id;
            $data['mata_pelajaran_id'] = $assignment->mata_pelajaran_id;
            $data['tahun_ajaran_id'] = $activeTahunAjaran->id;
            $data['semester_id'] = $activeSemester->id;
            $data['created_by_guru_id'] = Auth::guard('guru')->id();

            if ($request->filled('nilai_keterampilan_id')) {
                $nilaiKeterampilan = NilaiKeterampilan::findOrFail($request->nilai_keterampilan_id);
                $nilaiKeterampilan->update($data);
                Log::info("Nilai Keterampilan updated: Siswa {$siswa->name}, Assignment {$assignment->id}, Jenis {$data['jenis_keterampilan']}"); // Corrected line
                $message = 'Nilai Keterampilan berhasil diperbarui.';
            } else {
                NilaiKeterampilan::create($data);
                Log::info("Nilai Keterampilan created: Siswa {$siswa->name}, Assignment {$assignment->id}, Jenis {$data['jenis_keterampilan']}"); // Corrected line
                $message = 'Nilai Keterampilan berhasil ditambahkan.';
            }

            return redirect()->route('guru.grades.create', ['assignment' => $assignment->id, 'siswa' => $siswa->id])->with('success', $message);
        } catch (\Exception $e) {
            Log::error("Error saving Nilai Keterampilan: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan nilai keterampilan. ' . $e->getMessage());
        }
    }

    /**
     * Menyimpan atau memperbarui nilai sikap.
     */
    public function storeSikap(Request $request, Assignment $assignment, Siswa $siswa)
    {
        $guruId = Auth::guard('guru')->id();
        if ($assignment->guru_id !== $guruId) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke penugasan ini.');
        }
        if ($siswa->kelas_id !== $assignment->kelas_id) {
            return redirect()->back()->with('error', 'Siswa tidak ditemukan di kelas penugasan ini.');
        }

        $activeTahunAjaran = TahunAjaran::where('is_active', true)->firstOrFail();
        $activeSemester = Semester::where('is_active', true)->firstOrFail();

        // Validasi untuk kedua deskripsi sikap
        $request->validate([
            'deskripsi_sikap_spiritual' => 'required|string|max:1000',
            'deskripsi_sikap_sosial' => 'required|string|max:1000',
        ]);

        try {
            // Simpan atau perbarui sikap spiritual
            NilaiSikap::updateOrCreate(
                [
                    'assignment_id' => $assignment->id,
                    'siswa_id' => $siswa->id,
                    'mata_pelajaran_id' => $assignment->mata_pelajaran_id,
                    'jenis_sikap' => 'spiritual',
                    'tahun_ajaran_id' => $activeTahunAjaran->id,
                    'semester_id' => $activeSemester->id,
                ],
                [
                    'deskripsi' => $request->deskripsi_sikap_spiritual,
                    'created_by_guru_id' => Auth::guard('guru')->id(),
                ]
            );

            // Simpan atau perbarui sikap sosial
            NilaiSikap::updateOrCreate(
                [
                    'assignment_id' => $assignment->id,
                    'siswa_id' => $siswa->id,
                    'mata_pelajaran_id' => $assignment->mata_pelajaran_id,
                    'jenis_sikap' => 'sosial',
                    'tahun_ajaran_id' => $activeTahunAjaran->id,
                    'semester_id' => $activeSemester->id,
                ],
                [
                    'deskripsi' => $request->deskripsi_sikap_sosial,
                    'created_by_guru_id' => Auth::guard('guru')->id(),
                ]
            );

            Log::info("Nilai Sikap (Spiritual & Sosial) saved/updated for Siswa {$siswa->name}, Assignment {$assignment->id}");
            $message = 'Nilai Sikap berhasil disimpan/diperbarui.';

            return redirect()->route('guru.grades.create', ['assignment' => $assignment->id, 'siswa' => $siswa->id])->with('success', $message);
        } catch (\Exception $e) {
            Log::error("Error saving Nilai Sikap: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan nilai sikap. ' . $e->getMessage());
        }
    }

    /**
     * Menghapus nilai akademik.
     */
    public function destroyAkademik(NilaiAkademik $nilaiAkademik)
    {
        try {
            $assignment = $nilaiAkademik->assignment;
            // Pastikan guru yang login adalah pemilik assignment ini
            if ($assignment->guru_id !== Auth::guard('guru')->id()) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus nilai ini.');
            }

            $nilaiAkademik->delete();
            Log::info("Nilai Akademik deleted: ID {$nilaiAkademik->id}");
            return redirect()->back()->with('success', 'Nilai Akademik berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error("Error deleting Nilai Akademik: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus nilai akademik. ' . $e->getMessage());
        }
    }

    /**
     * Menghapus nilai keterampilan.
     */
    public function destroyKeterampilan(NilaiKeterampilan $nilaiKeterampilan)
    {
        try {
            $assignment = $nilaiKeterampilan->assignment;
            if ($assignment->guru_id !== Auth::guard('guru')->id()) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus nilai ini.');
            }

            $nilaiKeterampilan->delete();
            Log::info("Nilai Keterampilan deleted: ID {$nilaiKeterampilan->id}");
            return redirect()->back()->with('success', 'Nilai Keterampilan berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error("Error deleting Nilai Keterampilan: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus nilai keterampilan. ' . $e->getMessage());
        }
    }

    /**
     * Menghapus nilai sikap.
     */
    public function destroySikap(NilaiSikap $nilaiSikap)
    {
        try {
            $assignment = $nilaiSikap->assignment;
            if ($assignment->guru_id !== Auth::guard('guru')->id()) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus nilai ini.');
            }

            $nilaiSikap->delete();
            Log::info("Nilai Sikap deleted: ID {$nilaiSikap->id}");
            return redirect()->back()->with('success', 'Nilai Sikap berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error("Error deleting Nilai Sikap: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus nilai sikap. ' . $e->getMessage());
        }
    }

    /**
     * Helper function to determine grade predicate based on score and KKM.
     * You can customize this logic based on your school's grading system.
     *
     * @param float $score
     * @param int $kkm
     * @return string
     */
    private function determinePredikat(float $score, int $kkm): string
    {
        if ($score >= 90) {
            return 'A';
        } elseif ($score >= 80) {
            return 'B';
        } elseif ($score >= $kkm) { // Lulus KKM
            return 'C';
        } else {
            return 'D'; // Tidak Lulus KKM
        }
    }
}