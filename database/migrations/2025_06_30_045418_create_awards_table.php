<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     * Membuat tabel 'awards' untuk menyimpan data penghargaan.
     */
    public function up(): void
    {
        Schema::create('awards', function (Blueprint $table) {
            $table->id(); // Kolom ID otomatis (primary key)
            $table->string('title'); // Judul penghargaan
            $table->text('description')->nullable(); // Deskripsi penghargaan (opsional)
            $table->string('awarded_by')->nullable(); // Diberikan oleh (misal: Kementerian Pendidikan)
            $table->date('award_date'); // Tanggal penghargaan diterima
            $table->string('image_path')->nullable(); // Path gambar/sertifikat penghargaan (opsional)
            $table->timestamps(); // Kolom created_at dan updated_at otomatis
        });
    }

    /**
     * Kembalikan migrasi.
     * Menghapus tabel 'awards' jika rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('awards');
    }
};

