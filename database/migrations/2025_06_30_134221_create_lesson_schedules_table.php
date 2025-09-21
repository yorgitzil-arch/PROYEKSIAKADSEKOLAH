<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Metode ini akan dijalankan saat Anda menjalankan 'php artisan migrate'.
     */
    public function up(): void
    {
        Schema::create('lesson_schedules', function (Blueprint $table) {
            $table->id(); // Kolom ID auto-increment (primary key)

            // Menghubungkan jadwal presensi spesifik ini ke penugasan mengajar guru (Assignment)
            // Jika Assignment dihapus, jadwal presensi spesifik ini juga akan dihapus.
            $table->foreignId('assignment_id')->constrained('assignments')->onDelete('cascade');

            // Tanggal spesifik di mana presensi akan diisi oleh guru
            $table->date('date');

            $table->timestamps(); // Kolom created_at dan updated_at

            // Menambahkan indeks unik untuk memastikan satu penugasan hanya memiliki
            // satu jadwal presensi spesifik per tanggal yang sama.
            $table->unique(['assignment_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     * Metode ini akan dijalankan saat Anda menjalankan 'php artisan migrate:rollback'.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_schedules'); // Hapus tabel jika di-rollback
    }
};

