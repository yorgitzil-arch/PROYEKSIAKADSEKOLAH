<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SchoolProfile; // <--- TAMBAHKAN INI

class ProfileController extends Controller
{
    /**
     * Menampilkan formulir profil siswa.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $siswa = Auth::guard('siswa')->user();
        
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('siswa.profile.index', compact('siswa', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Memperbarui informasi profil siswa.
     * Hanya nama dan email yang bisa diubah.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $siswa = Auth::guard('siswa')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            // Pastikan email unik kecuali untuk email siswa itu sendiri
            'email' => 'required|string|email|max:255|unique:siswas,email,' . $siswa->id,
        ]);

        // Update nama dan email
        $siswa->name = $request->name;
        $siswa->email = $request->email;


        $siswa->save();

        return redirect()->route('siswa.profile.index')->with('success', 'Profil berhasil diperbarui!');
    }
}