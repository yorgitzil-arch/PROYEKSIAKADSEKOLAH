<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Models\SchoolProfile; // <--- TAMBAHKAN INI

class SemesterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            // Load semesters with their associated TahunAjaran
            $semesters = Semester::with('tahunAjaran')->orderBy('tahun_ajaran_id', 'desc')->orderBy('nama')->paginate(10);
            
            // --- Tambahkan ini untuk $schoolProfile ---
            $schoolProfile = SchoolProfile::firstOrCreate([]);
            // -----------------------------------------

            return view('admin.semester.index', compact('semesters', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
        } catch (\Exception $e) {
            Log::error("Error retrieving semesters: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat data Semester.');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tahunAjarans = TahunAjaran::orderBy('nama', 'desc')->get();
        
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.semester.create', compact('tahunAjarans', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'tahun_ajaran_id' => ['required', 'exists:tahun_ajarans,id'],
            'nama' => [ // KOREKSI: Validasi menggunakan 'nama'
                'required',
                'string',
                'max:255',
                Rule::unique('semesters')->where(function ($query) use ($request) {
                    return $query->where('tahun_ajaran_id', $request->tahun_ajaran_id);
                }),
            ],
            'is_active' => 'nullable|boolean',
        ]);

        try {
            $data = $request->all();
            // KOREKSI: Pastikan is_active diset dengan benar dari request (true/false)
            $data['is_active'] = $request->has('is_active') ? true : false;

            // Hanya boleh ada satu semester yang aktif pada tahun ajaran yang sama
            if ($data['is_active']) {
                // Pastikan tahun ajaran yang dipilih juga aktif
                $tahunAjaranAktif = TahunAjaran::find($data['tahun_ajaran_id']);
                if (!$tahunAjaranAktif || !$tahunAjaranAktif->is_active) {
                    return redirect()->back()->withInput()->with('error', 'Tidak bisa mengaktifkan semester jika Tahun Ajaran tidak aktif.');
                }
                // KOREKSI: Menonaktifkan semester lain HANYA di tahun ajaran yang sama
                Semester::where('tahun_ajaran_id', $data['tahun_ajaran_id'])
                        ->update(['is_active' => false]);
            }

            Semester::create($data); // Data sekarang akan memiliki 'nama' dan 'is_active' yang benar
            Log::info("Semester created successfully: Tahun Ajaran ID " . $data['tahun_ajaran_id'] . ", Nama: " . $data['nama']);
            return redirect()->route('admin.semester.index')->with('success', 'Semester berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error("Error creating semester: " . $e->getMessage());
            // Tambahkan pesan error yang lebih informatif dari validasi unique
            if (str_contains($e->getMessage(), 'Duplicate entry') && str_contains($e->getMessage(), 'semesters_tahun_ajaran_id_nama_unique')) {
                   return redirect()->back()->withInput()->with('error', 'Semester dengan nama dan tahun ajaran yang sama sudah ada.');
            }
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Semester. ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Semester  $semester
     * @return \Illuminate\Http\Response
     */
    public function show(Semester $semester)
    {
        // Opsional
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.semester.show', compact('semester', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Semester  $semester
     * @return \Illuminate\Http\Response
     */
    public function edit(Semester $semester)
    {
        $tahunAjarans = TahunAjaran::orderBy('nama', 'desc')->get();
        
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.semester.edit', compact('semester', 'tahunAjarans', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Semester  $semester
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Semester $semester)
    {
        $request->validate([
            'tahun_ajaran_id' => ['required', 'exists:tahun_ajarans,id'],
            'nama' => [ // KOREKSI: Validasi menggunakan 'nama'
                'required',
                'string',
                'max:255',
                Rule::unique('semesters')->where(function ($query) use ($request) {
                    return $query->where('tahun_ajaran_id', $request->tahun_ajaran_id);
                })->ignore($semester->id),
            ],
            'is_active' => 'nullable|boolean',
        ]);

        try {
            $data = $request->all();
            $data['is_active'] = $request->has('is_active') ? true : false; // KOREKSI: Tangani checkbox

            if ($data['is_active']) {
                $tahunAjaranAktif = TahunAjaran::find($data['tahun_ajaran_id']);
                if (!$tahunAjaranAktif || !$tahunAjaranAktif->is_active) {
                    return redirect()->back()->withInput()->with('error', 'Tidak bisa mengaktifkan semester jika Tahun Ajaran tidak aktif.');
                }
                // KOREKSI: Menonaktifkan semester lain HANYA di tahun ajaran yang sama
                Semester::where('tahun_ajaran_id', $data['tahun_ajaran_id'])
                        ->where('id', '!=', $semester->id)
                        ->update(['is_active' => false]);
            }

            $semester->update($data);
            Log::info("Semester updated successfully: Tahun Ajaran ID " . $semester->tahun_ajaran_id . ", Nama: " . $semester->nama);
            return redirect()->route('admin.semester.index')->with('success', 'Semester berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error("Error updating semester: " . $e->getMessage());
             if (str_contains($e->getMessage(), 'Duplicate entry') && str_contains($e->getMessage(), 'semesters_tahun_ajaran_id_nama_unique')) {
                   return redirect()->back()->withInput()->with('error', 'Semester dengan nama dan tahun ajaran yang sama sudah ada.');
            }
            return redirect()->back()->with('error', 'Gagal memperbarui Semester. ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Semester  $semester
     * @return \Illuminate\Http\Response
     */
    public function destroy(Semester $semester)
    {
        try {
            // KOREKSI: Pastikan relasi di model Semester sudah benar (misal: hasMany di Semester, belongsTo di model terkait)
            // Cek apakah ada assignment, nilai, atau presensi yang terkait
            if ($semester->assignments()->count() > 0 ||
                $semester->nilaiAkademik()->count() > 0 ||
                $semester->nilaiKeterampilan()->count() > 0 ||
                $semester->nilaiSikap()->count() > 0 ||
                $semester->attendances()->count() > 0 ||
                $semester->presensiAkhir()->count() > 0 ||
                $semester->catatanWaliKelas()->count() > 0 ||
                $semester->rekapNilaiMapel()->count() > 0 ||
                $semester->ekstrakurikulerSiswa()->count() > 0 ||
                $semester->raports()->count() > 0)
            {
                return redirect()->back()->with('error', 'Semester tidak bisa dihapus karena memiliki data terkait (Penugasan, Nilai, atau Presensi).');
            }

            if ($semester->is_active) {
                return redirect()->back()->with('error', 'Semester aktif tidak bisa dihapus.');
            }

            $semester->delete();
            Log::info("Semester deleted successfully: " . $semester->nama);
            return redirect()->route('admin.semester.index')->with('success', 'Semester berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error("Error deleting semester: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus Semester. ' . $e->getMessage());
        }
    }

    /**
     * Toggle the active status of a semester.
     *
     * @param  \App\Models\Semester  $semester
     * @return \Illuminate\Http\Response
     */
    public function toggleActive(Semester $semester)
    {
        try {
            // Pastikan tahun ajaran yang dipilih juga aktif
            $tahunAjaranAktif = TahunAjaran::find($semester->tahun_ajaran_id);
            if (!$tahunAjaranAktif || !$tahunAjaranAktif->is_active) {
                return redirect()->back()->with('error', 'Tidak bisa mengaktifkan semester jika Tahun Ajaran tidak aktif.');
            }

            // Menonaktifkan semua semester lain di tahun ajaran yang sama
            Semester::where('tahun_ajaran_id', $semester->tahun_ajaran_id)
                    ->where('id', '!=', $semester->id)
                    ->update(['is_active' => false]);

            // Mengaktifkan semester yang dipilih
            $semester->update(['is_active' => true]);

            return redirect()->back()->with('success', 'Status semester berhasil diubah menjadi aktif.');
        } catch (\Exception $e) {
            Log::error("Error toggling semester active status: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengubah status semester: ' . $e->getMessage());
        }
    }
}