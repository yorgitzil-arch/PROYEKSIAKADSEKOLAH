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
        Schema::create('raport_ekstrakurikuler', function (Blueprint $table) {
            $table->id();
            $table->foreignId('raport_id')->constrained('raports')->onDelete('cascade');
            $table->string('nama_ekskul');
            $table->enum('jenis_ekskul', ['Wajib', 'Pilihan']);
            $table->enum('predikat', ['A', 'B', 'C', 'D'])->nullable();
            $table->timestamps();

            // Menambahkan unique constraint agar tidak ada duplikat ekskul untuk raport yang sama
            $table->unique(['raport_id', 'nama_ekskul']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raport_ekstrakurikuler');
    }
};
