<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\TeachingMaterial;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\Assignment; // Untuk mengambil kelas dan mapel yang diampu guru
use Illuminate\Support\Facades\Log;
use App\Models\SchoolProfile; // <--- TAMBAHKAN INI

class TeachingMaterialController extends Controller
{
    /**
     * Menampilkan daftar buku mengajar yang diunggah oleh guru yang sedang login.
     */
    public function index()
    {
        $guruId = Auth::guard('guru')->id();
        $teachingMaterials = TeachingMaterial::where('guru_id', $guruId)
            ->with(['mataPelajaran', 'kelas'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('guru.teaching-materials.index', compact('teachingMaterials', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menampilkan form untuk membuat buku mengajar baru.
     */
    public function create()
    {
        $guru = Auth::guard('guru')->user();

        // Ambil mata pelajaran dan kelas yang diampu oleh guru ini dari assignments yang sudah dikonfirmasi
        $assignments = $guru->assignments()
            ->where('status_konfirmasi', 'Dikonfirmasi')
            ->with(['mataPelajaran', 'kelas'])
            ->get();

        $mataPelajaranOptions = $assignments->pluck('mataPelajaran.nama_mapel', 'mata_pelajaran_id')->unique();
        $kelasOptions = $assignments->pluck('kelas.nama_kelas', 'kelas_id')->unique();

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('guru.teaching-materials.create', compact('mataPelajaranOptions', 'kelasOptions', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menyimpan buku mengajar baru.
     */
    public function store(Request $request)
    {
        $guruId = Auth::guard('guru')->id();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'kelas_id' => 'required|exists:kelas,id',
            'file_path' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar|max:20480', // Max 20MB
        ]);

        $filePath = $request->file('file_path')->store('teaching_materials', 'public');

        TeachingMaterial::create([
            'guru_id' => $guruId,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'kelas_id' => $request->kelas_id,
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
        ]);

        return redirect()->route('guru.teaching-materials.index')->with('success', 'Buku mengajar berhasil diunggah!');
    }

    /**
     * Menampilkan detail buku mengajar.
     */
    public function show(TeachingMaterial $teachingMaterial)
    {
        if ($teachingMaterial->guru_id !== Auth::guard('guru')->id()) {
            return redirect()->route('guru.teaching-materials.index')->with('error', 'Anda tidak memiliki izin untuk melihat materi ini.');
        }

        $teachingMaterial->load(['mataPelajaran', 'kelas', 'guru']);
        
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('guru.teaching-materials.show', compact('teachingMaterial', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menampilkan form untuk mengedit buku mengajar.
     */
    public function edit(TeachingMaterial $teachingMaterial)
    {
        if ($teachingMaterial->guru_id !== Auth::guard('guru')->id()) {
            return redirect()->route('guru.teaching-materials.index')->with('error', 'Anda tidak memiliki izin untuk mengedit materi ini.');
        }

        $guru = Auth::guard('guru')->user();
        $assignments = $guru->assignments()
            ->where('status_konfirmasi', 'Dikonfirmasi')
            ->with(['mataPelajaran', 'kelas'])
            ->get();

        $mataPelajaranOptions = $assignments->pluck('mataPelajaran.nama_mapel', 'mata_pelajaran_id')->unique();
        $kelasOptions = $assignments->pluck('kelas.nama_kelas', 'kelas_id')->unique();

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('guru.teaching-materials.edit', compact('teachingMaterial', 'mataPelajaranOptions', 'kelasOptions', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Memperbarui buku mengajar.
     */
    public function update(Request $request, TeachingMaterial $teachingMaterial)
    {
        if ($teachingMaterial->guru_id !== Auth::guard('guru')->id()) {
            return redirect()->route('guru.teaching-materials.index')->with('error', 'Anda tidak memiliki izin untuk memperbarui materi ini.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'kelas_id' => 'required|exists:kelas,id',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar|max:20480', // Ubah dari 'required' ke 'nullable'
            'remove_file' => 'nullable|boolean', // Tambahkan validasi untuk checkbox hapus file
        ]);

        $filePath = $teachingMaterial->file_path;

        if ($request->hasFile('file_path')) {
            // Hapus file lama jika ada
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            $filePath = $request->file('file_path')->store('teaching_materials', 'public');
        } elseif ($request->boolean('remove_file')) { // Jika checkbox 'Hapus file saat ini' dicentang
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            $filePath = null;
        }

        $teachingMaterial->update([
            'title' => $request->title,
            'description' => $request->description,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'kelas_id' => $request->kelas_id,
            'file_path' => $filePath,
        ]);

        return redirect()->route('guru.teaching-materials.index')->with('success', 'Buku mengajar berhasil diperbarui!');
    }

    /**
     * Menghapus buku mengajar.
     */
    public function destroy(TeachingMaterial $teachingMaterial)
    {
        if ($teachingMaterial->guru_id !== Auth::guard('guru')->id()) {
            return redirect()->route('guru.teaching-materials.index')->with('error', 'Anda tidak memiliki izin untuk menghapus materi ini.');
        }

        if ($teachingMaterial->file_path && Storage::disk('public')->exists($teachingMaterial->file_path)) {
            Storage::disk('public')->delete($teachingMaterial->file_path);
        }

        $teachingMaterial->delete();

        return redirect()->route('guru.teaching-materials.index')->with('success', 'Buku mengajar berhasil dihapus!');
    }

    /**
     * Mengunduh file buku mengajar.
     */
    public function download(TeachingMaterial $teachingMaterial)
    {
        if ($teachingMaterial->file_path && Storage::disk('public')->exists($teachingMaterial->file_path)) {
            return Storage::disk('public')->download($teachingMaterial->file_path, $teachingMaterial->title . '.' . pathinfo($teachingMaterial->file_path, PATHINFO_EXTENSION));
        }

        return redirect()->back()->with('error', 'File buku mengajar tidak ditemukan.');
    }
}