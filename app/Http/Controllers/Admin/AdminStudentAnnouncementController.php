<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminStudentAnnouncement;
use App\Models\SchoolProfile; // <--- TAMBAHKAN INI

class AdminStudentAnnouncementController extends Controller
{
    /**
     * Menampilkan daftar pengumuman sekolah yang dibuat oleh admin.
     */
    public function index()
    {
        $adminAnnouncements = AdminStudentAnnouncement::orderBy('created_at', 'desc')->get();

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.admin_student_announcements.index', compact('adminAnnouncements', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menampilkan form untuk membuat pengumuman baru.
     */
    public function create()
    {
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.admin_student_announcements.create', compact('schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menyimpan pengumuman baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        AdminStudentAnnouncement::create([
            'admin_id' => Auth::guard('admin')->id(),
            'title' => $request->title,
            'message' => $request->message,
        ]);

        return redirect()->route('admin.admin-student-announcements.index')->with('success', 'Pengumuman berhasil dibuat!');
    }

    /**
     * Menampilkan detail pengumuman.
     */
    public function show(AdminStudentAnnouncement $adminStudentAnnouncement)
    {
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.admin_student_announcements.show', compact('adminStudentAnnouncement', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menampilkan form untuk mengedit pengumuman.
     */
    public function edit(AdminStudentAnnouncement $adminStudentAnnouncement)
    {
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.admin_student_announcements.edit', compact('adminStudentAnnouncement', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Memperbarui pengumuman di database.
     */
    public function update(Request $request, AdminStudentAnnouncement $adminStudentAnnouncement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $adminStudentAnnouncement->update([
            'title' => $request->title,
            'message' => $request->message,
        ]);

        return redirect()->route('admin.admin-student-announcements.index')->with('success', 'Pengumuman berhasil diperbarui!');
    }

    /**
     * Menghapus pengumuman dari database.
     */
    public function destroy(AdminStudentAnnouncement $adminStudentAnnouncement)
    {
        $adminStudentAnnouncement->delete();
        return redirect()->route('admin.admin-student-announcements.index')->with('success', 'Pengumuman berhasil dihapus!');
    }
}