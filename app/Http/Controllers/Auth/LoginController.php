<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\SchoolProfile;

class LoginController extends Controller
{
    public function showAdminLoginForm()
    {

        $schoolProfile = SchoolProfile::first();

        // Jika SchoolProfile tidak ditemukan, inisialisasi dengan nilai default
        // untuk menghindari error 'Undefined variable' atau 'Trying to get property of non-object'.
        if (!$schoolProfile) {
            // Buat instance SchoolProfile kosong atau dengan nilai default.
            // Anda bisa menyesuaikan properti default sesuai kebutuhan.
            $schoolProfile = (object)['logo_path' => null, 'name' => 'Nama Sekolah Default'];
            // Atau jika Anda ingin lebih ketat dan SchoolProfile harus ada,
            // Anda bisa throw error, log, atau redirect ke halaman setup.
        }

        return view('auth.admin-login', compact('schoolProfile'));
    }

    public function adminLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            return redirect()->intended(route('admin.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    public function showGuruLoginForm()
    {

       $schoolProfile = SchoolProfile::first();

        // Jika SchoolProfile tidak ditemukan, inisialisasi dengan nilai default
        // untuk menghindari error 'Undefined variable' atau 'Trying to get property of non-object'.
        if (!$schoolProfile) {
            // Buat instance SchoolProfile kosong atau dengan nilai default.
            // Anda bisa menyesuaikan properti default sesuai kebutuhan.
            $schoolProfile = (object)['logo_path' => null, 'name' => 'Nama Sekolah Default'];
            // Atau jika Anda ingin lebih ketat dan SchoolProfile harus ada,
            // Anda bisa throw error, log, atau redirect ke halaman setup.
        }

        return view('auth.guru-login', compact('schoolProfile'));
    }

    public function guruLogin(Request $request)
    {
        // --- PENTING: VALIDASI UNTUK NIP ---
        $this->validate($request, [
            'nip' => 'required|string',
            'password' => 'required',
        ]);

        // --- PENTING: GUNAKAN NIP UNTUK ATTEMPT ---
        if (Auth::guard('guru')->attempt(['nip' => $request->nip, 'password' => $request->password], $request->remember)) {
            return redirect()->intended(route('guru.dashboard'));
        }

        // Pesan error jika login gagal (sesuaikan dengan 'nip')
        throw ValidationException::withMessages([
            'nip' => [trans('auth.failed')],
        ]);
    }

    public function showSiswaLoginForm()
    {
        $schoolProfile = SchoolProfile::first();

        // Jika SchoolProfile tidak ditemukan, inisialisasi dengan nilai default
        // untuk menghindari error 'Undefined variable' atau 'Trying to get property of non-object'.
        if (!$schoolProfile) {
            // Buat instance SchoolProfile kosong atau dengan nilai default.
            // Anda bisa menyesuaikan properti default sesuai kebutuhan.
            $schoolProfile = (object)['logo_path' => null, 'name' => 'Nama Sekolah Default'];
            // Atau jika Anda ingin lebih ketat dan SchoolProfile harus ada,
            // Anda bisa throw error, log, atau redirect ke halaman setup.
        }

        return view('auth.siswa-login', compact('schoolProfile'));
    }

    public function siswaLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('siswa')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            return redirect()->intended(route('siswa.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    public function logout(Request $request)
    {
        // Logika logout yang lebih spesifik
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('admin.login');
        } elseif (Auth::guard('guru')->check()) {
            Auth::guard('guru')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('guru.login');
        } elseif (Auth::guard('siswa')->check()) {
            Auth::guard('siswa')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('siswa.login');
        }

        return redirect('/');
    }
}
