<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SchoolSetting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\SchoolProfile; // <--- TAMBAHKAN INI

class SchoolSettingController extends Controller
{
    public function __construct()
    {
        // Anda mungkin ingin menambahkan middleware otentikasi admin di sini
        // $this->middleware('auth:admin');
    }

    /**
     * Menampilkan formulir pengaturan sekolah.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Ambil pengaturan sekolah pertama atau buat instance baru jika belum ada
        $settings = SchoolSetting::firstOrNew([]);
        
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.settings.index', compact('settings', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menyimpan atau memperbarui pengaturan sekolah.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_sekolah' => 'nullable|string|max:255',
            'nssn' => 'nullable|string|max:255',
            'npsn' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:1000',
            'logo_kiri' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi untuk upload gambar
            'logo_kanan' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi untuk upload gambar
        ]);

        try {
            $settings = SchoolSetting::firstOrNew([]);

            $settings->nama_sekolah = $request->nama_sekolah;
            $settings->nssn = $request->nssn;
            $settings->npsn = $request->npsn;
            $settings->alamat = $request->alamat;

            // Handle Logo Kiri Upload
            if ($request->hasFile('logo_kiri')) {
                // Hapus logo lama jika ada
                if ($settings->logo_kiri_path && Storage::disk('public')->exists($settings->logo_kiri_path)) {
                    Storage::disk('public')->delete($settings->logo_kiri_path);
                }
                $path = $request->file('logo_kiri')->store('school_logos', 'public');
                $settings->logo_kiri_path = $path;
            } elseif ($request->input('clear_logo_kiri')) { // Jika checkbox 'clear_logo_kiri' dicentang
                if ($settings->logo_kiri_path && Storage::disk('public')->exists($settings->logo_kiri_path)) {
                    Storage::disk('public')->delete($settings->logo_kiri_path);
                }
                $settings->logo_kiri_path = null;
            }

            // Handle Logo Kanan Upload
            if ($request->hasFile('logo_kanan')) {
                // Hapus logo lama jika ada
                if ($settings->logo_kanan_path && Storage::disk('public')->exists($settings->logo_kanan_path)) {
                    Storage::disk('public')->delete($settings->logo_kanan_path);
                }
                $path = $request->file('logo_kanan')->store('school_logos', 'public');
                $settings->logo_kanan_path = $path;
            } elseif ($request->input('clear_logo_kanan')) { // Jika checkbox 'clear_logo_kanan' dicentang
                if ($settings->logo_kanan_path && Storage::disk('public')->exists($settings->logo_kanan_path)) {
                    Storage::disk('public')->delete($settings->logo_kanan_path);
                }
                $settings->logo_kanan_path = null;
            }

            $settings->save();

            return redirect()->back()->with('success', 'Pengaturan sekolah berhasil diperbarui.');

        } catch (\Exception $e) {
            Log::error("Error saving school settings: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui pengaturan sekolah: ' . $e->getMessage());
        }
    }
}