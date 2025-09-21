<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInfo;
use Illuminate\Http\Request;
use App\Models\SchoolProfile; // <--- TAMBAHKAN INI

class ContactInfoController extends Controller
{
    /**
     * Menampilkan formulir untuk mengedit atau membuat informasi kontak.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contactInfo = ContactInfo::first();

        if (!$contactInfo) {
            $contactInfo = new ContactInfo();
        }

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.public_content.contact_info.index', compact('contactInfo', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menyimpan atau memperbarui informasi kontak.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeUpdate(Request $request)
    {
        $contactInfo = ContactInfo::first();

        $rules = [
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'map_embed_url' => 'nullable|url|max:2048',
            'social_media_links' => 'nullable|array',
            'social_media_links.*' => 'nullable|url|max:255',
        ];

        $request->validate($rules);

        $data = $request->except(['_token', '_method']);
        if (!isset($data['social_media_links'])) {
            $data['social_media_links'] = null;
        }


        if ($contactInfo) {
            $contactInfo->update($data);
            $message = 'Informasi kontak berhasil diperbarui!';
        } else {
            ContactInfo::create($data);
            $message = 'Informasi kontak berhasil ditambahkan!';
        }

        return redirect()->route('admin.contact-info.index')->with('success', $message);
    }
}