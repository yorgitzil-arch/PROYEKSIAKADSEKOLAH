<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     * Membuat tabel 'admission_infos' untuk menyimpan informasi PPDB.
     * Diasumsikan sebagai tabel dengan satu baris data.
     */
    public function up(): void
    {
        Schema::create('admission_infos', function (Blueprint $table) {
            $table->id(); // Kolom ID otomatis (primary key)
            $table->string('title'); // Judul informasi PPDB
            $table->text('content'); // Isi/konten informasi PPDB
            $table->timestamp('published_at')->nullable(); // Tanggal publikasi (opsional)
            $table->boolean('is_active')->default(true); // Status aktif/nonaktif informasi
            $table->timestamps(); // Kolom created_at dan updated_at otomatis
        });
    }

    /**
     * Kembalikan migrasi.
     * Menghapus tabel 'admission_infos' jika rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_infos');
    }
};

