<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\Guru;
use App\Models\NilaiAkademik;
use App\Models\NilaiKeterampilan;
use App\Models\NilaiSikap;
use App\Models\Attendance;
use App\Models\AssignmentSubmission;
use App\Models\EkstrakurikulerSiswa;
use App\Models\Raport;
use App\Models\RekapNilaiMapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\SchoolProfile; // <--- TAMBAHKAN INI

class StudentDataController extends Controller
{
    /**
     * Menampilkan daftar semua siswa yang terdaftar.
     * Admin dapat mencari, memfilter berdasarkan status, kelas, jurusan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $statusFilter = $request->query('status');
        $kelasFilter = $request->query('kelas_id');
        $jurusanFilter = $request->query('jurusan_id');

        $siswas = Siswa::with(['jurusan', 'kelas', 'waliKelas'])
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('nis', 'like', '%' . $search . '%')
                      ->orWhere('nisn', 'like', '%' . $search . '%') // Tambahkan pencarian berdasarkan NISN
                      ->orWhere('email', 'like', '%' . $search . '%');
            })
            ->when($statusFilter, function ($query, $statusFilter) {
                $query->where('status', $statusFilter);
            })
            ->when($kelasFilter, function ($query, $kelasFilter) {
                $query->where('kelas_id', $kelasFilter);
            })
            ->when($jurusanFilter, function ($query, $jurusanFilter) {
                $query->where('jurusan_id', $jurusanFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $kelas = Kelas::orderBy('nama_kelas')->get();
        $jurusans = Jurusan::orderBy('nama_jurusan')->get();

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.siswa-management.data_siswa.index', compact('siswas', 'search', 'statusFilter', 'kelasFilter', 'jurusanFilter', 'kelas', 'jurusans', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menampilkan formulir untuk membuat data siswa baru.
     * CATATAN: Metode ini mungkin tidak lagi digunakan jika Akun Siswa dikelola terpisah.
     * Namun, jika Anda ingin memungkinkan pembuatan data detail dari sini, biarkan saja.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        $jurusans = Jurusan::orderBy('nama_jurusan')->get();
        $gurus = Guru::orderBy('name')->get();
        
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.siswa-management.data_siswa.create', compact('kelas', 'jurusans', 'gurus', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menyimpan data siswa baru ke database.
     * CATATAN: Metode ini mungkin tidak lagi digunakan jika Akun Siswa dikelola terpisah.
     * Namun, jika Anda ingin memungkinkan penyimpanan data detail dari sini, biarkan saja.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|string|max:255|unique:siswas,nis',
            'nisn' => 'nullable|string|max:255|unique:siswas,nisn', // Tambahkan validasi NISN
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:siswas,email',
            'password' => 'required|string|min:8|confirmed',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'agama' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'nomor_telepon' => 'nullable|string|max:20',
            'nama_ayah' => 'nullable|string|max:255',
            'pekerjaan_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'pekerjaan_ibu' => 'nullable|string|max:255',
            'jurusan_id' => 'required|exists:jurusans,id',
            'kelas_id' => 'required|exists:kelas,id',
            'wali_kelas_id' => 'nullable|exists:gurus,id',
            'status' => 'required|in:pending,confirmed',
            'foto_profile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ijazah' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'raport' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'kk' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'ktp_ortu' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'akta_lahir' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'sk_lulus' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'kis' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'kks' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except(['_token', 'password_confirmation', 'foto_profile', 'ijazah', 'raport', 'kk', 'ktp_ortu', 'akta_lahir', 'sk_lulus', 'kis', 'kks']);

        $data['password'] = Hash::make($request->password);

        $documentFields = [
            'foto_profile' => 'foto_profile_path',
            'ijazah' => 'ijazah_path',
            'raport' => 'raport_path',
            'kk' => 'kk_path',
            'ktp_ortu' => 'ktp_ortu_path',
            'akta_lahir' => 'akta_lahir_path',
            'sk_lulus' => 'sk_lulus_path',
            'kis' => 'kis_path',
            'kks' => 'kks_path',
        ];

        foreach ($documentFields as $inputName => $columnName) {
            if ($request->hasFile($inputName)) {
                $data[$columnName] = $request->file($inputName)->store('siswa_documents/' . $request->nis, 'public');
            }
        }

        Siswa::create($data);
        Log::info("Siswa baru berhasil dibuat: " . $request->name . " (" . $request->nis . ")");

        return redirect()->route('admin.student-data.index')->with('success', 'Data siswa berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail lengkap data siswa.
     *
     * @param  \App\Models\Siswa  $siswa
     * @return \Illuminate\Http\Response
     */
    public function show(Siswa $siswa)
    {
        $siswa->load(['jurusan', 'kelas', 'waliKelas']);
        $documents = [
            'ijazah' => 'Ijazah',
            'raport' => 'Raport',
            'kk' => 'Kartu Keluarga (KK)',
            'ktp_ortu' => 'KTP Orang Tua',
            'akta_lahir' => 'Akta Lahir',
            'sk_lulus' => 'Surat Keterangan Lulus (SKL)',
            'kis' => 'Kartu Indonesia Sehat (KIS)',
            'kks' => 'Kartu Keluarga Sejahtera (KKS) / Bantuan Sosial',
        ];

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.siswa-management.data_siswa.show', compact('siswa', 'documents', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menampilkan formulir untuk mengedit/mengkonfirmasi data siswa.
     * Admin dapat mengatur kelas, jurusan, dan wali kelas, serta data pribadi.
     *
     * @param  \App\Models\Siswa  $siswa
     * @return \Illuminate\Http\Response
     */
    public function edit(Siswa $siswa)
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        $jurusans = Jurusan::orderBy('nama_jurusan')->get();
        $gurus = Guru::orderBy('name')->get();
        
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.siswa-management.data_siswa.edit', compact('siswa', 'kelas', 'jurusans', 'gurus', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Memperbarui data siswa.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Siswa  $siswa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nis' => ['required', 'string', 'max:255', Rule::unique('siswas')->ignore($siswa->id)],
            'nisn' => ['nullable', 'string', 'max:255', Rule::unique('siswas', 'nisn')->ignore($siswa->id)], // Tambahkan validasi NISN
            'name' => 'required|string|max:255',
            'email' => ['nullable', 'email', 'max:255', Rule::unique('siswas')->ignore($siswa->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'agama' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'nomor_telepon' => 'nullable|string|max:20',
            'nama_ayah' => 'nullable|string|max:255',
            'pekerjaan_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'pekerjaan_ibu' => 'nullable|string|max:255',
            'jurusan_id' => 'required|exists:jurusans,id',
            'kelas_id' => 'required|exists:kelas,id',
            'wali_kelas_id' => 'nullable|exists:gurus,id',
            'status' => 'required|in:pending,confirmed',
            'foto_profile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ijazah' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'raport' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'kk' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'ktp_ortu' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'akta_lahir' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'sk_lulus' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'kis' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'kks' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except(['_token', '_method', 'password', 'password_confirmation', 'foto_profile', 'ijazah', 'raport', 'kk', 'ktp_ortu', 'akta_lahir', 'sk_lulus', 'kis', 'kks']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $documentFields = [
            'foto_profile' => 'foto_profile_path',
            'ijazah' => 'ijazah_path',
            'raport' => 'raport_path',
            'kk' => 'kk_path',
            'ktp_ortu' => 'ktp_ortu_path',
            'akta_lahir' => 'akta_lahir_path',
            'sk_lulus' => 'sk_lulus_path',
            'kis' => 'kis_path',
            'kks' => 'kks_path',
        ];

        foreach ($documentFields as $inputName => $columnName) {
            if ($request->hasFile($inputName)) {
                if ($siswa->$columnName && Storage::disk('public')->exists($siswa->$columnName)) {
                    Storage::disk('public')->delete($siswa->$columnName);
                }
                $data[$columnName] = $request->file($inputName)->store('siswa_documents/' . $siswa->nis, 'public');
            }
        }

        $siswa->update($data);
        Log::info("Data siswa berhasil diperbarui: " . $siswa->name . " (" . $siswa->nis . ")");

        return redirect()->route('admin.student-data.index')->with('success', 'Data siswa berhasil diperbarui!');
    }

    /**
     * Menghapus siswa dari database.
     *
     * @param  \App\Models\Siswa  $siswa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Siswa $siswa)
    {
        try {
            // Periksa relasi nilai yang lebih spesifik
            if ($siswa->nilaiAkademik()->count() > 0 ||
                $siswa->nilaiKeterampilan()->count() > 0 ||
                $siswa->nilaiSikap()->count() > 0) {
                return redirect()->back()->with('error', 'Siswa tidak dapat dihapus karena memiliki data nilai terkait (Akademik, Keterampilan, atau Sikap).');
            }
            // Relasi lainnya yang sudah ada di controller Anda
            if ($siswa->attendances()->count() > 0) {
                return redirect()->back()->with('error', 'Siswa tidak dapat dihapus karena memiliki data presensi terkait.');
            }
            if ($siswa->assignmentSubmissions()->count() > 0) {
                return redirect()->back()->with('error', 'Siswa tidak dapat dihapus karena memiliki data pengumpulan tugas terkait.');
            }
            if ($siswa->rekapNilaiMapel()->count() > 0) {
                return redirect()->back()->with('error', 'Siswa tidak dapat dihapus karena memiliki data rekap nilai mata pelajaran terkait.');
            }
            if ($siswa->ekstrakurikulerSiswa()->count() > 0) {
                return redirect()->back()->with('error', 'Siswa tidak dapat dihapus karena memiliki data ekstrakurikuler terkait.');
            }
            if ($siswa->raports()->count() > 0) {
                return redirect()->back()->with('error', 'Siswa tidak dapat dihapus karena memiliki data raport terkait.');
            }

            $documentPaths = [
                $siswa->foto_profile_path, $siswa->ijazah_path, $siswa->raport_path,
                $siswa->kk_path, $siswa->ktp_ortu_path, $siswa->akta_lahir_path,
                $siswa->sk_lulus_path, $siswa->kis_path, $siswa->kks_path,
            ];

            foreach ($documentPaths as $path) {
                if ($path && Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            $siswa->delete();
            Log::info("Siswa berhasil dihapus: " . $siswa->name . " (" . $siswa->nis . ")");
            return redirect()->route('admin.student-data.index')->with('success', 'Siswa berhasil dihapus beserta semua dokumennya!');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus siswa: ' . $e->getMessage(), ['siswa_id' => $siswa->id]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus siswa: ' . $e->getMessage());
        }
    }

    /**
     * Mengunduh dokumen siswa.
     *
     * @param  \App\Models\Siswa  $siswa
     * @param  string  $documentType
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function downloadDocument(Siswa $siswa, $documentType)
    {
        $pathColumn = $documentType . '_path';

        if (!property_exists($siswa, $pathColumn) || empty($siswa->$pathColumn)) {
            return redirect()->back()->with('error', 'Dokumen tidak ditemukan atau belum diunggah.');
        }

        $filePath = $siswa->$pathColumn;

        if (Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->download($filePath);
        }

        return redirect()->back()->with('error', 'File dokumen tidak ditemukan di penyimpanan.');
    }
}