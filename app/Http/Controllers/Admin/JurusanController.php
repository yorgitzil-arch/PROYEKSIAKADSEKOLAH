<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use App\Models\SchoolProfile; // <--- TAMBAHKAN INI

class JurusanController extends Controller
{
    /**
     * Menampilkan daftar semua jurusan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $jurusans = Jurusan::when($search, function ($query, $search) {
            $query->where('nama_jurusan', 'like', '%' . $search . '%')
                ->orWhere('kode_jurusan', 'like', '%' . $search . '%');
        })
            ->orderBy('nama_jurusan', 'asc')
            ->paginate(10);

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.data_master.jurusan.index', compact('jurusans', 'search', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menampilkan formulir untuk membuat jurusan baru.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.data_master.jurusan.create', compact('schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menyimpan jurusan baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi data menggunakan aturan dari model
        $request->validate(Jurusan::rules());

        Jurusan::create($request->all());

        return redirect()->route('admin.jurusans.index')->with('success', 'Jurusan berhasil ditambahkan!');
    }

    /**
     * Menampilkan formulir untuk mengedit jurusan yang sudah ada.
     *
     * @param  \App\Models\Jurusan  $jurusan
     * @return \Illuminate\Http\Response
     */
    public function edit(Jurusan $jurusan)
    {
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.data_master.jurusan.edit', compact('jurusan', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Memperbarui jurusan yang sudah ada di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Jurusan  $jurusan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Jurusan $jurusan)
    {
        // Validasi data, tambahkan ID untuk pengecualian unique rule
        $request->validate(Jurusan::rules($jurusan->id));

        $jurusan->update($request->all());

        return redirect()->route('admin.jurusans.index')->with('success', 'Jurusan berhasil diperbarui!');
    }

    /**
     * Menghapus jurusan dari database.
     *
     * @param  \App\Models\Jurusan  $jurusan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Jurusan $jurusan)
    {
        try {
            $jurusan->delete();
            return redirect()->route('admin.jurusans.index')->with('success', 'Jurusan berhasil dihapus!');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('admin.jurusans.index')->with('error', 'Jurusan tidak dapat dihapus karena masih terkait dengan data lain.');
        }
    }
}