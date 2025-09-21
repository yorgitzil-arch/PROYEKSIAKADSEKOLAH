<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     * Membuat tabel 'public_teachers' untuk menyimpan data guru yang ditampilkan di publik.
     */
    public function up(): void
    {
        Schema::create('public_teachers', function (Blueprint $table) {
            $table->id(); // Kolom ID otomatis (primary key)
            $table->foreignId('guru_id')->constrained('gurus')->onDelete('cascade'); // Foreign key ke tabel gurus
            $table->string('position')->nullable(); // Jabatan (misal: Kepala Sekolah, Guru Matematika)
            $table->string('category')->nullable(); // Kategori (misal: PNS, Honorer)
            $table->string('image_path')->nullable(); // Path gambar profil guru untuk publik (jika berbeda dari profil guru login)
            $table->integer('display_order')->default(0); // Urutan tampilan di halaman publik
            $table->boolean('is_featured')->default(false); // Apakah guru ini ditampilkan sebagai "featured"
            $table->timestamps(); // Kolom created_at dan updated_at otomatis

            // Pastikan satu guru hanya bisa memiliki satu entri di daftar publik
            $table->unique('guru_id');
        });
    }

    /**
     * Kembalikan migrasi.
     * Menghapus tabel 'public_teachers' jika rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('public_teachers');
    }
};

