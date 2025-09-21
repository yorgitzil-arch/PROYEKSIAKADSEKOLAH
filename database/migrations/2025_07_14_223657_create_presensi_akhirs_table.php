<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('presensi_akhirs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->onDelete('cascade');
            $table->foreignId('semester_id')->constrained('semesters')->onDelete('cascade');
            $table->integer('sakit')->default(0);
            $table->integer('izin')->default(0);
            $table->integer('alpha')->default(0);
            $table->foreignId('created_by_guru_id')->nullable()->constrained('gurus')->onDelete('set null'); // Wali kelas yang menginput
            $table->timestamps();

            // Memastikan setiap siswa hanya memiliki satu rekap presensi per tahun ajaran dan semester
            $table->unique(['siswa_id', 'tahun_ajaran_id', 'semester_id'], 'unique_presensi_akhir');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_akhirs');
    }
};
