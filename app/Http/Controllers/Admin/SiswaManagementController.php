<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Models\SchoolProfile; // <--- TAMBAHKAN INI

class SiswaManagementController extends Controller
{
    /**
     * Menampilkan daftar semua akun siswa.
     * Admin dapat mencari berdasarkan nama, NIS, NISN, atau kelas.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $siswas = Siswa::query()
            ->with('kelas');
        if ($search) {
            $siswas->where('name', 'like', '%' . $search . '%')
                ->orWhere('nis', 'like', '%' . $search . '%')
                ->orWhere('nisn', 'like', '%' . $search . '%') // NISN sudah termasuk dalam pencarian
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhereHas('kelas', function ($query) use ($search) {
                    $query->where('nama_kelas', 'like', '%' . $search . '%');
                });
        }

        $siswas = $siswas->orderBy('name')->paginate(10);

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.siswa-management.index', compact('siswas', 'search', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menampilkan formulir untuk membuat akun siswa baru.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.siswa-management.create', compact('kelas', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menyimpan akun siswa baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'nis' => 'required|string|max:20|unique:siswas,nis',
                'nisn' => 'nullable|string|max:255|unique:siswas,nisn', // Validasi NISN sudah benar
                'email' => 'nullable|string|email|max:255|unique:siswas,email',
                'password' => 'required|string|min:8|confirmed',
                'kelas_id' => 'required|exists:kelas,id',
            ]);

            Siswa::create([
                'name' => $request->name,
                'nis' => $request->nis,
                'nisn' => $request->nisn, // NISN sudah disimpan
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'kelas_id' => $request->kelas_id,
            ]);

            Log::info('Akun Siswa baru ditambahkan: ' . $request->nis);
            return redirect()->route('admin.siswa-management.index')->with('success', 'Akun Siswa berhasil ditambahkan!');

        } catch (\Exception $e) {
            Log::error('Gagal menambahkan akun Siswa: ' . $e->getMessage(), $request->all());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan akun Siswa: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan formulir untuk mengedit akun siswa yang ada.
     *
     * @param  \App\Models\Siswa  $siswa
     * @return \Illuminate\Http\Response
     */
    public function edit(Siswa $siswa)
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        Log::info('Mengakses halaman edit siswa untuk ID: ' . $siswa->id);
        
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.siswa-management.edit', compact('siswa', 'kelas', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Memperbarui akun siswa yang ada di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Siswa  $siswa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Siswa $siswa)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'nis' => [
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('siswas', 'nis')->ignore($siswa->id),
                ],
                'nisn' => [ // Validasi NISN sudah benar dengan pengecualian unique
                    'nullable',
                    'string',
                    'max:255',
                    Rule::unique('siswas', 'nisn')->ignore($siswa->id),
                ],
                'email' => [
                    'nullable',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('siswas', 'email')->ignore($siswa->id),
                ],
                'password' => 'nullable|string|min:8|confirmed',
                'kelas_id' => 'required|exists:kelas,id',
            ]);

            $siswa->name = $request->name;
            $siswa->nis = $request->nis;
            $siswa->nisn = $request->nisn; // NISN sudah diperbarui
            $siswa->email = $request->email;
            $siswa->kelas_id = $request->kelas_id;

            if ($request->filled('password')) {
                $siswa->password = Hash::make($request->password);
            }

            $siswa->save();

            Log::info('Akun Siswa diperbarui: ' . $siswa->nis);
            return redirect()->route('admin.siswa-management.index')->with('success', 'Akun Siswa berhasil diperbarui!');

        } catch (\Exception $e) {
            Log::error('Gagal memperbarui akun Siswa: ' . $e->getMessage(), ['siswa_id' => $siswa->id, 'request_data' => $request->all()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui akun Siswa: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menghapus akun siswa dari database.
     *
     * @param  \App\Models\Siswa  $siswa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Siswa $siswa)
    {
        try {
            $siswa->delete(); // Ini hanya menghapus akun, bukan data terkait lainnya.
            Log::info('Akun Siswa berhasil dihapus: ' . $siswa->nis);
            return redirect()->route('admin.siswa-management.index')->with('success', 'Akun Siswa berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus siswa: ' . $e->getMessage(), ['siswa_id' => $siswa->id]);
            return redirect()->route('admin.siswa-management.index')->with('error', 'Terjadi kesalahan saat menghapus akun Siswa: ' . $e->getMessage());
        }
    }
}