<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     * Membuat tabel 'jurusans' untuk menyimpan data jurusan sekolah.
     */
    public function up(): void
    {
        Schema::create('jurusans', function (Blueprint $table) {
            $table->id(); // Kolom ID otomatis (primary key)
            $table->string('nama_jurusan')->unique(); // Nama jurusan, harus unik
            $table->string('kode_jurusan')->unique(); // Kode jurusan, harus unik (misal: RPL, TKJ)
            $table->text('deskripsi')->nullable(); // Deskripsi jurusan
            $table->timestamps(); // Kolom created_at dan updated_at otomatis
        });
    }

    /**
     * Kembalikan migrasi.
     * Menghapus tabel 'jurusans' jika rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurusans');
    }
};

