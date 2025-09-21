<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactMessage;
use App\Models\SchoolProfile; // <--- TAMBAHKAN INI

class ContactMessageController extends Controller
{
    /**
     * Menampilkan daftar semua pesan kontak yang diterima.
     */
    public function index()
    {
        $contactMessages = ContactMessage::orderBy('created_at', 'desc')->get();

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.contact_messages.index', compact('contactMessages', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menampilkan detail pesan kontak tertentu dan menandainya sebagai sudah dibaca.
     */
    public function show(ContactMessage $contactMessage)
    {
        // Tandai pesan sebagai sudah dibaca jika belum
        if (!$contactMessage->is_read) {
            $contactMessage->update(['is_read' => true]);
        }

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.contact_messages.show', compact('contactMessage', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menandai pesan sebagai sudah dibaca atau belum dibaca (toggle).
     */
    public function toggleReadStatus(ContactMessage $contactMessage)
    {
        $contactMessage->update(['is_read' => !$contactMessage->is_read]);
        return redirect()->back()->with('success', 'Status pesan berhasil diperbarui!');
    }

    /**
     * Menghapus pesan kontak dari database.
     */
    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();
        return redirect()->route('admin.contact-messages.index')->with('success', 'Pesan berhasil dihapus!');
    }
}