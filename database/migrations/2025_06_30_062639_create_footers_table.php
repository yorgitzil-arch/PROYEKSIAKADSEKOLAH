<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     * Membuat tabel 'footers' untuk menyimpan data footer situs web.
     * Diasumsikan sebagai tabel dengan satu baris data.
     */
    public function up(): void
    {
        Schema::create('footers', function (Blueprint $table) {
            $table->id(); // Kolom ID otomatis (primary key)
            $table->string('copyright_text')->nullable(); // Teks hak cipta (misal: "© 2025 SMKN 1 Lahusa")
            $table->text('address_short')->nullable(); // Alamat singkat
            $table->string('phone_short')->nullable(); // Telepon singkat
            $table->string('email_short')->nullable(); // Email singkat
            $table->json('quick_links')->nullable(); // Link cepat dalam format JSON (misal: [{"text": "About Us", "url": "/about"}])
            $table->timestamps(); // Kolom created_at dan updated_at otomatis
        });
    }

    /**
     * Kembalikan migrasi.
     * Menghapus tabel 'footers' jika rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('footers');
    }
};

