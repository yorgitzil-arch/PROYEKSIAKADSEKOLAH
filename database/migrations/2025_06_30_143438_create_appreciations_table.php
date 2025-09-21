<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appreciations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('admins')->onDelete('cascade'); // Admin yang mengirim apresiasi
            $table->foreignId('guru_id')->constrained('gurus')->onDelete('cascade'); // Guru yang menerima apresiasi
            $table->string('title'); // Judul apresiasi (misal: "Kerja Bagus!", "Peningkatan Kinerja")
            $table->text('message'); // Pesan apresiasi
            $table->enum('category', ['sangat_luar_biasa', 'baik', 'cukup', 'kurang'])->nullable(); // Kategori apresiasi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appreciations');
    }
};
