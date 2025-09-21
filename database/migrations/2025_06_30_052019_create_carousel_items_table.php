<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     * Membuat tabel 'carousel_items' untuk menyimpan data slide carousel.
     */
    public function up(): void
    {
        Schema::create('carousel_items', function (Blueprint $table) {
            $table->id(); // Kolom ID otomatis (primary key)
            $table->string('title')->nullable(); // Judul slide (opsional)
            $table->text('description')->nullable(); // Deskripsi slide (opsional)
            $table->string('image_path'); // Path gambar slide (wajib)
            $table->integer('order')->default(0); // Urutan tampilan slide
            $table->boolean('is_active')->default(true); // Status aktif/nonaktif slide
            $table->timestamps(); // Kolom created_at dan updated_at otomatis
        });
    }

    /**
     * Kembalikan migrasi.
     * Menghapus tabel 'carousel_items' jika rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('carousel_items');
    }
};

