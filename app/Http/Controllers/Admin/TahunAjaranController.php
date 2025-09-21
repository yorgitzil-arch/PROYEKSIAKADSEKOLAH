<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Models\SchoolProfile; // <--- TAMBAHKAN INI

class TahunAjaranController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Urutkan berdasarkan 'nama' karena itu kolom yang benar
            $tahunAjarans = TahunAjaran::orderBy('nama', 'desc')->paginate(10);
            
            // --- Tambahkan ini untuk $schoolProfile ---
            $schoolProfile = SchoolProfile::firstOrCreate([]);
            // -----------------------------------------

            return view('admin.tahun_ajaran.index', compact('tahunAjarans', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
        } catch (\Exception $e) {
            Log::error("Error retrieving tahun ajarans: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat data Tahun Ajaran.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.tahun_ajaran.create', compact('schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            // Gunakan 'nama' di sini dan tambahkan regex untuk format Tahun Ajaran
            'nama' => [
                'required',
                'string',
                'max:20', // Sesuaikan jika 'nama' bisa lebih dari 20 karakter
                'regex:/^\d{4}\/\d{4}$/', // Wajib ada untuk format 2023/2024
                Rule::unique('tahun_ajarans', 'nama'), // Pastikan nama tahun ajaran unik
            ],
            'is_active' => 'nullable|boolean', // Untuk checkbox
        ], [
            // Custom messages untuk validasi
            'nama.required' => 'Kolom Tahun Ajaran wajib diisi.',
            'nama.string' => 'Kolom Tahun Ajaran harus berupa teks.',
            'nama.max' => 'Kolom Tahun Ajaran tidak boleh lebih dari :max karakter.',
            'nama.regex' => 'Format Tahun Ajaran harus YYYY/YYYY (contoh: 2023/2024).',
            'nama.unique' => 'Tahun Ajaran ini sudah ada.',
        ]);

        try {
            $dataToCreate = $validatedData;
            // Pastikan is_active adalah boolean berdasarkan keberadaan checkbox
            $dataToCreate['is_active'] = $request->has('is_active');

            // Hanya boleh ada satu tahun ajaran yang aktif pada satu waktu
            if ($dataToCreate['is_active']) {
                TahunAjaran::where('is_active', true)->update(['is_active' => false]);
            }

            TahunAjaran::create($dataToCreate);
            Log::info("Tahun Ajaran created successfully: " . $dataToCreate['nama']); // Menggunakan 'nama'
            return redirect()->route('admin.tahun-ajaran.index')->with('success', 'Tahun Ajaran berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error("Error creating tahun ajaran: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan Tahun Ajaran. Pesan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TahunAjaran $tahunAjaran)
    {
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.tahun_ajaran.show', compact('tahunAjaran', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TahunAjaran $tahunAjaran)
    {
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.tahun_ajaran.edit', compact('tahunAjaran', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TahunAjaran $tahunAjaran)
    {
        $validatedData = $request->validate([
            // Gunakan 'nama' di sini dan tambahkan regex
            'nama' => [
                'required',
                'string',
                'max:20',
                'regex:/^\d{4}\/\d{4}$/',
                Rule::unique('tahun_ajarans', 'nama')->ignore($tahunAjaran->id),
            ],
            'is_active' => 'nullable|boolean',
        ], [
            // Custom messages
            'nama.required' => 'Kolom Tahun Ajaran wajib diisi.',
            'nama.string' => 'Kolom Tahun Ajaran harus berupa teks.',
            'nama.max' => 'Kolom Tahun Ajaran tidak boleh lebih dari :max karakter.',
            'nama.regex' => 'Format Tahun Ajaran harus YYYY/YYYY (contoh: 2023/2024).',
            'nama.unique' => 'Tahun Ajaran ini sudah ada.',
        ]);

        try {
            $dataToUpdate = $validatedData;
            $dataToUpdate['is_active'] = $request->has('is_active');

            // Hanya boleh ada satu tahun ajaran yang aktif pada satu waktu, kecuali yang sedang diedit
            if ($dataToUpdate['is_active']) {
                TahunAjaran::where('is_active', true)
                           ->where('id', '!=', $tahunAjaran->id)
                           ->update(['is_active' => false]);
            }

            $tahunAjaran->update($dataToUpdate);
            Log::info("Tahun Ajaran updated successfully: " . $tahunAjaran->nama); // Menggunakan 'nama'
            return redirect()->route('admin.tahun-ajaran.index')->with('success', 'Tahun Ajaran berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error("Error updating tahun ajaran: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui Tahun Ajaran. Pesan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TahunAjaran $tahunAjaran)
    {
        try {
            // PENTING: Pastikan relasi semesters(), rekapNilaiMapel(), ekstrakurikulerSiswa(), dan raports()
            // sudah didefinisikan dengan benar di model TahunAjaran.

            if ($tahunAjaran->semesters()->count() > 0) {
                   return redirect()->back()->with('error', 'Tahun Ajaran tidak bisa dihapus karena memiliki Semester terkait.');
            }
            if ($tahunAjaran->rekapNilaiMapel()->count() > 0) {
                   return redirect()->back()->with('error', 'Tahun Ajaran tidak bisa dihapus karena memiliki Rekap Nilai Mapel terkait.');
            }
            if ($tahunAjaran->ekstrakurikulerSiswa()->count() > 0) {
                   return redirect()->back()->with('error', 'Tahun Ajaran tidak bisa dihapus karena memiliki Ekstrakurikuler Siswa terkait.');
            }
            if ($tahunAjaran->raports()->count() > 0) {
                   return redirect()->back()->with('error', 'Tahun Ajaran tidak bisa dihapus karena memiliki Raport terkait.');
            }

            if ($tahunAjaran->is_active) {
                return redirect()->back()->with('error', 'Tahun Ajaran aktif tidak bisa dihapus. Nonaktifkan terlebih dahulu.');
            }

            $tahunAjaran->delete();
            Log::info("Tahun Ajaran deleted successfully: " . $tahunAjaran->nama); // Menggunakan 'nama'
            return redirect()->route('admin.tahun-ajaran.index')->with('success', 'Tahun Ajaran berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error("Error deleting tahun ajaran: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus Tahun Ajaran. Pesan: ' . $e->getMessage());
        }
    }

    /**
     * Toggle the active status of a Tahun Ajaran.
     */
    public function toggleActive(Request $request, TahunAjaran $tahunAjaran)
    {
        try {
            // Nonaktifkan semua tahun ajaran lain
            TahunAjaran::where('is_active', true)->update(['is_active' => false]);

            // Aktifkan tahun ajaran yang dipilih
            $tahunAjaran->update(['is_active' => true]);

            Log::info("Tahun Ajaran toggled active: " . $tahunAjaran->nama); // Menggunakan 'nama'
            return redirect()->route('admin.tahun-ajaran.index')->with('success', 'Tahun Ajaran ' . $tahunAjaran->nama . ' berhasil diaktifkan.');
        } catch (\Exception $e) {
            Log::error("Error toggling active status for tahun ajaran: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengaktifkan Tahun Ajaran. Pesan: ' . $e->getMessage());
        }
    }
}