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
        Schema::create('nilai_akademik', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->enum('jenis_nilai', ['ulangan_harian', 'tugas', 'uts', 'uas', 'sumatif_lain'])->comment('Jenis penilaian');
            $table->string('nama_nilai')->nullable()->comment('Nama spesifik nilai (ex: UH Bab 1, Proyek IPA)');
            $table->integer('nilai')->comment('Nilai 0-100');
            $table->date('tanggal_nilai');
            $table->foreignId('semester_id')->constrained('semesters')->onDelete('restrict');
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_akademik');
    }
};