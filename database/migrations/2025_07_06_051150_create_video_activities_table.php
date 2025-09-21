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
        Schema::create('video_activities', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Judul video kegiatan
            $table->text('description')->nullable(); // Keterangan/deskripsi video
            // URL video ini akan menampung link YouTube, Vimeo, atau media sosial lain
            // Menggunakan 'text' karena URL bisa sangat panjang, dan mungkin juga ada embed code (jika di masa depan ingin langsung embed)
            $table->text('video_url');
            $table->boolean('is_active')->default(true); // Status aktif/nonaktif
            $table->integer('order')->default(0); // Untuk pengurutan tampilan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_activities');
    }
};
