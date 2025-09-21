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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('gurus')->onDelete('cascade'); // Foreign key ke tabel gurus
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajarans')->onDelete('cascade'); // Foreign key ke tabel mata_pelajarans
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade'); // Foreign key ke tabel kelas

            // Menambahkan unique constraint untuk mencegah duplikasi penugasan
            $table->unique(['guru_id', 'mata_pelajaran_id', 'kelas_id'], 'unique_guru_mapel_kelas');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
