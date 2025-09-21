<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PublicTeacher;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\SchoolProfile; // <--- TAMBAHKAN INI

class PublicTeacherController extends Controller
{
    /**
     * Menampilkan daftar guru yang akan ditampilkan di publik.
     * Mendukung pencarian berdasarkan nama guru, jabatan, atau kategori.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->query('search'); // Ambil query pencarian

        $publicTeachers = PublicTeacher::with('guru') // Eager load relasi guru
        ->when($search, function ($query, $search) {
            // Jika ada pencarian, filter berdasarkan nama guru, posisi, atau kategori
            $query->whereHas('guru', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })
                ->orWhere('position', 'like', '%' . $search . '%')
                ->orWhere('category', 'like', '%' . $search . '%');
        })
            ->orderBy('display_order', 'asc') // Urutkan berdasarkan display_order
            ->orderBy('created_at', 'desc') // Lalu dari yang terbaru
            ->paginate(10); // Pagination 10 item per halaman

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.public_content.public_teachers.index', compact('publicTeachers', 'search', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menampilkan formulir untuk membuat entri guru publik baru.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Ambil daftar guru yang belum ada di tabel public_teachers
        $existingPublicTeacherGuruIds = PublicTeacher::pluck('guru_id')->toArray();
        $gurus = Guru::whereNotIn('id', $existingPublicTeacherGuruIds)->get();

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.public_content.public_teachers.create', compact('gurus', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menyimpan entri guru publik baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'guru_id' => [
                'required',
                'exists:gurus,id',
                Rule::unique('public_teachers', 'guru_id'), // Pastikan guru_id unik di tabel public_teachers
            ],
            'position' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Gambar opsional
            'display_order' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
        ]);

        $data = $request->except(['_token', 'image']); // Ambil semua data kecuali token dan image

        // Handle upload gambar
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public_teacher_images', 'public'); // Simpan di folder storage/app/public/public_teacher_images
            $data['image_path'] = $imagePath;
        }

        PublicTeacher::create($data);

        return redirect()->route('admin.public-teachers.index')->with('success', 'Guru berhasil ditambahkan ke daftar publik!');
    }

    /**
     * Menampilkan detail entri guru publik tertentu.
     * (Biasanya detail ditampilkan di halaman edit atau tidak ada view terpisah)
     *
     * @param  \App\Models\PublicTeacher  $publicTeacher
     * @return \Illuminate\Http\Response
     */
    public function show(PublicTeacher $publicTeacher)
    {
        // Karena show() di sini redirect ke edit(), kita tambahkan schoolProfile di method edit()
        // --- Ini tidak perlu diubah, karena akan di-redirect ke edit yang sudah dihandle ---
        return redirect()->route('admin.public-teachers.edit', $publicTeacher);
    }

    /**
     * Menampilkan formulir untuk mengedit entri guru publik tertentu.
     *
     * @param  \App\Models\PublicTeacher  $publicTeacher
     * @return \Illuminate\Http\Response
     */
    public function edit(PublicTeacher $publicTeacher)
    {
        // Untuk edit, guru_id tidak perlu diubah, jadi tidak perlu daftar guru yang belum ada
        $gurus = Guru::all(); // Ambil semua guru untuk menampilkan nama guru
        
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.public_content.public_teachers.edit', compact('publicTeacher', 'gurus', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Memperbarui entri guru publik tertentu di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PublicTeacher  $publicTeacher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PublicTeacher $publicTeacher)
    {
        $request->validate([
            'position' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Gambar opsional saat update
            'display_order' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
        ]);

        $data = $request->except(['_token', '_method', 'image']); // Ambil semua data kecuali token, method, dan image

        // Handle upload gambar
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($publicTeacher->image_path) {
                Storage::disk('public')->delete($publicTeacher->image_path);
            }
            // Simpan gambar baru
            $imagePath = $request->file('image')->store('public_teacher_images', 'public');
            $data['image_path'] = $imagePath;
        } else {
            // Jika tidak ada gambar baru diupload, dan tidak ada perintah untuk menghapus, pertahankan gambar lama
            // Jika Anda memiliki checkbox "hapus gambar" di form, Anda harus menanganinya di sini juga.
            // Untuk saat ini, asumsikan jika tidak ada file 'image' baru, path lama tetap dipertahankan kecuali di clear secara eksplisit
            if ($publicTeacher->image_path && !isset($data['image_path'])) { // Cek jika image_path tidak diset null dari form
                $data['image_path'] = $publicTeacher->image_path;
            }
        }

        $publicTeacher->update($data);

        return redirect()->route('admin.public-teachers.index')->with('success', 'Daftar guru publik berhasil diperbarui!');
    }

    /**
     * Menghapus entri guru publik tertentu dari database.
     *
     * @param  \App\Models\PublicTeacher  $publicTeacher
     * @return \Illuminate\Http\Response
     */
    public function destroy(PublicTeacher $publicTeacher)
    {
        // Hapus gambar terkait jika ada
        if ($publicTeacher->image_path) {
            Storage::disk('public')->delete($publicTeacher->image_path);
        }
        $publicTeacher->delete();
        return redirect()->route('admin.public-teachers.index')->with('success', 'Guru berhasil dihapus dari daftar publik!');
    }
}