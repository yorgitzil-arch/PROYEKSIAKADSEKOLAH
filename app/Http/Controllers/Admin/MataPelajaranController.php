<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Models\Guru; 
use App\Models\SchoolProfile; // <--- TAMBAHKAN INI

class MataPelajaranController extends Controller
{
    // Definisikan opsi kelompok mata pelajaran yang tetap di sini
    private $kelompokOptions = [
        'Kelompok A (Muatan Umum)',
        'Kelompok B (Muatan Kewilayahan)', // Standardisasi
        'Kelompok C1 (Dasar Bidang Keahlian)', // Standardisasi
        'Kelompok C2 (Dasar Program Keahlian)', // Standardisasi
        'Kelompok C3 (Paket Keahlian)', // Tambahan untuk kelengkapan
        'Muatan Lokal',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        // KOREKSI SANGAT PENTING: Hapus eager loading 'guru' karena relasi ini tidak ada di model MataPelajaran
        $query = MataPelajaran::query();

        if ($search) {
            $query->where('nama_mapel', 'like', '%' . $search . '%')
                ->orWhere('kode_mapel', 'like', '%' . $search . '%')
                ->orWhere('kelompok', 'like', '%' . $search . '%'); // Tambahkan pencarian berdasarkan kelompok
        }

        $mataPelajarans = $query->orderBy('nama_mapel')->paginate(10);

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.mata-pelajaran.index', compact('mataPelajarans', 'search', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Kirim opsi kelompok ke view
        $kelompokOptions = $this->kelompokOptions;
        
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.mata-pelajaran.create', compact('kelompokOptions', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_mapel' => 'required|string|max:255|unique:mata_pelajarans,nama_mapel',
                'kode_mapel' => 'nullable|string|max:20|unique:mata_pelajarans,kode_mapel',
                'kelompok' => ['nullable', 'string', 'max:255', Rule::in($this->kelompokOptions)],
                'kkm' => 'nullable|integer|min:0|max:100',
            ]);

            MataPelajaran::create($request->all());

            Log::info('Mata Pelajaran baru ditambahkan: ' . $request->nama_mapel);
            return redirect()->route('admin.mata-pelajaran.index')->with('success', 'Mata Pelajaran berhasil ditambahkan!');

        } catch (\Exception $e) {
            Log::error('Gagal menambahkan Mata Pelajaran: ' . $e->getMessage(), $request->all());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan Mata Pelajaran: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MataPelajaran $mata_pelajaran)
    {
        Log::info('Mengakses halaman edit Mata Pelajaran untuk ID: ' . $mata_pelajaran->id);
        // Kirim opsi kelompok ke view
        $kelompokOptions = $this->kelompokOptions;
        
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.mata-pelajaran.edit', compact('mata_pelajaran', 'kelompokOptions', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MataPelajaran $mata_pelajaran)
    {
        try {
            $request->validate([
                'nama_mapel' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('mata_pelajarans', 'nama_mapel')->ignore($mata_pelajaran->id),
                ],
                'kode_mapel' => [
                    'nullable',
                    'string',
                    'max:20',
                    Rule::unique('mata_pelajarans', 'kode_mapel')->ignore($mata_pelajaran->id),
                ],
                'kelompok' => ['nullable', 'string', 'max:255', Rule::in($this->kelompokOptions)],
                'kkm' => 'nullable|integer|min:0|max:100',
            ]);

            $mata_pelajaran->update($request->all());

            Log::info('Mata Pelajaran diperbarui: ' . $mata_pelajaran->nama_mapel);
            return redirect()->route('admin.mata-pelajaran.index')->with('success', 'Mata Pelajaran berhasil diperbarui!');

        } catch (\Exception $e) {
            Log::error('Gagal memperbarui Mata Pelajaran: ' . $e->getMessage(), ['mapel_id' => $mata_pelajaran->id, 'request_data' => $request->all()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui Mata Pelajaran: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MataPelajaran $mata_pelajaran)
    {
        try {
            $mata_pelajaran->delete();
            Log::info('Mata Pelajaran berhasil dihapus: ' . $mata_pelajaran->nama_mapel);
            return redirect()->route('admin.mata-pelajaran.index')->with('success', 'Mata Pelajaran berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus Mata Pelajaran: ' . $e->getMessage(), ['mapel_id' => $mata_pelajaran->id]);
            return redirect()->route('admin.mata-pelajaran.index')->with('error', 'Terjadi kesalahan saat menghapus Mata Pelajaran: ' . $e->getMessage());
        }
    }
}