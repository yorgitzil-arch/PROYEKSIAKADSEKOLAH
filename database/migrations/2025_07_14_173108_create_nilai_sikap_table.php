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
        Schema::create('nilai_sikap', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->nullable()->constrained('assignments')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->enum('jenis_sikap', ['spiritual', 'sosial']);
            $table->text('deskripsi');
            $table->foreignId('semester_id')->constrained('semesters')->onDelete('restrict');
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->onDelete('restrict');
            $table->timestamps();

            $table->unique(['siswa_id', 'jenis_sikap', 'semester_id', 'tahun_ajaran_id', 'assignment_id'], 'nilai_sikap_unique_per_period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_sikap');
    }
};