<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appreciation;
use App\Models\SchoolProfile; // <--- TAMBAHKAN INI

class AppreciationController extends Controller
{
    /**
     * Menampilkan daftar apresiasi yang diterima oleh guru yang sedang login.
     */
    public function index()
    {
        if (!Auth::guard('guru')->check()) {
            return redirect()->route('guru.login');
        }

        $guruId = Auth::guard('guru')->id();

        $appreciations = Appreciation::where('guru_id', $guruId)
            ->with('admin')
            ->orderBy('created_at', 'desc')
            ->get();

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('guru.appreciations.index', compact('appreciations', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }
}