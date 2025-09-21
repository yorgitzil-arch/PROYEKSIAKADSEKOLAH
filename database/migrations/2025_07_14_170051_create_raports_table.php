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
        Schema::create('raports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('wali_kelas_id')->constrained('gurus')->onDelete('cascade');
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->onDelete('cascade');
            $table->foreignId('semester_id')->constrained('semesters')->onDelete('cascade');

            $table->text('catatan_wali_kelas')->nullable();
            
            // Kolom untuk rekap absensi yang diambil dari tabel attendances
            $table->integer('jumlah_sakit')->default(0);
            $table->integer('jumlah_izin')->default(0);
            $table->integer('jumlah_alfa')->default(0);

            $table->decimal('rata_rata_nilai', 5, 2)->nullable(); // Bisa dihitung saat generate
            $table->integer('peringkat_ke')->nullable(); // Bisa dihitung saat generate

            $table->date('tanggal_cetak')->nullable();
            $table->string('tempat_cetak')->nullable();
            $table->boolean('status_final')->default(false); // Untuk menandai raport sudah final dan tidak bisa diedit lagi

            $table->timestamps();

            // Agar tidak ada duplikasi raport untuk siswa, kelas, tahun, semester tertentu
            $table->unique(['siswa_id', 'kelas_id', 'tahun_ajaran_id', 'semester_id'], 'unique_raport_siswa_periode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raports');
    }
};