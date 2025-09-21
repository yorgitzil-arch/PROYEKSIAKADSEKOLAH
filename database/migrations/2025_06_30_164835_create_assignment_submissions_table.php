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
        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_given_id')->constrained('assignments_given')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->string('file_path')->nullable(); // Path ke file jawaban siswa
            $table->text('notes')->nullable(); // Catatan dari siswa saat mengumpulkan
            $table->integer('score')->nullable(); // Nilai yang diberikan guru
            $table->text('feedback')->nullable(); // Feedback dari guru
            $table->timestamp('submitted_at')->useCurrent(); // Waktu pengumpulan
            $table->timestamps();

            // Pastikan satu siswa hanya bisa mengumpulkan satu kali per tugas
            $table->unique(['assignment_given_id', 'siswa_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_submissions');
    }
};
