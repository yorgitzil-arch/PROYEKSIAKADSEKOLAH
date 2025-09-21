<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\SchoolProfile; // <--- TAMBAHKAN INI

class AnnouncementController extends Controller
{
    /**
     * Menampilkan daftar pengumuman.
     * Mendukung pencarian berdasarkan judul atau konten.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $announcements = Announcement::query()
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('content', 'like', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.public_content.announcements.index', compact('announcements', 'search', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menampilkan formulir untuk membuat pengumuman baru.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.public_content.announcements.create', compact('schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menyimpan pengumuman baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'author' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        Announcement::create($request->all());

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail pengumuman tertentu.
     * (Tidak ada view khusus, bisa di-handle di index atau edit)
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function show(Announcement $announcement)
    {
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        // Karena ini me-redirect ke 'edit', schoolProfile akan tetap dibutuhkan di sana.
        // Namun, jika Anda memiliki view 'show' terpisah, pastikan schoolProfile dikirimkan.
        return redirect()->route('admin.announcements.edit', $announcement)->with(compact('schoolProfile'));
        // NOTE: Mengirim data dengan with() saat redirect tidak selalu ideal untuk semua tipe data.
        // Lebih baik ambil di controller tujuan (edit method)
    }

    /**
     * Menampilkan formulir untuk mengedit pengumuman tertentu.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function edit(Announcement $announcement)
    {
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.public_content.announcements.edit', compact('announcement', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Memperbarui pengumuman tertentu di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'author' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $announcement->update($request->all());

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil diperbarui!');
    }

    /**
     * Menghapus pengumuman tertentu dari database.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil dihapus!');
    }
}