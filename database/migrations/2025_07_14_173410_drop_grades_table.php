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
        Schema::dropIfExists('grades'); // Hapus tabel grades lama
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Jika ingin mengembalikan, Anda perlu mendefinisikan ulang skema tabel grades lama di sini
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->integer('nilai');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }
};