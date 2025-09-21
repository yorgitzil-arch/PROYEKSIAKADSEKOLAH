<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\SchoolProfile; // <--- TAMBAHKAN INI

class DataDiriController extends Controller
{
    /**
     * Menampilkan halaman untuk melengkapi data diri siswa.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $siswa = Auth::guard('siswa')->user();
        
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('siswa.profile_data.index', compact('siswa', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menampilkan formulir untuk mengedit data diri siswa.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $siswa = Auth::guard('siswa')->user();

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------
        
        return view('siswa.profile_data.edit', compact('siswa', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Memperbarui data diri siswa dan mengunggah dokumen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $siswa = Auth::guard('siswa')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'sometimes',
                'string',
                'email',
                'max:255',
                Rule::unique('siswas')->ignore($siswa->id),
            ],
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

        $data = $request->except(['_token', '_method', 'foto_profile', 'ijazah', 'raport', 'kk', 'ktp_ortu', 'akta_lahir', 'sk_lulus', 'kis', 'kks']);

        // Proses unggah file
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
                // Hapus file lama jika ada
                if ($siswa->$columnName && Storage::disk('public')->exists($siswa->$columnName)) {
                    Storage::disk('public')->delete($siswa->$columnName);
                }
                // Simpan file baru
                $data[$columnName] = $request->file($inputName)->store('siswa_documents/' . $siswa->nis, 'public');
            }
        }

        $siswa->update($data);

        return redirect()->route('siswa.data-diri.index')->with('success', 'Data diri dan dokumen berhasil diperbarui!');
    }
}