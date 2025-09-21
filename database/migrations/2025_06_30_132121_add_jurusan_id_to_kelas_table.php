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
        Schema::table('kelas', function (Blueprint $table) {
            // Tambahkan kolom jurusan_id
            $table->foreignId('jurusan_id')
                ->nullable() // Izinkan null jika kelas bisa tanpa jurusan di awal
                ->constrained('jurusans') // Foreign key ke tabel 'jurusans'
                ->onDelete('set null'); // Jika jurusan dihapus, set jurusan_id di kelas menjadi null
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            // Hapus foreign key dan kolom jika migrasi di-rollback
            $table->dropConstrainedForeignId('jurusan_id');
            // $table->dropColumn('jurusan_id'); // Ini juga bisa digunakan jika Anda ingin menghapus kolomnya sepenuhnya
        });
    }
};
