<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdmissionInfo;
use Illuminate\Http\Request;
use App\Models\SchoolProfile; // <--- TAMBAHKAN INI

class AdmissionInfoController extends Controller
{
    /**
     * Menampilkan formulir untuk mengedit atau membuat informasi PPDB.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admissionInfo = AdmissionInfo::first();
        if (!$admissionInfo) {
            $admissionInfo = new AdmissionInfo();
        }

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.public_content.admission_info.index', compact('admissionInfo', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menyimpan atau memperbarui informasi PPDB.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeUpdate(Request $request)
    {
        $admissionInfo = AdmissionInfo::first();

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'published_at' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $data = $request->except(['_token', '_method']);

        if ($admissionInfo) {
            $admissionInfo->update($data);
            $message = 'Informasi PPDB berhasil diperbarui!';
        } else {
            AdmissionInfo::create($data);
            $message = 'Informasi PPDB berhasil ditambahkan!';
        }

        return redirect()->route('admin.admission-info.index')->with('success', $message);
    }
}