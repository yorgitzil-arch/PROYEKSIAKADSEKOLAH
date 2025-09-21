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
        Schema::create('guru_student_announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('gurus')->onDelete('cascade'); // Guru yang membuat pengumuman
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('set null'); // Kelas tujuan (nullable jika untuk semua kelas)
            $table->string('title'); // Judul pengumuman
            $table->text('message'); // Isi pengumuman
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guru_student_announcements');
    }
};
