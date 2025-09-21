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
        Schema::create('semesters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->onDelete('cascade');
            $table->string('nama'); // Contoh: Ganjil, Genap
            $table->boolean('is_active')->default(false); // Menandakan semester aktif
            $table->timestamps();

            // Unique constraint agar tidak ada duplikasi semester dalam satu tahun ajaran
            $table->unique(['tahun_ajaran_id', 'nama']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('semesters');
    }
};