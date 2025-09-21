<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Assignment;
use App\Models\LessonSchedule;
use App\Models\Attendance;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\Semester;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // Import Carbon untuk manipulasi tanggal
use Illuminate\Validation\Rule; // Import Rule untuk validasi
use App\Models\SchoolProfile; // <--- TAMBAHKAN INI

class GuruLessonScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:guru');
    }

    /**
     * Menampilkan daftar penugasan (assignments) yang diampu oleh guru
     * untuk pengelolaan jadwal pelajaran/presensi.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Assignment $assignment, Request $request)
    {
        try {
            $guru = Auth::guard('guru')->user();

            // Pastikan guru yang login adalah guru pengampu assignment ini
            if ($assignment->guru_id !== $guru->id) {
                return redirect()->route('guru.assignments.index')->with('error', 'Anda tidak memiliki akses ke jadwal presensi ini.');
            }

            $activeTahunAjaran = TahunAjaran::where('is_active', true)->first();
            $activeSemester = Semester::where('is_active', true)->first();

            if (!$activeTahunAjaran || !$activeSemester) {
                return redirect()->back()->with('error', 'Tidak ada Tahun Ajaran atau Semester aktif yang ditemukan.');
            }

            // Dapatkan tanggal yang diminta dari request, atau default ke hari ini
            $selectedDate = $request->input('date', now()->toDateString());

            $query = LessonSchedule::with(['assignment.mataPelajaran', 'assignment.kelas'])
                                         ->where('assignment_id', $assignment->id)
                                         ->whereHas('assignment', function($q) use ($activeTahunAjaran, $activeSemester) {
                                             $q->where('tahun_ajaran_id', $activeTahunAjaran->id)
                                               ->where('semester_id', $activeSemester->id);
                                         })
                                         ->whereDate('date', $selectedDate);

            $lessonSchedules = $query->orderBy('date', 'desc')
                                         ->orderBy('start_time')
                                         ->paginate(10);

            // --- Tambahkan ini untuk $schoolProfile ---
            $schoolProfile = SchoolProfile::firstOrCreate([]);
            // -----------------------------------------

            return view('guru.lesson_schedules.index', compact('lessonSchedules', 'assignment', 'activeTahunAjaran', 'activeSemester', 'selectedDate', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
        } catch (\Exception $e) {
            Log::error("Error in GuruLessonScheduleController index: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat jadwal pelajaran. ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new lesson schedule.
     *
     * @param  \App\Models\Assignment  $assignment
     * @return \Illuminate\Http\Response
     */
    public function create(Assignment $assignment)
    {
        $guru = Auth::guard('guru')->user();

        if ($assignment->guru_id !== $guru->id) {
            return redirect()->route('guru.assignments.index')->with('error', 'Anda tidak memiliki akses untuk membuat jadwal presensi di penugasan ini.');
        }

        try {
            $activeTahunAjaran = TahunAjaran::where('is_active', true)->firstOrFail();
            $activeSemester = Semester::where('is_active', true)->firstOrFail();

            // --- Tambahkan ini untuk $schoolProfile ---
            $schoolProfile = SchoolProfile::firstOrCreate([]);
            // -----------------------------------------

            return view('guru.lesson_schedules.create', compact('assignment', 'activeTahunAjaran', 'activeSemester', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error("Tahun Ajaran or Semester not active when creating lesson schedule: " . $e->getMessage());
            return redirect()->back()->with('error', 'Tahun Ajaran atau Semester aktif tidak ditemukan. Harap hubungi administrator.');
        } catch (\Exception $e) {
            Log::error("Error showing create form for lesson schedule: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat form pembuatan jadwal presensi. ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created lesson schedule in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Assignment  $assignment
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Assignment $assignment)
    {
        $guru = Auth::guard('guru')->user();

        if ($assignment->guru_id !== $guru->id) {
            return redirect()->route('guru.assignments.index')->with('error', 'Anda tidak memiliki akses untuk membuat jadwal presensi di penugasan ini.');
        }

        $activeTahunAjaran = TahunAjaran::where('is_active', true)->firstOrFail();
        $activeSemester = Semester::where('is_active', true)->firstOrFail();

        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'topic' => 'nullable|string|max:255',
        ]);

        try {
            LessonSchedule::create([
                'assignment_id' => $assignment->id,
                'date' => $request->date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'topic' => $request->topic,
                'tahun_ajaran_id' => $activeTahunAjaran->id, // Tambahkan ini
                'semester_id' => $activeSemester->id,     // Tambahkan ini
            ]);

            return redirect()->route('guru.assignments.lesson_schedules.index', [
                'assignment' => $assignment->id,
                'date' => $request->date
            ])->with('success', 'Jadwal presensi berhasil ditambahkan untuk tanggal ' . \Carbon\Carbon::parse($request->date)->translatedFormat('d F Y') . '.');
        } catch (\Exception $e) {
            Log::error("Error storing lesson schedule: " . $e->getMessage());
            if (str_contains($e->getMessage(), 'Duplicate entry') && str_contains($e->getMessage(), 'lesson_schedules_assignment_id_date_unique')) {
                return redirect()->back()->withInput()->with('error', 'Jadwal presensi untuk penugasan ini pada tanggal tersebut sudah ada. Silakan pilih tanggal lain atau hapus jadwal yang sudah ada.');
            }
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan jadwal presensi: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified lesson schedule and show attendance form.
     *
     * @param  \App\Models\Assignment  $assignment
     * @param  string  $lessonScheduleIdentifier (can be 'today' or an ID)
     * @return \Illuminate\Http\Response
     */
    public function show(Assignment $assignment, $lessonScheduleIdentifier)
    {
        try {
            $guruId = Auth::guard('guru')->id();

            // First, ensure the assignment belongs to the logged-in guru
            if ($assignment->guru_id !== $guruId) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses ke penugasan ini.');
            }

            $activeTahunAjaran = TahunAjaran::where('is_active', true)->firstOrFail();
            $activeSemester = Semester::where('is_active', true)->firstOrFail();

            $lessonSchedule = null;

            if ($lessonScheduleIdentifier === 'today') {
                // If 'today', try to find an existing lesson schedule for today
                $lessonSchedule = LessonSchedule::where('assignment_id', $assignment->id)
                                                 ->whereDate('date', Carbon::today())
                                                 ->where('tahun_ajaran_id', $activeTahunAjaran->id)
                                                 ->where('semester_id', $activeSemester->id)
                                                 ->first();

                if (!$lessonSchedule) {
                    // If no lesson schedule exists for today, redirect to the create form
                    return redirect()->route('guru.assignments.lesson_schedules.create', $assignment->id)
                                     ->with('info', 'Tidak ada jadwal pelajaran untuk hari ini. Silakan buat jadwal baru.');
                }
            } else {
                // If it's not 'today', assume it's an ID and try to find it
                $lessonSchedule = LessonSchedule::where('assignment_id', $assignment->id)
                                                 ->where('id', $lessonScheduleIdentifier)
                                                 ->firstOrFail(); // Use firstOrFail to throw 404 if not found
            }

            // At this point, $lessonSchedule should be an actual LessonSchedule model instance
            // Now, fetch students and their attendance records
            $siswas = Siswa::where('kelas_id', $lessonSchedule->assignment->kelas_id)
                                 ->orderBy('name')
                                 ->get();

            // Fetch attendance records, including 'keterangan'
            // We need to pluck both status and keterangan, so it's better to get the full attendance objects
            $attendances = Attendance::where('lesson_schedule_id', $lessonSchedule->id)
                                     ->where('tanggal_presensi', $lessonSchedule->date) // <--- KOREKSI DI SINI: Gunakan 'tanggal_presensi'
                                     ->where('tahun_ajaran_id', $activeTahunAjaran->id)
                                     ->where('semester_id', $activeSemester->id)
                                     ->get()
                                     ->keyBy('siswa_id'); // Key by siswa_id for easy lookup in the view

            // --- Tambahkan ini untuk $schoolProfile ---
            $schoolProfile = SchoolProfile::firstOrCreate([]);
            // -----------------------------------------

            return view('guru.lesson_schedules.record_attendance', compact(
                'lessonSchedule',
                'siswas',
                'attendances',
                'activeTahunAjaran',
                'activeSemester',
                'schoolProfile' // <--- Tambahkan 'schoolProfile' di compact
            ));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error("LessonSchedule not found or Tahun Ajaran/Semester not active: " . $e->getMessage());
            return redirect()->back()->with('error', 'Jadwal pelajaran yang diminta tidak ditemukan atau Tahun Ajaran/Semester aktif tidak ditemukan. Pastikan jadwal tersebut ada dan aktif.');
        } catch (\Exception $e) {
            Log::error("Error showing attendance form: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat form presensi. ' . $e->getMessage());
        }
    }

    /**
     * Store attendance for a lesson schedule.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Assignment  $assignment
     * @param  \App\Models\LessonSchedule  $lessonSchedule
     * @return \Illuminate\Http\Response
     */
    public function storeAttendance(Request $request, Assignment $assignment, LessonSchedule $lessonSchedule)
    {
        $guruId = Auth::guard('guru')->id();

        if (!$lessonSchedule->assignment || $lessonSchedule->assignment->guru_id !== $guruId || $lessonSchedule->assignment_id !== $assignment->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mencatat presensi pada jadwal ini atau jadwal tidak valid.');
        }

        $activeTahunAjaran = TahunAjaran::where('is_active', true)->firstOrFail();
        $activeSemester = Semester::where('is_active', true)->firstOrFail();

        $request->validate([
            'status.*' => ['required', 'string', Rule::in(['Hadir', 'Sakit', 'Izin', 'Alpha'])],
            'keterangan.*' => 'nullable|string|max:255',
            'siswa_ids.*' => 'required|exists:siswas,id',
        ]);

        try {
            DB::beginTransaction();

            $statuses = $request->input('status');
            $keterangans = $request->input('keterangan');
            $siswaIds = $request->input('siswa_ids');

            foreach ($siswaIds as $index => $siswaId) {
                // KOREKSI PENTING: Gunakan 'tanggal_presensi' sebagai kunci unik dan untuk menyimpan tanggal presensi
                // Sesuai dengan error sebelumnya dan data tabel Anda.
                Attendance::updateOrCreate(
                    [
                        'lesson_schedule_id' => $lessonSchedule->id,
                        'siswa_id' => $siswaId,
                        'tanggal_presensi' => $lessonSchedule->date, // Menggunakan tanggal dari lesson_schedule
                        'tahun_ajaran_id' => $activeTahunAjaran->id,
                        'semester_id' => $activeSemester->id,
                    ],
                    [
                        'status' => $statuses[$siswaId],
                        'keterangan' => $keterangans[$siswaId] ?? null,
                        'assignment_id' => $lessonSchedule->assignment_id,
                        // Kolom 'date' di tabel attendances akan dibiarkan NULL jika memang nullable,
                        // atau Anda bisa menambahkannya di sini jika memang perlu diisi dan tidak nullable.
                        // Berdasarkan data contoh Anda, 'date' di attendances adalah NULL, jadi kita fokus ke 'tanggal_presensi'.
                    ]
                );
            }

            DB::commit();
            Log::info("Attendance recorded for LessonSchedule ID: " . $lessonSchedule->id);
            return redirect()->route('guru.assignments.lesson_schedules.fill_attendance', [$lessonSchedule->assignment_id, $lessonSchedule->id])->with('success', 'Presensi berhasil dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error storing attendance: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal mencatat presensi. ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified attendance from storage.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroyAttendance(Attendance $attendance)
    {
        try {
            $guruId = Auth::guard('guru')->id();
            if ($attendance->lessonSchedule->assignment->guru_id !== $guruId) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus presensi ini.');
            }

            $attendance->delete();
            Log::info("Attendance record deleted: ID {$attendance->id}");
            return redirect()->back()->with('success', 'Presensi berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error("Error deleting attendance: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus presensi. ' . $e->getMessage());
        }
    }

    /**
     * Display attendance history for a specific assignment.
     *
     * @param  \App\Models\Assignment  $assignment
     * @return \Illuminate\Http\Response
     */
    public function attendance_summary(Assignment $assignment) // Renamed from history to attendance_summary
    {
        $guru = Auth::guard('guru')->user();

        if ($assignment->guru_id !== $guru->id) {
            return redirect()->route('guru.assignments.index')->with('error', 'Anda tidak memiliki akses ke riwayat presensi ini.');
        }

        $lessonSchedules = LessonSchedule::where('assignment_id', $assignment->id)
                                         ->orderBy('date', 'desc')
                                         ->get();

        $siswas = Siswa::where('kelas_id', $assignment->kelas_id)->orderBy('name')->get();

        $attendances = Attendance::whereHas('lessonSchedule', function ($query) use ($assignment) {
                                             $query->where('assignment_id', $assignment->id);
                                         })
                                         ->get();

        $rekapPresensi = [];
        foreach ($siswas as $siswa) {
            $rekapPresensi[$siswa->id] = [
                'siswa' => $siswa,
                'sakit' => 0,
                'izin' => 0,
                'alpha' => 0,
                'hadir' => 0,
                'total_pertemuan' => $lessonSchedules->count(),
            ];
        }

        foreach ($attendances as $attendance) {
            if (isset($rekapPresensi[$attendance->siswa_id])) {
                if ($attendance->status === 'Sakit') {
                    $rekapPresensi[$attendance->siswa_id]['sakit']++;
                } elseif ($attendance->status === 'Izin') {
                    $rekapPresensi[$attendance->siswa_id]['izin']++;
                } elseif ($attendance->status === 'Alpha') {
                    $rekapPresensi[$attendance->siswa_id]['alpha']++;
                } elseif ($attendance->status === 'Hadir') {
                    $rekapPresensi[$attendance->siswa_id]['hadir']++;
                }
            }
        }

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('guru.lesson_schedules.attendance_summary', compact('assignment', 'rekapPresensi', 'lessonSchedules', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Remove the specified lesson schedule from storage.
     *
     * @param  \App\Models\Assignment  $assignment
     * @param  \App\Models\LessonSchedule  $lessonSchedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Assignment $assignment, LessonSchedule $lessonSchedule)
    {
        $guru = Auth::guard('guru')->user();

        if ($assignment->guru_id !== $guru->id) {
            return redirect()->route('guru.assignments.index')->with('error', 'Anda tidak memiliki akses untuk menghapus jadwal presensi ini.');
        }

        if ($lessonSchedule->assignment_id !== $assignment->id) {
            return redirect()->back()->with('error', 'Jadwal pelajaran tidak cocok dengan penugasan.');
        }

        try {
            DB::beginTransaction();
            // Hapus semua presensi terkait jadwal pelajaran ini
            Attendance::where('lesson_schedule_id', $lessonSchedule->id)->delete();
            // Hapus jadwal pelajaran
            $lessonSchedule->delete();
            DB::commit();
            return redirect()->route('guru.assignments.lesson_schedules.index', $assignment->id)->with('success', 'Jadwal presensi berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error deleting lesson schedule: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus jadwal presensi: ' . $e->getMessage());
        }
    }
}