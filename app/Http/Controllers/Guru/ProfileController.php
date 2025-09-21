<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Tambahkan ini
use Illuminate\Support\Facades\Log;      // Tambahkan ini
use Illuminate\Validation\ValidationException; // Tambahkan ini jika akan mengaktifkan validasi password
use App\Models\SchoolProfile;

class ProfileController extends Controller
{
    /**
     * Menampilkan formulir profil guru.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $guru = Auth::guard('guru')->user();
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        return view('guru.profile.index', compact('guru', 'schoolProfile'));
    }

    /**
     * Memperbarui informasi profil guru.
     * Hanya nama dan email yang bisa diubah.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $guru = Auth::guard('guru')->user();

        try {
            $validatedData = $request->validate([
                'name'              => 'required|string|max:255',
                'email'             => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    'unique:gurus,email,' . $guru->id,
                ],
                'profile_picture'   => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Tambahkan validasi gambar
            ]);

            // Hapus 'profile_picture' dari validatedData karena akan ditangani secara terpisah
            if ($request->hasFile('profile_picture')) {
                unset($validatedData['profile_picture']);
            }

            // Update data teks/non-file
            $guru->fill($validatedData);

            // === Handle Profile Picture Upload ===
            if ($request->hasFile('profile_picture')) {
                // Hapus gambar profil lama jika ada
                if ($guru->profile_picture && Storage::disk('public')->exists($guru->profile_picture)) {
                    Storage::disk('public')->delete($guru->profile_picture);
                    Log::info('Gambar profil lama guru dihapus: ' . $guru->profile_picture);
                }
                // Simpan gambar profil baru ke folder 'guru_profiles'
                $imagePath = $request->file('profile_picture')->store('guru_profiles', 'public');
                $guru->profile_picture = $imagePath;
                Log::info('Gambar profil baru guru diupload: ' . $imagePath);
            }

            $guru->save();

            return redirect()->route('guru.profile.index')->with('success', 'Profil berhasil diperbarui!');
        } catch (ValidationException $e) {
            Log::warning('Validasi profil guru gagal: ' . $e->getMessage(), ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui profil guru: ' . $e->getMessage(), ['request_data' => $request->all()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui profil Anda: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menghapus gambar profil guru.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteProfilePicture()
    {
        $guru = Auth::guard('guru')->user();

        try {
            if ($guru->profile_picture && Storage::disk('public')->exists($guru->profile_picture)) {
                Storage::disk('public')->delete($guru->profile_picture);
                $guru->profile_picture = null; // Set field di database menjadi null
                $guru->save(); // Simpan perubahan di database

                Log::info('Gambar profil guru berhasil dihapus.');
                return redirect()->route('guru.profile.index')->with('success', 'Gambar profil Anda berhasil dihapus!');
            }
            Log::warning('Percobaan hapus gambar profil guru, namun tidak ada gambar ditemukan.');
            return redirect()->route('guru.profile.index')->with('warning', 'Tidak ada gambar profil untuk dihapus.');

        } catch (\Exception $e) {
            Log::error('Gagal menghapus gambar profil guru: ' . $e->getMessage());
            return redirect()->route('guru.profile.index')->with('error', 'Terjadi kesalahan saat menghapus gambar profil Anda: ' . $e->getMessage());
        }
    }
}