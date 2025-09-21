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
        // KOREKSI: Ubah nama tabel menjadi plural 'rekap_nilai_mapels'
        Schema::create('rekap_nilai_mapels', function (Blueprint $table) { // <--- PASTIKAN ADA 's' DI SINI
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('mapel_id')->constrained('mata_pelajarans')->onDelete('cascade');
            $table->foreignId('guru_pengampu_id')->constrained('gurus')->onDelete('cascade'); // Guru yang mengampu mapel
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade'); // Kelas siswa saat rekap nilai
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->onDelete('cascade');
            $table->foreignId('semester_id')->constrained('semesters')->onDelete('cascade');

            $table->integer('kkm_mapel')->nullable(); // KKM pada saat nilai direkap

            // Nilai Pengetahuan
            $table->decimal('nilai_pengetahuan_angka', 5, 2)->nullable();
            $table->string('nilai_pengetahuan_predikat')->nullable(); // A, B, C, D
            $table->text('deskripsi_pengetahuan')->nullable();

            // Nilai Keterampilan
            $table->decimal('nilai_keterampilan_angka', 5, 2)->nullable();
            $table->string('nilai_keterampilan_predikat')->nullable();
            $table->text('deskripsi_keterampilan')->nullable();

            // Nilai Sikap
            $table->string('nilai_sikap_spiritual_predikat')->nullable(); // A, B, C, D
            $table->text('deskripsi_sikap_spiritual')->nullable();
            $table->string('nilai_sikap_sosial_predikat')->nullable(); // A, B, C, D
            $table->text('deskripsi_sikap_sosial')->nullable();

            $table->timestamps();

            // KOREKSI: Ubah nama constraint unique agar konsisten dengan nama tabel plural
            $table->unique(['siswa_id', 'mapel_id', 'tahun_ajaran_id', 'semester_id'], 'unique_rekap_nilai_mapels'); // <--- PASTIKAN ADA 's' DI SINI
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // KOREKSI: Ubah nama tabel menjadi plural 'rekap_nilai_mapels'
        Schema::dropIfExists('rekap_nilai_mapels'); // <--- PASTIKAN ADA 's' DI SINI
    }
};
