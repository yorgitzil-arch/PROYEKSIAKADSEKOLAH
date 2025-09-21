<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     * Mengubah tipe kolom 'map_embed_url' di tabel 'contact_infos' menjadi 'text'.
     */
    public function up(): void
    {
        Schema::table('contact_infos', function (Blueprint $table) {
            $table->text('map_embed_url')->nullable()->change();
        });
    }

    /**
     * Kembalikan migrasi.
     * Mengembalikan tipe kolom 'map_embed_url' menjadi 'string' (VARCHAR) dengan panjang default 255.
     */
    public function down(): void
    {
        Schema::table('contact_infos', function (Blueprint $table) {
            // SOLUSI: Untuk menghindari error "Data too long", kita akan biarkan sebagai TEXT di metode down()
            // Karena jika ada data > 255 karakter, ini akan gagal.
            // Biarkan sebagai TEXT di down() juga, agar rollback tidak memotong data.
            $table->text('map_embed_url')->nullable()->change(); 
        });
    }
};