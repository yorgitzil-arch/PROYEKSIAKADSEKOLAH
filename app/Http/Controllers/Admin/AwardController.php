<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Award;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\SchoolProfile; // <--- TAMBAHKAN INI

class AwardController extends Controller
{
    /**
     * Menampilkan daftar penghargaan.
     * Mendukung pencarian berdasarkan judul atau pemberi penghargaan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $awards = Award::query()
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('awarded_by', 'like', '%' . $search . '%');
            })
            ->orderBy('award_date', 'desc')
            ->paginate(10);

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.public_content.awards.index', compact('awards', 'search', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menampilkan formulir untuk membuat penghargaan baru.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.public_content.awards.create', compact('schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menyimpan penghargaan baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'awarded_by' => 'nullable|string|max:255',
            'award_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->except(['_token', 'image']);
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('award_images', 'public');
            $data['image_path'] = $imagePath;
        }

        Award::create($data);

        return redirect()->route('admin.awards.index')->with('success', 'Penghargaan berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail penghargaan tertentu.
     * (Biasanya detail ditampilkan di halaman edit atau tidak ada view terpisah)
     *
     * @param  \App\Models\Award  $award
     * @return \Illuminate\Http\Response
     */
    public function show(Award $award)
    {
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        // Karena ini me-redirect ke 'edit', schoolProfile akan tetap dibutuhkan di sana.
        return redirect()->route('admin.awards.edit', $award)->with(compact('schoolProfile'));
        // NOTE: Mengirim data dengan with() saat redirect tidak selalu ideal untuk semua tipe data.
        // Lebih baik ambil di controller tujuan (edit method)
    }

    /**
     * Menampilkan formulir untuk mengedit penghargaan tertentu.
     *
     * @param  \App\Models\Award  $award
     * @return \Illuminate\Http\Response
     */
    public function edit(Award $award)
    {
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.public_content.awards.edit', compact('award', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Memperbarui penghargaan tertentu di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Award  $award
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Award $award)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'awarded_by' => 'nullable|string|max:255',
            'award_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->except(['_token', '_method', 'image']); // Ambil semua data kecuali token, method, dan image

        // Handle upload gambar
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($award->image_path) {
                Storage::disk('public')->delete($award->image_path);
            }
            // Simpan gambar baru
            $imagePath = $request->file('image')->store('award_images', 'public');
            $data['image_path'] = $imagePath;
        } else {
            if ($award->image_path && !isset($data['image_path'])) {
                $data['image_path'] = $award->image_path;
            }
        }

        $award->update($data);

        return redirect()->route('admin.awards.index')->with('success', 'Penghargaan berhasil diperbarui!');
    }

    /**
     * Menghapus penghargaan tertentu dari database.
     *
     * @param  \App\Models\Award  $award
     * @return \Illuminate\Http\Response
     */
    public function destroy(Award $award)
    {
        if ($award->image_path) {
            Storage::disk('public')->delete($award->image_path);
        }
        $award->delete();
        return redirect()->route('admin.awards.index')->with('success', 'Penghargaan berhasil dihapus!');
    }
}