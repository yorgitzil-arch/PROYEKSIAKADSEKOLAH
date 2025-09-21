<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SppType;
use Illuminate\Http\Request;
use App\Models\SchoolProfile;

class SppTypeManagementController extends Controller
{
    /**
     * Menampilkan daftar semua tipe SPP.
     */
    public function index()
    {
        $sppTypes = SppType::orderBy('name')->get();
        $schoolProfile = SchoolProfile::firstOrCreate([]);

        return view('admin.spp-types.index', compact('sppTypes','schoolProfile'));
    }

    /**
     * Menampilkan form untuk membuat tipe SPP baru.
     */
    public function create()
    {
        $schoolProfile = SchoolProfile::firstOrCreate([]);

        return view('admin.spp-types.create', compact('schoolProfile'));
    }

    /**
     * Menyimpan tipe SPP yang baru dibuat.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:spp_types,name',
            'amount' => 'required|numeric|min:0',
            'duration_in_months' => 'nullable|integer|min:1',
        ]);

        SppType::create($request->all());

        return redirect()->route('admin.spp-types.index')->with('success', 'Tipe SPP berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit tipe SPP.
     */
    public function edit(SppType $sppType)
    {
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        return view('admin.spp-types.edit', compact('sppType', 'schoolProfile'));
    }

    /**
     * Memperbarui tipe SPP di database.
     */
    public function update(Request $request, SppType $sppType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:spp_types,name,' . $sppType->id,
            'amount' => 'required|numeric|min:0',
            'duration_in_months' => 'nullable|integer|min:1',
        ]);

        $sppType->update($request->all());

        return redirect()->route('admin.spp-types.index')->with('success', 'Tipe SPP berhasil diperbarui.');
    }

    /**
     * Menghapus tipe SPP.
     */
    public function destroy(SppType $sppType)
    {
        try {
            $sppType->delete();
            return back()->with('success', 'Tipe SPP berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', 'Tidak dapat menghapus tipe SPP ini karena masih terkait dengan data pembayaran.');
        }
    }
}