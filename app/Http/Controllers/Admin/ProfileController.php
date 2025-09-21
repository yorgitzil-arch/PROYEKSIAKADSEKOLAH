<?php

namespace App\Http\Controllers\Admin; // Pastikan namespace ini benar sesuai lokasi file

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin; // Menggunakan model Admin
use Illuminate\Support\Facades\Auth; // Untuk mendapatkan admin yang login
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Models\SchoolProfile; // <--- TAMBAHKAN INI

class ProfileController extends Controller // Menggunakan nama ProfileController
{
    // Tambahkan middleware jika hanya admin yang boleh akses
    public function __construct()
    {
        $this->middleware('auth:admin'); // Sesuaikan guard jika berbeda (misal 'web' jika admin menggunakan guard default)
    }

    public function index()
    {
        // Ambil data profil admin yang sedang login
        $admin = Auth::guard('admin')->user();

        // --- Tambahkan ini untuk $schoolProfile sesuai permintaan ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // ---------------------------------------------------------

        // Pastikan nama view sesuai dengan lokasi yang kamu inginkan
        return view('admin.profile.index', compact('admin', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    public function update(Request $request) // Menggunakan nama metode 'update'
    {
        // Ambil data profil admin yang sedang login
        $admin = Auth::guard('admin')->user();

        try {
            $validatedData = $request->validate([
                'name'              => 'required|string|max:255',
                'email'             => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    // Pastikan email unik kecuali untuk admin ini sendiri
                    'unique:admins,email,' . $admin->id,
                ],
                'old_password'      => 'nullable|string', // Untuk validasi password lama
                'password'          => 'nullable|string|min:8|confirmed',
                'profile_picture'   => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                // Pastikan nama field di form kamu adalah 'profile_picture'
            ]);

            // Jika ada password baru, hash dan update
            if (!empty($validatedData['password'])) {
                // Verifikasi password lama jika diisi
                if (!empty($validatedData['old_password']) && !password_verify($validatedData['old_password'], $admin->password)) {
                    throw ValidationException::withMessages([
                        'old_password' => 'Password lama salah.',
                    ]);
                }
                $admin->password = bcrypt($validatedData['password']);
            }
            // Hapus field password dari validatedData agar tidak di-fill langsung
            unset($validatedData['old_password']);
            unset($validatedData['password']);
            unset($validatedData['password_confirmation']);


            // Hapus 'profile_picture' dari validatedData karena akan ditangani secara terpisah
            // Lakukan unset hanya jika 'profile_picture' ada di request untuk menghindari error undefined index
            if ($request->hasFile('profile_picture')) {
                 unset($validatedData['profile_picture']);
            }


            // Update data teks/non-file
            $admin->fill($validatedData);

            // === Handle Profile Picture Upload ===
            if ($request->hasFile('profile_picture')) {
                // Hapus gambar profil lama jika ada
                if ($admin->profile_picture && Storage::disk('public')->exists($admin->profile_picture)) {
                    Storage::disk('public')->delete($admin->profile_picture);
                    Log::info('Gambar profil lama admin dihapus: ' . $admin->profile_picture);
                }
                // Simpan gambar profil baru
                $imagePath = $request->file('profile_picture')->store('admin_profiles', 'public'); // Folder penyimpanan baru
                $admin->profile_picture = $imagePath;
                Log::info('Gambar profil baru admin diupload: ' . $imagePath);
            }

            $admin->save(); // Simpan perubahan pada objek admin

            return redirect()->route('admin.profile.index')->with('success', 'Profil Anda berhasil diperbarui!'); // Sesuaikan route
        } catch (ValidationException $e) {
            Log::warning('Validasi profil admin gagal: ' . $e->getMessage(), ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui profil admin: ' . $e->getMessage(), ['request_data' => $request->all()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui profil Anda: ' . $e->getMessage())->withInput();
        }
    }

    public function deleteProfilePicture()
    {
        $admin = Auth::guard('admin')->user(); // Ambil admin yang sedang login

        try {
            if ($admin->profile_picture && Storage::disk('public')->exists($admin->profile_picture)) {
                Storage::disk('public')->delete($admin->profile_picture);
                $admin->profile_picture = null; // Set field di database menjadi null
                $admin->save(); // Simpan perubahan di database

                Log::info('Gambar profil admin berhasil dihapus.');
                return redirect()->route('admin.profile.index')->with('success', 'Gambar profil Anda berhasil dihapus!'); // Sesuaikan route
            }
            Log::warning('Percobaan hapus gambar profil admin, namun tidak ada gambar ditemukan.');
            return redirect()->route('admin.profile.index')->with('warning', 'Tidak ada gambar profil untuk dihapus.'); // Sesuaikan route

        } catch (\Exception $e) {
            Log::error('Gagal menghapus gambar profil admin: ' . $e->getMessage());
            return redirect()->route('admin.profile.index')->with('error', 'Terjadi kesalahan saat menghapus gambar profil Anda: ' . $e->getMessage()); // Sesuaikan route
        }
    }
}