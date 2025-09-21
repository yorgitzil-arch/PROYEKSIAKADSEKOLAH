<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\SchoolProfile; // <--- TAMBAHKAN INI

class AdminManagementController extends Controller
{
    public function index()
    {
        // Ambil semua admin kecuali yang sedang login
        $admins = Admin::where('id', '!=', auth('admin')->id())->orderBy('name')->get();

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.admin-management.index', compact('admins', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    public function create()
    {
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.admin-management.create', compact('schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.admin-management.index')->with('success', 'Admin baru berhasil ditambahkan!');
    }

    public function destroy(Admin $admin)
    {
        if ($admin->id === auth('admin')->id()) {
            return redirect()->route('admin.admin-management.index')->with('error', 'Anda tidak bisa menghapus akun Anda sendiri!');
        }

        $admin->delete();
        return redirect()->route('admin.admin-management.index')->with('success', 'Admin berhasil dihapus!');
    }
}