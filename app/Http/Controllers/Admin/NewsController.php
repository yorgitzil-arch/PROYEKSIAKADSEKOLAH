<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\View\View;
use App\Models\SchoolProfile; // <--- TAMBAHKAN INI

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {

        $news = News::latest()->paginate(10);
        
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.news.index', compact('news', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.news.create', compact('schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi input dari form
        $request->validate([
            'title'             => 'required|string|max:255|unique:news,title',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'short_description' => 'nullable|string|max:500',
            'content'           => 'required|string',
            'source_url'        => 'nullable|url',
            'published_at'      => 'nullable|date',
        ]);

        $imagePath = null;
        // Jika ada file gambar di-upload
        if ($request->hasFile('image')) {

            $imagePath = $request->file('image')->store('news_images', 'public');
        }
        News::create([
            'title'             => $request->input('title'),
            'slug'              => Str::slug($request->input('title')),
            'image_path'        => $imagePath,
            'short_description' => $request->input('short_description'),
            'content'           => $request->input('content'),
            'source_url'        => $request->input('source_url'),
            'published_at'      => $request->input('published_at') ?? now(),
        ]);

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\News  $news  Instance model News (Route Model Binding)
     * @return \Illuminate\View\View
     */
    public function show(News $news): View
    {
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.news.show', compact('news', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\View\View
     */
    public function edit(News $news): View
    {
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.news.edit', compact('news', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, News $news): RedirectResponse
    {
        // Validasi input
        $request->validate([
            'title'             => 'required|string|max:255|unique:news,title,' . $news->id, // Judul unik kecuali untuk berita ini
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'short_description' => 'nullable|string|max:500',
            'content'           => 'required|string',
            'source_url'        => 'nullable|url',
            'published_at'      => 'nullable|date',
        ]);

        $imagePath = $news->image_path; // Ambil path gambar lama
        if ($request->hasFile('image')) {
            if ($news->image_path) {
                Storage::disk('public')->delete($news->image_path);
            }
            // Simpan gambar baru
            $imagePath = $request->file('image')->store('news_images', 'public');
        } elseif ($request->input('clear_image')) {
            if ($news->image_path) {
                Storage::disk('public')->delete($news->image_path);
                $imagePath = null;
            }
        }

        // Perbarui record berita
        $news->update([
            'title'             => $request->input('title'),
            'slug'              => Str::slug($request->input('title')),
            'image_path'        => $imagePath,
            'short_description' => $request->input('short_description'),
            'content'           => $request->input('content'),
            'source_url'        => $request->input('source_url'),
            'published_at'      => $request->input('published_at') ?? $news->published_at, // Update published_at atau pertahankan yang lama
        ]);

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(News $news): RedirectResponse
    {
        // Hapus file gambar terkait jika ada
        if ($news->image_path) {
            Storage::disk('public')->delete($news->image_path);
        }
        // Hapus record berita dari database
        $news->delete();
        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil dihapus!');
    }
}