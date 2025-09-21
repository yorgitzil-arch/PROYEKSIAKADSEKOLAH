<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\Guru;
use App\Models\TahunAjaran;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // <--- TAMBAHKAN INI UNTUK TRANSACTION
use App\Models\SchoolProfile;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Kelas::with('jurusan', 'waliKelas'); // Eager load waliKelas

            // Filter by Jurusan (jika ada)
            if ($request->filled('jurusan_id')) {
                $query->where('jurusan_id', $request->jurusan_id);
            }

            // Filter by Tingkat (jika ada)
            if ($request->filled('tingkat')) {
                $query->where('tingkat', $request->tingkat);
            }

            // Filter by Nama Kelas (jika ada)
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where('nama_kelas', 'like', "%{$search}%");
            }

            $kelas = $query->orderBy('tingkat')->orderBy('nama_kelas')->paginate(10);
            $jurusans = Jurusan::all(); // Untuk dropdown filter
            $tingkats = Kelas::select('tingkat')->distinct()->orderBy('tingkat')->pluck('tingkat'); // Untuk dropdown filter

            $schoolProfile = SchoolProfile::firstOrCreate([]);

            return view('admin.kelas.index', compact('kelas', 'jurusans', 'tingkats', 'schoolProfile'));
        } catch (\Exception $e) {
            Log::error("Error retrieving kelas: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat data kelas.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jurusans = Jurusan::all();
        // Ambil semua guru yang belum menjadi wali kelas atau yang sudah menjadi wali kelas untuk kelas lain
        // Ini agar tidak ada 2 kelas yang menunjuk 1 guru yang sama sebagai wali kelas
        // Atau, jika Anda mengizinkan satu guru menjadi wali kelas untuk banyak kelas, hapus filter ini.
        // Untuk saat ini, kita asumsikan 1 guru hanya wali kelas untuk 1 kelas.
        $gurus = Guru::whereDoesntHave('kelasWali')->orderBy('name')->get(); 
        // Jika Anda ingin menampilkan semua guru (termasuk yang sudah jadi wali kelas) dan nanti validasi di sisi server:
        // $gurus = Guru::orderBy('name')->get(); 
        
        $schoolProfile = SchoolProfile::firstOrCreate([]);

        return view('admin.kelas.create', compact('jurusans', 'gurus', 'schoolProfile'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jurusan_id' => 'required|exists:jurusans,id',
            'nama_kelas' => ['required', 'string', 'max:255', Rule::unique('kelas')->where(function ($query) use ($request) {
                return $query->where('jurusan_id', $request->jurusan_id);
            })],
            'tingkat' => 'required|integer|min:1',
            'wali_kelas_id' => 'nullable|exists:gurus,id', // Validasi wali_kelas_id
        ]);

        DB::beginTransaction(); // Mulai transaksi database

        try {
            $kelas = Kelas::create($request->all());

            // Jika wali_kelas_id diisi, update status is_wali_kelas guru tersebut
            if ($request->filled('wali_kelas_id')) {
                $guru = Guru::find($request->wali_kelas_id);
                if ($guru) {
                    $guru->is_wali_kelas = true;
                    $guru->save();
                }
            }

            DB::commit(); // Commit transaksi jika semua berhasil
            Log::info("Kelas created successfully: " . $request->nama_kelas . " with wali_kelas_id: " . ($request->wali_kelas_id ?? 'NULL'));
            return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaksi jika ada error
            Log::error("Error creating kelas: " . $e->getMessage());
            // Tambahkan pesan error yang lebih informatif dari validasi unique
            if (str_contains($e->getMessage(), 'Duplicate entry') && str_contains($e->getMessage(), 'kelas_jurusan_id_nama_kelas_unique')) {
                return redirect()->back()->withInput()->with('error', 'Kelas dengan nama dan jurusan yang sama sudah ada.');
            }
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan kelas. ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Kelas $kela) // Perhatikan nama parameter "kela" sesuai binding route
    {
        // Opsional: Untuk menampilkan detail kelas beserta siswanya
        $kela->load('jurusan', 'waliKelas', 'siswas'); // Load relasi yang diperlukan

        $schoolProfile = SchoolProfile::firstOrCreate([]);

        return view('admin.kelas.show', compact('kela', 'schoolProfile'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kelas $kela) // Perhatikan nama parameter "kela"
    {
        $jurusans = Jurusan::all();
        // Ambil semua guru. Jika Anda hanya ingin guru yang belum jadi wali kelas,
        // atau guru yang saat ini adalah wali kelas dari $kela ini.
        $gurus = Guru::orderBy('name')->get(); // Untuk edit, tampilkan semua guru agar bisa memilih dari yang sudah ada juga
        
        $schoolProfile = SchoolProfile::firstOrCreate([]);

        return view('admin.kelas.edit', compact('kela', 'jurusans', 'gurus', 'schoolProfile'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kelas $kela) // Perhatikan nama parameter "kela"
    {
        $request->validate([
            'jurusan_id' => 'required|exists:jurusans,id',
            'nama_kelas' => ['required', 'string', 'max:255', Rule::unique('kelas')->where(function ($query) use ($request) {
                return $query->where('jurusan_id', $request->jurusan_id);
            })->ignore($kela->id)],
            'tingkat' => 'required|integer|min:1',
            'wali_kelas_id' => 'nullable|exists:gurus,id',
        ]);

        DB::beginTransaction(); // Mulai transaksi database

        try {
            $oldWaliKelasId = $kela->wali_kelas_id; // Simpan ID wali kelas lama sebelum update

            $kela->update($request->all()); // Update data kelas

            // Logika untuk mengelola is_wali_kelas di tabel guru
            if ($oldWaliKelasId != $kela->wali_kelas_id) { // Jika wali kelas berubah
                // 1. Set is_wali_kelas guru lama menjadi false (jika tidak mengampu kelas lain)
                if ($oldWaliKelasId) {
                    $oldWaliKelas = Guru::find($oldWaliKelasId);
                    if ($oldWaliKelas) {
                        // Cek apakah guru lama ini masih menjadi wali kelas untuk kelas lain
                        if (Kelas::where('wali_kelas_id', $oldWaliKelas->id)->count() === 0) {
                            $oldWaliKelas->is_wali_kelas = false;
                            $oldWaliKelas->save();
                            Log::info("Guru lama (ID: {$oldWaliKelasId}) is_wali_kelas diset FALSE.");
                        } else {
                            Log::info("Guru lama (ID: {$oldWaliKelasId}) masih mengampu kelas lain, is_wali_kelas tetap TRUE.");
                        }
                    }
                }

                // 2. Set is_wali_kelas guru baru menjadi true (jika ada)
                if ($kela->wali_kelas_id) { // Jika ada wali kelas baru yang dipilih
                    $newWaliKelas = Guru::find($kela->wali_kelas_id);
                    if ($newWaliKelas) {
                        $newWaliKelas->is_wali_kelas = true;
                        $newWaliKelas->save();
                        Log::info("Guru baru (ID: {$kela->wali_kelas_id}) is_wali_kelas diset TRUE.");
                    }
                }
            }

            DB::commit(); // Commit transaksi jika semua berhasil
            Log::info("Kelas updated successfully: " . $kela->nama_kelas . " with new wali_kelas_id: " . ($kela->wali_kelas_id ?? 'NULL'));
            return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaksi jika ada error
            Log::error("Error updating kelas: " . $e->getMessage());
            if (str_contains($e->getMessage(), 'Duplicate entry') && str_contains($e->getMessage(), 'kelas_jurusan_id_nama_kelas_unique')) {
                return redirect()->back()->withInput()->with('error', 'Kelas dengan nama dan jurusan yang sama sudah ada.');
            }
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui kelas. ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kelas $kela) // Perhatikan nama parameter "kela"
    {
        DB::beginTransaction(); // Mulai transaksi untuk destroy

        try {
            // Cek apakah ada siswa yang terkait dengan kelas ini
            if ($kela->siswas()->count() > 0) {
                return redirect()->back()->with('error', 'Kelas tidak bisa dihapus karena memiliki siswa terkait.');
            }
            // Cek apakah ada assignment yang terkait
            if ($kela->assignments()->count() > 0) {
                return redirect()->back()->with('error', 'Kelas tidak bisa dihapus karena memiliki penugasan mata pelajaran terkait.');
            }
            // Cek apakah ada schedule yang terkait
            if ($kela->schedules()->count() > 0) {
                return redirect()->back()->with('error', 'Kelas tidak bisa dihapus karena memiliki jadwal pelajaran terkait.');
            }

            // Jika kelas memiliki wali kelas, set is_wali_kelas guru tersebut menjadi false
            // HANYA JIKA guru tersebut tidak lagi menjadi wali kelas untuk kelas lain
            if ($kela->wali_kelas_id) {
                $waliKelasGuru = Guru::find($kela->wali_kelas_id);
                if ($waliKelasGuru) {
                    // Cek apakah guru ini hanya wali kelas untuk kelas yang akan dihapus ini
                    if (Kelas::where('wali_kelas_id', $waliKelasGuru->id)->count() === 1 && Kelas::where('id', $kela->id)->where('wali_kelas_id', $waliKelasGuru->id)->exists()) {
                        $waliKelasGuru->is_wali_kelas = false;
                        $waliKelasGuru->save();
                        Log::info("Guru (ID: {$waliKelasGuru->id}) is_wali_kelas diset FALSE karena kelas terakhirnya dihapus.");
                    }
                }
            }

            $kela->delete();
            DB::commit(); // Commit transaksi
            Log::info("Kelas deleted successfully: " . $kela->nama_kelas);
            return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaksi
            Log::error("Error deleting kelas: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus kelas. ' . $e->getMessage());
        }
    }

    // Metode untuk mendapatkan kelas berdasarkan jurusan (opsional, untuk AJAX)
    public function getByJurusan(Request $request)
    {
        if ($request->ajax() && $request->has('jurusan_id')) {
            $kelas = Kelas::where('jurusan_id', $request->jurusan_id)->orderBy('tingkat')->orderBy('nama_kelas')->get();
            return response()->json($kelas);
        }
        return response()->json([]);
    }
}