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
        Schema::table('siswas', function (Blueprint $table) {
            // Pastikan kolom `kelas_id` belum ada sebelum menambahkannya
            // Ini mencegah error jika migrasi dijalankan dua kali (misalnya, di fresh tapi sudah ada kolomnya)
            if (!Schema::hasColumn('siswas', 'kelas_id')) {
                // Perhatikan: foreignId() lebih disarankan di Laravel baru,
                // tapi unsignedBigInteger + foreign() juga valid.
                // Jika ingin konsisten dengan foreignId(), ubah baris ini:
                // $table->foreignId('kelas_id')->nullable()->after('password')->constrained('kelas')->onDelete('set null');
                
                // Jika tetap ingin menggunakan unsignedBigInteger:
                $table->unsignedBigInteger('kelas_id')->nullable()->after('password');
                $table->foreign('kelas_id')->references('id')->on('kelas')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            // SOLUSI: Cek keberadaan kolom sebelum mencoba menghapus foreign key dan kolomnya.
            // Ini adalah praktek terbaik untuk mencegah error "Can't DROP FOREIGN KEY" atau "Unknown column".
            if (Schema::hasColumn('siswas', 'kelas_id')) {
                // Drop foreign key terlebih dahulu
                $table->dropForeign(['kelas_id']);
                // Kemudian drop kolom
                $table->dropColumn('kelas_id');
            }
        });
    }
};