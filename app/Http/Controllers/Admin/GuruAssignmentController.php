<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use App\Models\Semester;
use App\Models\Jurusan;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Models\SchoolProfile; 

class GuruAssignmentController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->query('search');
        $tahun_ajaran_id = $request->query('tahun_ajaran_id');
        $semester_id = $request->query('semester_id');

        $query = Assignment::with(['guru', 'kelas.jurusan', 'mataPelajaran', 'tahunAjaran', 'semester']);

        if ($search) {
            $query->whereHas('guru', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })->orWhereHas('kelas', function ($q) use ($search) {
                $q->where('nama_kelas', 'like', '%' . $search . '%');
            })->orWhereHas('mataPelajaran', function ($q) use ($search) {
                $q->where('nama_mapel', 'like', '%' . $search . '%')
                  ->orWhere('kode_mapel', 'like', '%' . $search . '%')
                  ->orWhere('kelompok', 'like', '%' . $search . '%');
            });
        }

        if ($tahun_ajaran_id) {
            $query->where('tahun_ajaran_id', $tahun_ajaran_id);
        }

        if ($semester_id) {
            $query->where('semester_id', $semester_id);
        }

        $assignments = $query->orderBy('created_at', 'desc')->paginate(10);

        $tahunAjarans = TahunAjaran::orderBy('nama', 'desc')->get();
        $semesters = Semester::orderBy('nama', 'asc')->get();

        $schoolProfile = SchoolProfile::firstOrCreate([]);

        return view('admin.guru-assignments.index', compact('assignments', 'search', 'tahunAjarans', 'semesters', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }


    public function create()
    {
        $gurus = Guru::orderBy('name')->get();
        $kelas = Kelas::orderBy('nama_kelas')->get();

        $kelompokMataPelajaran = [
            'Kelompok A (Muatan Umum)',
            'Kelompok B (Muatan Kewilayahan)',
            'Kelompok C1 (Dasar Bidang Keahlian)',
            'Kelompok C2 (Dasar Program Keahlian)',
            'Kelompok C3 (Paket Keahlian)',
            'Muatan Lokal',
        ];

        $mataPelajaran = MataPelajaran::orderBy('nama_mapel')->get(); 
        $tahunAjarans = TahunAjaran::orderBy('nama', 'desc')->get();
        $semesters = Semester::orderBy('nama', 'asc')->get();
        $jurusans = Jurusan::orderBy('nama_jurusan')->get();

        $schoolProfile = SchoolProfile::firstOrCreate([]);


        return view('admin.guru-assignments.create', compact('gurus', 'kelas', 'mataPelajaran', 'kelompokMataPelajaran', 'tahunAjarans', 'semesters', 'jurusans', 'schoolProfile'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'guru_id' => 'required|exists:gurus,id',
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id', 
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
            'semester_id' => 'required|exists:semesters,id',
            'tipe_mengajar' => ['required', Rule::in(['Praktikum', 'Teori', 'Teori & Praktikum'])],
            'status_konfirmasi' => ['required', Rule::in(['Pending', 'Dikonfirmasi', 'Ditolak'])],
        ]);

        try {
            $existingAssignment = Assignment::where('guru_id', $request->guru_id)
                ->where('kelas_id', $request->kelas_id)
                ->where('mata_pelajaran_id', $request->mata_pelajaran_id)
                ->where('tahun_ajaran_id', $request->tahun_ajaran_id)
                ->where('semester_id', $request->semester_id)
                ->first();

            if ($existingAssignment) {
                return redirect()->back()->with('error', 'Penugasan dengan kombinasi Guru, Kelas, Mata Pelajaran, Tahun Ajaran, dan Semester yang sama sudah ada.');
            }

            Assignment::create($request->all());

            return redirect()->route('admin.guru-assignments.index')->with('success', 'Penugasan mengajar berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error adding guru assignment: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan penugasan mengajar: ' . $e->getMessage());
        }
    }

    public function edit(Assignment $assignment)
    {
        $gurus = Guru::orderBy('name')->get();
        $kelas = Kelas::orderBy('nama_kelas')->get();

        $kelompokMataPelajaran = [
            'Kelompok A (Muatan Umum)',
            'Kelompok B (Muatan Kewilayahan)',
            'Kelompok C1 (Dasar Bidang Keahlian)',
            'Kelompok C2 (Dasar Program Keahlian)',
            'Kelompok C3 (Paket Keahlian)',
            'Muatan Lokal',
        ];
        
        $mataPelajaran = MataPelajaran::orderBy('nama_mapel')->get(); 
        $tahunAjarans = TahunAjaran::orderBy('nama', 'desc')->get();
        $semesters = Semester::orderBy('nama', 'asc')->get();
        $jurusans = Jurusan::orderBy('nama_jurusan')->get();

        $schoolProfile = SchoolProfile::firstOrCreate([]);


        return view('admin.guru-assignments.edit', compact('assignment', 'gurus', 'kelas', 'mataPelajaran', 'kelompokMataPelajaran', 'tahunAjarans', 'semesters', 'jurusans', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

 
    public function update(Request $request, Assignment $assignment)
    {
        $request->validate([
            'guru_id' => 'required|exists:gurus,id',
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id', 
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
            'semester_id' => 'required|exists:semesters,id',
            'tipe_mengajar' => ['required', Rule::in(['Praktikum', 'Teori'])],
            'status_konfirmasi' => ['required', Rule::in(['Pending', 'Dikonfirmasi', 'Ditolak'])],
        ]);

        try {
            $existingAssignment = Assignment::where('guru_id', $request->guru_id)
                ->where('kelas_id', $request->kelas_id)
                ->where('mata_pelajaran_id', $request->mata_pelajaran_id)
                ->where('tahun_ajaran_id', $request->tahun_ajaran_id)
                ->where('semester_id', $request->semester_id)
                ->where('id', '!=', $assignment->id)
                ->first();

            if ($existingAssignment) {
                return redirect()->back()->with('error', 'Penugasan dengan kombinasi Guru, Kelas, Mata Pelajaran, Tahun Ajaran, dan Semester yang sama sudah ada.');
            }

            $assignment->update($request->all());

            return redirect()->route('admin.guru-assignments.index')->with('success', 'Penugasan mengajar berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating guru assignment: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui penugasan mengajar: ' . $e->getMessage());
        }
    }

    public function destroy(Assignment $assignment)
    {
        try {
            $assignment->delete();
            return redirect()->route('admin.guru-assignments.index')->with('success', 'Penugasan mengajar berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error deleting guru assignment: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus penugasan mengajar: ' . $e->getMessage());
        }
    }

    public function show(Assignment $assignment)
    {

        $schoolProfile = SchoolProfile::firstOrCreate([]);
        return view('admin.guru-assignments.show', compact('assignment', 'schoolProfile'));
    }

    public function getMataPelajaranByKelompok(Request $request)
    {
        try {
            $kelompok = $request->input('kelompok');
            
            if ($kelompok === null || $kelompok === '') {
                $mataPelajaran = MataPelajaran::orderBy('nama_mapel')->get();
            } else {
                $mataPelajaran = MataPelajaran::where('kelompok', $kelompok)
                                              ->orderBy('nama_mapel')
                                              ->get();
            }
            return response()->json($mataPelajaran);
        } catch (\Exception $e) {
            Log::error("Error in getMataPelajaranByKelompok: " . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error: ' . $e->getMessage()], 500);
        }
    }
}