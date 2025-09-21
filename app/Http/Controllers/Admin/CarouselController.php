<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarouselItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\SchoolProfile; // <--- TAMBAHKAN INI

class CarouselController extends Controller
{
    /**
     * Menampilkan daftar item carousel.
     * Mendukung pencarian berdasarkan judul atau deskripsi.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $carouselItems = CarouselItem::query()
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->orderBy('order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Pagination 10 item per halaman

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.public_content.carousel.index', compact('carouselItems', 'search', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menampilkan formulir untuk membuat item carousel baru.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.public_content.carousel.create', compact('schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menyimpan item carousel baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data = $request->except(['_token', 'image']);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('carousel_images', 'public'); // Simpan di folder storage/app/public/carousel_images
            $data['image_path'] = $imagePath;
        }

        CarouselItem::create($data);

        return redirect()->route('admin.carousel.index')->with('success', 'Item carousel berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail item carousel tertentu.
     * (Biasanya detail ditampilkan di halaman edit atau tidak ada view terpisah)
     *
     * @param  \App\Models\CarouselItem  $carousel
     * @return \Illuminate\Http\Response
     */
    public function show(CarouselItem $carousel)
    {
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        // Karena ini me-redirect ke 'edit', schoolProfile akan tetap dibutuhkan di sana.
        return redirect()->route('admin.carousel.edit', $carousel)->with(compact('schoolProfile'));
        // NOTE: Mengirim data dengan with() saat redirect tidak selalu ideal untuk semua tipe data.
        // Lebih baik ambil di controller tujuan (edit method)
    }

    /**
     * Menampilkan formulir untuk mengedit item carousel tertentu.
     *
     * @param  \App\Models\CarouselItem  $carousel
     * @return \Illuminate\Http\Response
     */
    public function edit(CarouselItem $carousel)
    {
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.public_content.carousel.edit', compact('carousel', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Memperbarui item carousel tertentu di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CarouselItem  $carousel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CarouselItem $carousel)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data = $request->except(['_token', '_method', 'image']);

        // Handle upload gambar
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($carousel->image_path) {
                Storage::disk('public')->delete($carousel->image_path);
            }
            // Simpan gambar baru
            $imagePath = $request->file('image')->store('carousel_images', 'public');
            $data['image_path'] = $imagePath;
        } else {
            if ($carousel->image_path && !isset($data['image_path'])) {
                $data['image_path'] = $carousel->image_path;
            }
        }

        $carousel->update($data);

        return redirect()->route('admin.carousel.index')->with('success', 'Item carousel berhasil diperbarui!');
    }

    /**
     * Menghapus item carousel tertentu dari database.
     *
     * @param  \App\Models\CarouselItem  $carousel
     * @return \Illuminate\Http\Response
     */
    public function destroy(CarouselItem $carousel)
    {
        // Hapus gambar terkait jika ada
        if ($carousel->image_path) {
            Storage::disk('public')->delete($carousel->image_path);
        }
        $carousel->delete();
        return redirect()->route('admin.carousel.index')->with('success', 'Item carousel berhasil dihapus!');
    }
}