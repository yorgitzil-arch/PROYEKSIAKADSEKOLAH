<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\SchoolProfile; // <--- TAMBAHKAN INI

class FacilityController extends Controller
{
    /**
     * Tampilkan daftar semua fasilitas.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $facilities = Facility::orderBy('display_order', 'asc')->paginate(10);

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.public_content.facilities.index', compact('facilities', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Tampilkan formulir untuk membuat fasilitas baru.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.public_content.facilities.create', compact('schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Simpan fasilitas yang baru dibuat ke penyimpanan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'display_order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();

        // Buat slug dari nama
        $data['slug'] = Str::slug($request->name);

        // Handle upload gambar
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/facilities');
            $data['image'] = str_replace('public/', '', $imagePath);
        } else {
            $data['image'] = null;
        }

        Facility::create($data);

        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas berhasil ditambahkan!');
    }

    /**
     * Tampilkan formulir untuk mengedit fasilitas yang ditentukan.
     *
     * @param  \App\Models\Facility  $facility
     * @return \Illuminate\Http\Response
     */
    public function edit(Facility $facility)
    {
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.public_content.facilities.edit', compact('facility', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Perbarui fasilitas yang ditentukan dalam penyimpanan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Facility  $facility
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Facility $facility)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'display_order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        // Handle upload gambar
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($facility->image) {
                Storage::delete('public/' . $facility->image);
            }
            $imagePath = $request->file('image')->store('public/facilities');
            $data['image'] = str_replace('public/', '', $imagePath);
        } else {
            unset($data['image']);
        }
        // Pastikan checkbox is_active ditangani jika tidak dicentang (request tidak akan mengirimnya)
        $data['is_active'] = $request->has('is_active');


        $facility->update($data);

        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas berhasil diperbarui!');
    }

    /**
     * Hapus fasilitas yang ditentukan dari penyimpanan.
     *
     * @param  \App\Models\Facility  $facility
     * @return \Illuminate\Http\Response
     */
    public function destroy(Facility $facility)
    {
        // Hapus gambar terkait dari penyimpanan
        if ($facility->image) {
            Storage::delete('public/' . $facility->image);
        }

        $facility->delete();

        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas berhasil dihapus!');
    }
}