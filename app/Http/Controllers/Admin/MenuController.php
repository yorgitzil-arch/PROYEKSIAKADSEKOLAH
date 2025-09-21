<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu; // Import model Menu
use App\Models\SchoolProfile; // <--- TAMBAHKAN INI

class MenuController extends Controller
{
    /**
     * Menampilkan daftar item menu.
     */
    public function index()
    {
        $menus = Menu::with('parent')->orderBy('parent_id')->orderBy('order')->get();
        
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.menus.index', compact('menus', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menampilkan form untuk membuat item menu baru.
     */
    public function create()
    {
        $parentMenus = Menu::whereNull('parent_id')->orderBy('order')->get();
        
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.menus.create', compact('parentMenus', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menyimpan item menu baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menus,id',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        Menu::create([
            'name' => $request->name,
            'url' => $request->url,
            'parent_id' => $request->parent_id,
            'order' => $request->order,
            'is_active' => $request->has('is_active'), // Checkbox value
        ]);

        return redirect()->route('admin.menus.index')->with('success', 'Item menu berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail item menu.
     */
    public function show(Menu $menu)
    {
        // Load relasi parent dan children jika diperlukan
        $menu->load('parent', 'children');
        
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.menus.show', compact('menu', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menampilkan form untuk mengedit item menu.
     */
    public function edit(Menu $menu)
    {
        // Mengambil semua menu yang bisa menjadi parent, kecuali menu itu sendiri dan anak-anaknya
        $parentMenus = Menu::whereNull('parent_id')
            ->where('id', '!=', $menu->id)
            ->whereDoesntHave('children', function ($query) use ($menu) {
                $query->where('id', $menu->id);
            })
            ->orderBy('order')
            ->get();
        
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.menus.edit', compact('menu', 'parentMenus', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Memperbarui item menu di database.
     */
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menus,id',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $menu->update([
            'name' => $request->name,
            'url' => $request->url,
            'parent_id' => $request->parent_id,
            'order' => $request->order,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.menus.index')->with('success', 'Item menu berhasil diperbarui!');
    }

    /**
     * Menghapus item menu dari database.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('admin.menus.index')->with('success', 'Item menu berhasil dihapus!');
    }
}