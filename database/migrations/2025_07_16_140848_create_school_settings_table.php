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
        Schema::create('school_settings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sekolah')->nullable();
            $table->string('nssn')->nullable();
            $table->string('npsn')->nullable();
            $table->string('alamat')->nullable();
            $table->string('logo_kiri_path')->nullable(); // Path untuk logo kiri (misal: SMKBisa)
            $table->string('logo_kanan_path')->nullable(); // Path untuk logo kanan (misal: background/lambang lain)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_settings');
    }
};