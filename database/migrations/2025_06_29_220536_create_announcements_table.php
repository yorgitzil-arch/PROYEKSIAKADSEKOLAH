<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     * Membuat tabel 'announcements' untuk menyimpan data pengumuman.
     */
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id(); // Kolom ID otomatis (primary key)
            $table->string('title'); // Judul pengumuman
            $table->text('content'); // Isi/konten pengumuman
            $table->string('author')->nullable(); // Penulis pengumuman (opsional)
            $table->timestamp('published_at')->nullable(); // Tanggal publikasi (opsional, bisa untuk scheduling)
            $table->boolean('is_active')->default(true); // Status aktif/nonaktif pengumuman
            $table->timestamps(); // Kolom created_at dan updated_at otomatis
        });
    }

    /**
     * Kembalikan migrasi.
     * Menghapus tabel 'announcements' jika rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
