<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas; // Tambahkan ini untuk akses ke model Kelas
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // Tambahkan ini untuk transaksi database
use App\Models\SchoolProfile;

class GuruManagementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $gurus = Guru::query();

        if ($search) {
            $gurus->where('name', 'like', '%' . $search . '%')
                ->orWhere('nip', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
        }

        $gurus = $gurus->orderBy('name')->paginate(10);

        $schoolProfile = SchoolProfile::firstOrCreate([]);

        return view('admin.guru-management.index', compact('gurus', 'search', 'schoolProfile'));
    }

    public function create()
    {
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        return view('admin.guru-management.create', compact('schoolProfile'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction(); // Mulai transaksi database

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'nip' => 'required|string|max:20|unique:gurus,nip',
                'kategori' => ['required', Rule::in(['PNS', 'Non-PNS'])],
                'email' => 'nullable|string|email|max:255|unique:gurus,email',
                'password' => 'required|string|min:8|confirmed',
                'is_wali_kelas' => 'sometimes|boolean', // Tambahkan validasi ini
            ]);

            $guru = Guru::create([
                'name' => $request->name,
                'nip' => $request->nip,
                'kategori' => $request->kategori,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                // Ambil nilai is_wali_kelas dari request, default false jika tidak ada (checkbox tidak dicentang)
                'is_wali_kelas' => $request->boolean('is_wali_kelas'),
            ]);

            DB::commit(); // Commit transaksi jika berhasil
            Log::info('Akun Guru baru ditambahkan: ' . $request->nip . ' (Wali Kelas: ' . ($guru->is_wali_kelas ? 'Ya' : 'Tidak') . ')');
            return redirect()->route('admin.guru-management.index')->with('success', 'Akun Guru berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaksi jika ada error
            Log::error('Gagal menambahkan akun Guru: ' . $e->getMessage(), $request->all());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan akun Guru: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Guru $guru)
    {
        Log::info('Mengakses halaman edit guru untuk ID: ' . $guru->id);
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        return view('admin.guru-management.edit', compact('guru', 'schoolProfile'));
    }

    public function update(Request $request, Guru $guru)
    {
        DB::beginTransaction(); // Mulai transaksi database

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'nip' => [
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('gurus', 'nip')->ignore($guru->id),
                ],
                'kategori' => ['required', Rule::in(['PNS', 'Non-PNS'])],
                'email' => [
                    'nullable',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('gurus', 'email')->ignore($guru->id),
                ],
                'password' => 'nullable|string|min:8|confirmed',
                'is_wali_kelas' => 'sometimes|boolean', // Tambahkan validasi ini
            ]);

            $oldIsWaliKelas = $guru->is_wali_kelas; // Simpan status lama
            $newIsWaliKelas = $request->boolean('is_wali_kelas'); // Ambil status baru dari request

            $guru->name = $request->name;
            $guru->nip = $request->nip;
            $guru->kategori = $request->kategori;
            $guru->email = $request->email;
            $guru->is_wali_kelas = $newIsWaliKelas; // Set status is_wali_kelas

            if ($request->filled('password')) {
                $guru->password = Hash::make($request->password);
            }

            $guru->save(); // Simpan perubahan pada guru

            // Logika penting: Jika status wali kelas berubah dari TRUE ke FALSE
            if ($oldIsWaliKelas && !$newIsWaliKelas) {
                // Cek apakah guru ini mengampu kelas (menggunakan relasi hasOne: kelasWali)
                if ($guru->kelasWali) {
                    // Set wali_kelas_id di kelas yang diampu menjadi NULL
                    $kelasDiampu = $guru->kelasWali;
                    $kelasDiampu->wali_kelas_id = null;
                    $kelasDiampu->save();
                    Log::info("Wali kelas (ID: {$guru->id}) dicabut dari kelas (ID: {$kelasDiampu->id}). wali_kelas_id di kelas diset NULL.");
                }
            }
          

            DB::commit(); // Commit transaksi jika berhasil
            Log::info('Akun Guru diperbarui: ' . $guru->nip . ' (Wali Kelas: ' . ($guru->is_wali_kelas ? 'Ya' : 'Tidak') . ')');
            return redirect()->route('admin.guru-management.index')->with('success', 'Akun Guru berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaksi jika ada error
            Log::error('Gagal memperbarui akun Guru: ' . $e->getMessage(), ['guru_id' => $guru->id, 'request_data' => $request->all()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui akun Guru: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Guru $guru)
    {
        DB::beginTransaction(); // Mulai transaksi database

        try {
            // Sebelum menghapus guru, cek apakah dia adalah wali kelas untuk kelas manapun
            if ($guru->is_wali_kelas) {
                // Jika guru ini adalah wali kelas, set wali_kelas_id di kelas yang diampu menjadi NULL
                if ($guru->kelasWali) { // Menggunakan relasi hasOne
                    $kelasDiampu = $guru->kelasWali;
                    $kelasDiampu->wali_kelas_id = null;
                    $kelasDiampu->save();
                    Log::info("Wali kelas (ID: {$guru->id}) dicabut dari kelas (ID: {$kelasDiampu->id}) karena guru dihapus.");
                }
            }

            if ($guru->assignments()->count() > 0 ||
                $guru->teachingMaterials()->count() > 0 ||
                $guru->appreciations()->count() > 0 ||
                $guru->studentAnnouncements()->count() > 0 ||
                $guru->assignmentsGiven()->count() > 0 ||
                $guru->attendances()->count() > 0 ||
                $guru->rekapNilaiMapel()->count() > 0 ||
                $guru->raports()->count() > 0 ||
                $guru->nilaiAkademikCreated()->count() > 0 ||
                $guru->nilaiKeterampilanCreated()->count() > 0 ||
                $guru->nilaiSikapCreated()->count() > 0 ||
                $guru->presensiAkhirCreated()->count() > 0 ||
                $guru->catatanWaliKelasCreated()->count() > 0
            ) {
                DB::rollBack();
                return redirect()->route('admin.guru-management.index')->with('error', 'Gagal menghapus akun Guru. Guru ini memiliki data terkait di sistem (tugas, materi, nilai, dll).');
            }

            $guru->delete(); // Hapus guru
            DB::commit(); // Commit transaksi
            Log::info('Akun Guru berhasil dihapus: ' . $guru->nip);
            return redirect()->route('admin.guru-management.index')->with('success', 'Akun Guru berhasil dihapus!');

        } catch (QueryException $e) {
            DB::rollBack(); // Rollback jika ada error database
            if ($e->getCode() == "23000") { // SQLSTATE for Integrity Constraint Violation
                Log::error('Gagal menghapus guru karena foreign key constraint: ' . $e->getMessage(), ['guru_id' => $guru->id]);
                return redirect()->route('admin.guru-management.index')->with('error', 'Gagal menghapus akun Guru. Data guru ini mungkin terkait dengan data lain (misal: mata pelajaran, kelas, dll). Hapus data terkait terlebih dahulu atau sesuaikan relasi database.');
            }
            Log::error('Gagal menghapus guru (QueryException): ' . $e->getMessage(), ['guru_id' => $guru->id]);
            return redirect()->route('admin.guru-management.index')->with('error', 'Terjadi kesalahan database saat menghapus akun Guru: ' . $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback jika ada error umum
            Log::error('Gagal menghapus guru (General Exception): ' . $e->getMessage(), ['guru_id' => $guru->id]);
            return redirect()->route('admin.guru-management.index')->with('error', 'Terjadi kesalahan umum saat menghapus akun Guru: ' . $e->getMessage());
        }
    }
}
