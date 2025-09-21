<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\AssignmentGiven;
use App\Models\AssignmentSubmission;
use App\Models\Siswa;
use Carbon\Carbon;
use App\Models\SchoolProfile; // <--- TAMBAHKAN INI

class AssignmentSubmissionController extends Controller
{
    /**
     * Menampilkan daftar tugas yang diberikan untuk kelas siswa yang login,
     * beserta status pengumpulannya.
     */
    public function index()
    {
        $siswa = Auth::guard('siswa')->user();

        if (!$siswa || !$siswa->kelas_id) {
            // --- Tambahkan ini untuk $schoolProfile ---
            $schoolProfile = SchoolProfile::firstOrCreate([]);
            // -----------------------------------------
            return view('siswa.assignments_submissions.index', compact('schoolProfile'))->with('info', 'Anda belum terdaftar di kelas mana pun. Silakan lengkapi data diri Anda atau hubungi admin.'); // <--- Tambahkan 'schoolProfile' di compact
        }

        $kelasId = $siswa->kelas_id;

        // Ambil semua tugas yang diberikan untuk kelas siswa ini
        $assignmentsGiven = AssignmentGiven::where('kelas_id', $kelasId)
            ->with(['guru', 'mataPelajaran', 'submissions' => function($query) use ($siswa) {
                // Hanya load submission dari siswa yang login untuk efisiensi
                $query->where('siswa_id', $siswa->id);
            }])
            ->orderBy('due_date', 'desc')
            ->paginate(10); // Gunakan paginate()

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('siswa.assignments_submissions.index', compact('assignmentsGiven', 'siswa', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menampilkan form untuk mengumpulkan tugas.
     */
    public function create(AssignmentGiven $assignmentGiven)
    {
        $siswa = Auth::guard('siswa')->user();

        // Pastikan tugas ini ditujukan untuk kelas siswa yang login
        if ($assignmentGiven->kelas_id !== $siswa->kelas_id) {
            return redirect()->route('siswa.assignments-submissions.index')->with('error', 'Tugas ini tidak ditujukan untuk kelas Anda.');
        }

        // Cek apakah siswa sudah mengumpulkan tugas ini
        $existingSubmission = AssignmentSubmission::where('assignment_given_id', $assignmentGiven->id)
            ->where('siswa_id', $siswa->id)
            ->first();

        if ($existingSubmission) {
            return redirect()->route('siswa.assignments-submissions.show', $existingSubmission->id)->with('info', 'Anda sudah mengumpulkan tugas ini. Anda bisa melihat detailnya.');
        }

        $assignmentGiven->load(['mataPelajaran', 'guru', 'kelas']);

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('siswa.assignments_submissions.create', compact('assignmentGiven', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menyimpan pengumpulan tugas baru ke database.
     */
    public function store(Request $request, AssignmentGiven $assignmentGiven)
    {
        $siswa = Auth::guard('siswa')->user();

        // Pastikan tugas ini ditujukan untuk kelas siswa yang login
        if ($assignmentGiven->kelas_id !== $siswa->kelas_id) {
            return redirect()->route('siswa.assignments-submissions.index')->with('error', 'Tugas ini tidak ditujukan untuk kelas Anda.');
        }

        // Cek apakah siswa sudah mengumpulkan tugas ini
        $existingSubmission = AssignmentSubmission::where('assignment_given_id', $assignmentGiven->id)
            ->where('siswa_id', $siswa->id)
            ->first();

        if ($existingSubmission) {
            return redirect()->route('siswa.assignments-submissions.show', $existingSubmission->id)->with('info', 'Anda sudah mengumpulkan tugas ini.');
        }

        $request->validate([
            'file_submission' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,zip,rar|max:10240', // Max 10MB
            'notes' => 'nullable|string',
        ]);

        $filePath = null;
        if ($request->hasFile('file_submission')) {
            $filePath = $request->file('file_submission')->store('submissions/' . $siswa->nis, 'public');
        }

        AssignmentSubmission::create([
            'assignment_given_id' => $assignmentGiven->id,
            'siswa_id' => $siswa->id,
            'file_path' => $filePath,
            'notes' => $request->notes,
            'submitted_at' => now(), // Menggunakan 'submitted_at' sesuai model
        ]);

        return redirect()->route('siswa.assignments-submissions.index')->with('success', 'Tugas berhasil dikumpulkan!');
    }

    /**
     * Menampilkan detail pengumpulan tugas siswa.
     */
    public function show(AssignmentSubmission $submission)
    {
        $siswa = Auth::guard('siswa')->user();

        // Pastikan pengumpulan ini milik siswa yang login
        if ($submission->siswa_id !== $siswa->id) {
            return redirect()->route('siswa.assignments-submissions.index')->with('error', 'Anda tidak memiliki izin untuk melihat pengumpulan ini.');
        }

        $submission->load(['assignmentGiven.mataPelajaran', 'assignmentGiven.guru', 'assignmentGiven.kelas']);

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('siswa.assignments_submissions.show', compact('submission', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Mengunduh file pengumpulan tugas siswa.
     */
    public function downloadFile(AssignmentSubmission $submission)
    {
        $siswa = Auth::guard('siswa')->user();

        // Pastikan pengumpulan ini milik siswa yang login
        if ($submission->siswa_id !== $siswa->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengunduh file ini.');
        }

        if ($submission->file_path && Storage::disk('public')->exists($submission->file_path)) {
            return Storage::disk('public')->download($submission->file_path, 'jawaban_' . $submission->assignmentGiven->title . '_' . $siswa->nis . '.' . pathinfo($submission->file_path, PATHINFO_EXTENSION));
        }

        return redirect()->back()->with('error', 'File pengumpulan tidak ditemukan.');
    }
}