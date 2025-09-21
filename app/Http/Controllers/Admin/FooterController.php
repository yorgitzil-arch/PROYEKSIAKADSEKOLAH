<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Footer; // Impor model Footer
use Illuminate\Http\Request;
use App\Models\SchoolProfile; // <--- TAMBAHKAN INI

class FooterController extends Controller
{
    /**
     * Menampilkan formulir untuk mengedit atau membuat informasi footer.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $footer = Footer::first();

        if (!$footer) {
            $footer = new Footer();
        }

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.public_content.footer.index', compact('footer', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menyimpan atau memperbarui informasi footer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeUpdate(Request $request)
    {
        $footer = Footer::first();

        $rules = [
            'copyright_text' => 'nullable|string|max:255',
            'address_short' => 'nullable|string|max:255',
            'phone_short' => 'nullable|string|max:20',
            'email_short' => 'nullable|email|max:255',
            'quick_links' => 'nullable|array',
            'quick_links.*.text' => 'required_with:quick_links.*.url|string|max:255',
            'quick_links.*.url' => 'required_with:quick_links.*.text|url|max:255',
        ];

        $request->validate($rules);

        $data = $request->except(['_token', '_method']);

        $filteredQuickLinks = [];
        if (isset($data['quick_links']) && is_array($data['quick_links'])) {
            foreach ($data['quick_links'] as $link) {
                if (!empty($link['text']) && !empty($link['url'])) {
                    $filteredQuickLinks[] = $link;
                }
            }
        }
        $data['quick_links'] = !empty($filteredQuickLinks) ? json_encode($filteredQuickLinks) : null;


        if ($footer) {
            // Jika informasi sudah ada, update
            $footer->update($data);
            $message = 'Informasi footer berhasil diperbarui!';
        } else {
            // Jika informasi belum ada, buat baru
            Footer::create($data);
            $message = 'Informasi footer berhasil ditambahkan!';
        }

        return redirect()->route('admin.footer.index')->with('success', $message);
    }
}