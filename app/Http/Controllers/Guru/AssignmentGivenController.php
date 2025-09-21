<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\AssignmentGiven;
use App\Models\AssignmentSubmission;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\SchoolProfile; // <--- TAMBAHKAN INI

class AssignmentGivenController extends Controller
{
    /**
     * Menampilkan daftar tugas yang diberikan oleh guru yang sedang login.
     */
    public function index()
    {
        $guruId = Auth::guard('guru')->id();
        $assignmentsGiven = AssignmentGiven::where('guru_id', $guruId)
            ->with(['kelas', 'mataPelajaran'])
            ->orderBy('due_date', 'desc')
            ->paginate(10); // Pastikan ini paginate()

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('guru.assignments_given.index', compact('assignmentsGiven', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menampilkan form untuk membuat tugas baru.
     */
    public function create()
    {
        $guru = Auth::guard('guru')->user();

        // Ambil mata pelajaran dan kelas yang diampu oleh guru ini dari assignments yang sudah dikonfirmasi
        $assignments = $guru->assignments()
            ->where('status_konfirmasi', 'Dikonfirmasi') // Pastikan konsisten dengan 'Dikonfirmasi'
            ->with(['mataPelajaran', 'kelas'])
            ->get();

        $mataPelajaranOptions = $assignments->pluck('mataPelajaran.nama_mapel', 'mata_pelajaran_id')->unique();
        $kelasOptions = $assignments->pluck('kelas.nama_kelas', 'kelas_id')->unique();

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('guru.assignments_given.create', compact('mataPelajaranOptions', 'kelasOptions', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menyimpan tugas baru ke database.
     */
    public function store(Request $request)
    {
        $guruId = Auth::guard('guru')->id();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'kelas_id' => 'required|exists:kelas,id',
            'due_date' => 'nullable|date|after_or_equal:today',
            'file_assignment' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:10240', // Max 10MB
        ]);

        $filePath = null;
        if ($request->hasFile('file_assignment')) {
            $filePath = $request->file('file_assignment')->store('assignments/given', 'public');
        }

        AssignmentGiven::create([
            'guru_id' => $guruId,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'kelas_id' => $request->kelas_id,
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
            'due_date' => $request->due_date,
        ]);

        return redirect()->route('guru.assignments-given.index')->with('success', 'Tugas berhasil dibuat!');
    }

    /**
     * Menampilkan detail tugas dan daftar pengumpulan siswa.
     */
    public function show(string $id)
    {
        $assignmentGiven = AssignmentGiven::findOrFail($id); // Cari tugas berdasarkan ID

        if ($assignmentGiven->guru_id !== Auth::guard('guru')->id()) {
            return redirect()->route('guru.assignments-given.index')->with('error', 'Anda tidak memiliki izin untuk melihat tugas ini.');
        }

        // Load relasi kelas, mata pelajaran, dan semua pengumpulan tugas beserta data siswa
        $assignmentGiven->load(['kelas', 'mataPelajaran', 'submissions.siswa']);

        // Ambil daftar siswa di kelas ini yang belum mengumpulkan tugas
        $siswaInClass = Siswa::where('kelas_id', $assignmentGiven->kelas_id)->get();
        $submittedSiswaIds = $assignmentGiven->submissions->pluck('siswa_id')->toArray();
        $unsubmittedSiswa = $siswaInClass->whereNotIn('id', $submittedSiswaIds);

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('guru.assignments_given.show', compact('assignmentGiven', 'unsubmittedSiswa', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menampilkan form untuk mengedit tugas.
     */
    public function edit(string $id)
    {
        $assignmentGiven = AssignmentGiven::findOrFail($id);
        // Pastikan guru yang login adalah pemilik tugas ini
        if ($assignmentGiven->guru_id !== Auth::guard('guru')->id()) {
            return redirect()->route('guru.assignments-given.index')->with('error', 'Anda tidak memiliki izin untuk mengedit tugas ini.');
        }

        $guru = Auth::guard('guru')->user();
        $assignments = $guru->assignments()
            ->where('status_konfirmasi', 'Dikonfirmasi')
            ->with(['mataPelajaran', 'kelas'])
            ->get();

        $mataPelajaranOptions = $assignments->pluck('mataPelajaran.nama_mapel', 'mata_pelajaran_id')->unique();
        $kelasOptions = $assignments->pluck('kelas.nama_kelas', 'kelas_id')->unique();

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('guru.assignments_given.edit', compact('assignmentGiven', 'mataPelajaranOptions', 'kelasOptions', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Memperbarui tugas di database.
     */
    public function update(Request $request, string $id)
    {
        $assignmentGiven = AssignmentGiven::findOrFail($id); // Cari tugas berdasarkan ID

        // Pastikan guru yang login adalah pemilik tugas ini
        if ($assignmentGiven->guru_id !== Auth::guard('guru')->id()) {
            return redirect()->route('guru.assignments-given.index')->with('error', 'Anda tidak memiliki izin untuk memperbarui tugas ini.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'kelas_id' => 'required|exists:kelas,id',
            'due_date' => 'nullable|date|after_or_equal:today',
            'file_assignment' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:10240', // Max 10MB
        ]);

        $filePath = $assignmentGiven->file_path;

        if ($request->hasFile('file_assignment')) {
            // Hapus file lama jika ada
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            $filePath = $request->file('file_assignment')->store('assignments/given', 'public');
        } elseif ($request->boolean('remove_file')) { // Jika ada checkbox untuk menghapus file
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            $filePath = null;
        }

        $assignmentGiven->update([
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'kelas_id' => $request->kelas_id,
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
            'due_date' => $request->due_date,
        ]);

        return redirect()->route('guru.assignments-given.index')->with('success', 'Tugas berhasil diperbarui!');
    }

    /**
     * Menghapus tugas dari database.
     */
    public function destroy(string $id)
    {
        $assignmentGiven = AssignmentGiven::findOrFail($id); // Cari tugas berdasarkan ID

        // Pastikan guru yang login adalah pemilik tugas ini
        if ($assignmentGiven->guru_id !== Auth::guard('guru')->id()) {
            return redirect()->route('guru.assignments-given.index')->with('error', 'Anda tidak memiliki izin untuk menghapus tugas ini.');
        }

        // Hapus file terkait jika ada
        if ($assignmentGiven->file_path && Storage::disk('public')->exists($assignmentGiven->file_path)) {
            Storage::disk('public')->delete($assignmentGiven->file_path);
        }

        $assignmentGiven->delete();

        return redirect()->route('guru.assignments-given.index')->with('success', 'Tugas berhasil dihapus!');
    }

    /**
     * Mengunduh file tugas yang diberikan oleh guru.
     */
    public function downloadFile(string $id)
    {
        $assignmentGiven = AssignmentGiven::findOrFail($id); // Cari tugas berdasarkan ID

        // Pastikan guru yang login adalah pemilik tugas ini atau memiliki akses
        if ($assignmentGiven->guru_id !== Auth::guard('guru')->id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengunduh file ini.');
        }

        if ($assignmentGiven->file_path && Storage::disk('public')->exists($assignmentGiven->file_path)) {
            return Storage::disk('public')->download($assignmentGiven->file_path, $assignmentGiven->title . '.' . pathinfo($assignmentGiven->file_path, PATHINFO_EXTENSION));
        }

        return redirect()->back()->with('error', 'File tugas tidak ditemukan.');
    }

    /**
     * Menampilkan detail pengumpulan tugas oleh siswa dan form untuk memberi nilai/feedback.
     */
    public function showSubmission(string $submissionId)
    {
        $submission = AssignmentSubmission::findOrFail($submissionId); // Cari submission berdasarkan ID

        // Pastikan guru yang login adalah guru yang memberikan tugas ini
        if ($submission->assignmentGiven->guru_id !== Auth::guard('guru')->id()) {
            return redirect()->route('guru.assignments-given.index')->with('error', 'Anda tidak memiliki izin untuk melihat pengumpulan ini.');
        }

        $submission->load(['siswa', 'assignmentGiven.mataPelajaran', 'assignmentGiven.kelas']);

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('guru.assignments_given.submission_detail', compact('submission', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Memberi nilai dan feedback pada pengumpulan tugas siswa.
     */
    public function gradeSubmission(Request $request, string $submissionId)
    {
        $submission = AssignmentSubmission::findOrFail($submissionId); // Cari submission berdasarkan ID

        // Pastikan guru yang login adalah guru yang memberikan tugas ini
        if ($submission->assignmentGiven->guru_id !== Auth::guard('guru')->id()) {
            return redirect()->route('guru.assignments-given.index')->with('error', 'Anda tidak memiliki izin untuk memberi nilai pada pengumpulan ini.');
        }

        $request->validate([
            'score' => 'required|integer|min:0|max:100',
            'feedback' => 'nullable|string',
        ]);

        $submission->update([
            'score' => $request->score,
            'feedback' => $request->feedback,
        ]);

        return redirect()->route('guru.assignments-given.show', $submission->assignmentGiven->id)->with('success', 'Nilai dan feedback berhasil disimpan!');
    }

    /**
     * Mengunduh file pengumpulan tugas oleh siswa.
     */
    public function downloadSubmissionFile(string $submissionId)
    {
        $submission = AssignmentSubmission::findOrFail($submissionId); // Cari submission berdasarkan ID

        // Pastikan guru yang login adalah guru yang memberikan tugas ini
        if ($submission->assignmentGiven->guru_id !== Auth::guard('guru')->id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengunduh file ini.');
        }

        if ($submission->file_path && Storage::disk('public')->exists($submission->file_path)) {
            return Storage::disk('public')->download($submission->file_path, 'submission_' . $submission->siswa->nis . '_' . $submission->assignmentGiven->title . '.' . pathinfo($submission->file_path, PATHINFO_EXTENSION));
        }

        return redirect()->back()->with('error', 'File pengumpulan tidak ditemukan.');
    }
}