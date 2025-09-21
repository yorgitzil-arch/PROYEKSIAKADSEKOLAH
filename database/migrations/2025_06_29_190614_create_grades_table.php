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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->integer('nilai'); // Atau $table->float('nilai', 5, 2); jika butuh desimal
            $table->string('keterangan')->nullable(); // Misal: Tugas Harian, UTS, UAS

            // Mencegah satu siswa memiliki lebih dari satu nilai untuk penugasan yang sama
            $table->unique(['assignment_id', 'siswa_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
