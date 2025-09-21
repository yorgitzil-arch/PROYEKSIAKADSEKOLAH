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
        Schema::create('home_statistics', function (Blueprint $table) {
            $table->id();
            $table->string('icon_class'); // Contoh: fas fa-user-graduate
            $table->integer('value'); // Angka statistik: 1500
            $table->string('title'); // Peserta Didik
            $table->text('description')->nullable(); // Deskripsi info selengkapnya
            $table->string('link')->nullable(); // Link ke halaman detail
            $table->integer('order')->default(0); // Urutan tampilan
            $table->boolean('is_active')->default(true); // Aktif/non-aktif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_statistics');
    }
};
