<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     * Membuat tabel 'contact_infos' untuk menyimpan informasi kontak sekolah.
     * Diasumsikan sebagai tabel dengan satu baris data.
     */
    public function up(): void
    {
        Schema::create('contact_infos', function (Blueprint $table) {
            $table->id(); // Kolom ID otomatis (primary key)
            $table->string('title')->nullable(); // Judul halaman kontak (misal: "Hubungi Kami")
            $table->text('content')->nullable(); // Deskripsi atau teks tambahan di halaman kontak
            $table->string('phone')->nullable(); // Nomor telepon kontak
            $table->string('email')->nullable(); // Email kontak
            $table->string('address')->nullable(); // Alamat fisik
            $table->string('map_embed_url')->nullable(); // URL embed peta (misal dari Google Maps)
            $table->json('social_media_links')->nullable(); // Link media sosial dalam format JSON (misal: {"facebook": "url", "instagram": "url"})
            $table->timestamps(); // Kolom created_at dan updated_at otomatis
        });
    }

    /**
     * Kembalikan migrasi.
     * Menghapus tabel 'contact_infos' jika rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_infos');
    }
};

