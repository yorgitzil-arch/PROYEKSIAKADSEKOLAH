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
        Schema::create('catatan_wali_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->onDelete('cascade');
            $table->foreignId('semester_id')->constrained('semesters')->onDelete('cascade');
            $table->text('catatan')->nullable();
            $table->foreignId('created_by_guru_id')->nullable()->constrained('gurus')->onDelete('set null'); // Wali kelas yang menginput
            $table->timestamps();

            // Memastikan setiap siswa hanya memiliki satu catatan per tahun ajaran dan semester
            $table->unique(['siswa_id', 'tahun_ajaran_id', 'semester_id'], 'unique_catatan_wali_kelas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('catatan_wali_kelas');
    }
};
