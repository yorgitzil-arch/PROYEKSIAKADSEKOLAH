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
        Schema::create('teaching_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('gurus')->onDelete('cascade'); // Guru yang mengunggah materi
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajarans')->onDelete('cascade'); // Mata pelajaran terkait
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade'); // <--- PASTIKAN BARIS INI ADA
            $table->string('title'); // Judul materi ajar
            $table->text('description')->nullable(); // Deskripsi materi
            $table->string('file_path'); // Path ke file materi (PDF, DOCX, dll.)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teaching_materials');
    }
};
