<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\GuruStudentAnnouncement;
use App\Models\Kelas;
use App\Models\SchoolProfile; // <--- TAMBAHKAN INI

class StudentAnnouncementController extends Controller
{
    /**
     * Menampilkan daftar pengumuman yang dibuat oleh guru yang sedang login.
     */
    public function index()
    {
        $guruId = Auth::guard('guru')->id();
        $announcements = GuruStudentAnnouncement::where('guru_id', $guruId)
            ->with('kelas')
            ->orderBy('created_at', 'desc')
            ->get();

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('guru.student_announcements.index', compact('announcements', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menampilkan form untuk membuat pengumuman baru.
     */
    public function create()
    {
        $guru = Auth::guard('guru')->user();
        // Ambil kelas-kelas yang diampu oleh guru ini dari assignments yang sudah dikonfirmasi
        $kelasOptions = $guru->assignments()
            ->where('status_konfirmasi', 'dikonfirmasi')
            ->with('kelas')
            ->get()
            ->pluck('kelas.nama_kelas', 'kelas_id')
            ->unique();

        // Tambahkan opsi "Semua Kelas"
        $kelasOptions = ['' => 'Semua Kelas'] + $kelasOptions->toArray();

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('guru.student_announcements.create', compact('kelasOptions', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menyimpan pengumuman baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'kelas_id' => 'nullable|exists:kelas,id', // Nullable karena bisa "Semua Kelas"
        ]);

        $guruId = Auth::guard('guru')->id();

        GuruStudentAnnouncement::create([
            'guru_id' => $guruId,
            'kelas_id' => $request->kelas_id,
            'title' => $request->title,
            'message' => $request->message,
        ]);

        return redirect()->route('guru.student-announcements.index')->with('success', 'Pengumuman berhasil dibuat!');
    }

    /**
     * Menampilkan detail pengumuman.
     */
    public function show(GuruStudentAnnouncement $studentAnnouncement)
    {
        // Pastikan guru yang login adalah pemilik pengumuman ini
        if ($studentAnnouncement->guru_id !== Auth::guard('guru')->id()) {
            return redirect()->route('guru.student-announcements.index')->with('error', 'Anda tidak memiliki izin untuk melihat pengumuman ini.');
        }
        $studentAnnouncement->load('kelas');
        
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('guru.student_announcements.show', compact('studentAnnouncement', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menampilkan form untuk mengedit pengumuman.
     */
    public function edit(GuruStudentAnnouncement $studentAnnouncement)
    {
        // Pastikan guru yang login adalah pemilik pengumuman ini
        if ($studentAnnouncement->guru_id !== Auth::guard('guru')->id()) {
            return redirect()->route('guru.student-announcements.index')->with('error', 'Anda tidak memiliki izin untuk mengedit pengumuman ini.');
        }

        $guru = Auth::guard('guru')->user();
        $kelasOptions = $guru->assignments()
            ->where('status_konfirmasi', 'dikonfirmasi')
            ->with('kelas')
            ->get()
            ->pluck('kelas.nama_kelas', 'kelas_id')
            ->unique();
        $kelasOptions = ['' => 'Semua Kelas'] + $kelasOptions->toArray();

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('guru.student_announcements.edit', compact('studentAnnouncement', 'kelasOptions', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Memperbarui pengumuman di database.
     */
    public function update(Request $request, GuruStudentAnnouncement $studentAnnouncement)
    {
        // Pastikan guru yang login adalah pemilik pengumuman ini
        if ($studentAnnouncement->guru_id !== Auth::guard('guru')->id()) {
            return redirect()->route('guru.student-announcements.index')->with('error', 'Anda tidak memiliki izin untuk memperbarui pengumuman ini.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'kelas_id' => 'nullable|exists:kelas,id',
        ]);

        $studentAnnouncement->update([
            'kelas_id' => $request->kelas_id,
            'title' => $request->title,
            'message' => $request->message,
        ]);

        return redirect()->route('guru.student-announcements.index')->with('success', 'Pengumuman berhasil diperbarui!');
    }

    /**
     * Menghapus pengumuman dari database.
     */
    public function destroy(GuruStudentAnnouncement $studentAnnouncement)
    {
        // Pastikan guru yang login adalah pemilik pengumuman ini
        if ($studentAnnouncement->guru_id !== Auth::guard('guru')->id()) {
            return redirect()->route('guru.student-announcements.index')->with('error', 'Anda tidak memiliki izin untuk menghapus pengumuman ini.');
        }

        $studentAnnouncement->delete();

        return redirect()->route('guru.student-announcements.index')->with('success', 'Pengumuman berhasil dihapus!');
    }
}