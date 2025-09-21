<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Guru; // Untuk memilih guru
use App\Models\Appreciation;
use App\Models\SchoolProfile; // <--- TAMBAHKAN INI

class AppreciationManagementController extends Controller
{
    /**
     * Menampilkan form untuk mengirim apresiasi baru.
     */
    public function create()
    {
        $gurus = Guru::orderBy('name')->get();

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.appreciation_management.create', compact('gurus', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menyimpan apresiasi yang dikirim oleh admin ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'guru_id' => 'required|exists:gurus,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'category' => 'required|in:baik,sangat luar biasa,buruk', // Validasi kategori
        ]);

        Appreciation::create([
            'admin_id' => Auth::guard('admin')->id(), // Admin yang mengirim apresiasi
            'guru_id' => $request->guru_id,
            'title' => $request->title,
            'message' => $request->message,
            'category' => $request->category,
        ]);

        return redirect()->route('admin.appreciation-management.create')->with('success', 'Apresiasi berhasil dikirim kepada guru!');
    }

    /**
     * Menampilkan daftar apresiasi yang pernah dikirim oleh admin.
     * (Opsional, jika ingin admin bisa melihat riwayat apresiasi yang dia kirim)
     */
    public function index()
    {
        $sentAppreciations = Appreciation::where('admin_id', Auth::guard('admin')->id())
            ->with('guru')
            ->orderBy('created_at', 'desc')
            ->get();

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.appreciation_management.index', compact('sentAppreciations', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }
}