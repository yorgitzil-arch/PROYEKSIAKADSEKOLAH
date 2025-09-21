<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     * Membuat tabel 'school_profiles' untuk menyimpan informasi profil sekolah.
     * Ini diasumsikan sebagai tabel dengan satu baris data.
     */
    public function up(): void
    {
        Schema::create('school_profiles', function (Blueprint $table) {
            $table->id(); // Kolom ID otomatis (primary key)
            $table->string('name')->nullable(); // Nama sekolah (opsional, jika ingin disimpan terpisah dari nama di brand-link)
            $table->text('history')->nullable(); // Sejarah sekolah
            $table->text('vision')->nullable(); // Visi sekolah
            $table->text('mission')->nullable(); // Misi sekolah
            $table->string('address')->nullable(); // Alamat sekolah
            $table->string('phone')->nullable(); // Nomor telepon
            $table->string('email')->nullable(); // Email kontak
            $table->string('website')->nullable(); // Alamat website
            $table->string('logo_path')->nullable(); // Path gambar logo sekolah
            $table->timestamps(); // Kolom created_at dan updated_at otomatis
        });
    }

    /**
     * Kembalikan migrasi.
     * Menghapus tabel 'school_profiles' jika rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_profiles');
    }
};

