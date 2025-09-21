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
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama fasilitas (misal: Laboratorium Komputer)
            $table->string('slug')->unique(); // Slug untuk URL yang rapi
            $table->text('description')->nullable(); // Deskripsi fasilitas
            $table->string('image')->nullable(); // Path gambar fasilitas
            $table->integer('display_order')->default(0); // Urutan tampilan
            $table->boolean('is_active')->default(true); // Status aktif/tidak aktif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
