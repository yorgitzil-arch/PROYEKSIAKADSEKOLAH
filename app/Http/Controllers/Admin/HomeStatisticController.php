<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeStatistic; // Pastikan model HomeStatistic di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\SchoolProfile; // <--- TAMBAHKAN INI

class HomeStatisticController extends Controller
{
    /**
     * Menampilkan daftar semua statistik beranda di halaman admin.
     * Data diurutkan berdasarkan kolom 'order'.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Mengambil semua data statistik dari database, diurutkan berdasarkan 'order'
        $statistics = HomeStatistic::orderBy('order')->get();

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        // Mengembalikan view 'admin.home_statistics.index' dan meneruskan data statistik
        return view('admin.home_statistics.index', compact('statistics', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menampilkan form untuk membuat statistik beranda baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        // Mengembalikan view 'admin.home_statistics.create' untuk form tambah data
        return view('admin.home_statistics.create', compact('schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menyimpan statistik beranda yang baru dibuat ke database.
     * Slug akan secara otomatis dihasilkan dari 'title' oleh paket Sluggable.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Aturan validasi untuk data yang masuk dari form
        $validator = Validator::make($request->all(), [
            'icon_class' => 'required|string|max:255', // Kelas ikon Font Awesome (misal: fas fa-users)
            'value' => 'required|integer|min:0',      // Nilai statistik (angka)
            'title' => 'required|string|max:255',     // Judul statistik (akan menjadi sumber slug)
            'description' => 'nullable|string',        // Deskripsi opsional
            'link' => 'nullable|url|max:255',          // Link opsional, harus format URL
            'order' => 'required|integer|min:0',      // Urutan tampilan
            'is_active' => 'boolean',                  // Status aktif (boolean)
        ]);

        // Jika validasi gagal, kembalikan ke halaman sebelumnya dengan error dan input lama
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        HomeStatistic::create($request->all());
        return redirect()->route('admin.home-statistics.index')->with('success', 'Statistik berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit statistik beranda yang spesifik.
     * Menerima ID statistik secara eksplisit dan menemukan modelnya.
     *
     * @param  int  $homeStatisticId  ID dari HomeStatistic yang akan diedit
     * @return \Illuminate\View\View
     */
    public function edit(int $homeStatisticId) // Parameter diubah menjadi ID
    {
        // Temukan HomeStatistic berdasarkan ID. Jika tidak ditemukan, Laravel akan otomatis 404.
        $homeStatistic = HomeStatistic::findOrFail($homeStatisticId);

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        // Mengembalikan view 'admin.home_statistics.edit' dan meneruskan instance HomeStatistic
        return view('admin.home_statistics.edit', compact('homeStatistic', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Memperbarui statistik beranda yang spesifik di database.
     * Slug akan secara otomatis diperbarui oleh paket Sluggable jika 'title' berubah.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $homeStatisticId  ID dari HomeStatistic yang akan diperbarui
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $homeStatisticId) // Parameter diubah menjadi ID
    {
        // Temukan HomeStatistic berdasarkan ID. Jika tidak ditemukan, Laravel akan otomatis 404.
        $homeStatistic = HomeStatistic::findOrFail($homeStatisticId);

        // Aturan validasi untuk data yang masuk dari form
        $validator = Validator::make($request->all(), [
            'icon_class' => 'required|string|max:255',
            'value' => 'required|integer|min:0',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'link' => 'nullable|url|max:255',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // Jika validasi gagal, kembalikan ke halaman sebelumnya dengan error dan input lama
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $homeStatistic->update($request->all());

        return redirect()->route('admin.home-statistics.index')->with('success', 'Statistik berhasil diperbarui!');
    }

    /**
     * Menghapus statistik beranda yang spesifik dari database.
     *
     * @param  int  $homeStatisticId  ID dari HomeStatistic yang akan dihapus
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $homeStatisticId) // Parameter diubah menjadi ID
    {
        $homeStatistic = HomeStatistic::findOrFail($homeStatisticId);
        $homeStatistic->delete();
        return redirect()->route('admin.home-statistics.index')->with('success', 'Statistik berhasil dihapus!');
    }
}