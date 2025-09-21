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
        Schema::table('raports', function (Blueprint $table) {
            // Menambahkan kolom untuk nama dan NIP Kepala Sekolah
            $table->string('kepala_sekolah_nama')->nullable()->after('tempat_cetak');
            $table->string('kepala_sekolah_nip')->nullable()->after('kepala_sekolah_nama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('raports', function (Blueprint $table) {
            // Menghapus kolom jika migrasi di-rollback
            $table->dropColumn(['kepala_sekolah_nama', 'kepala_sekolah_nip']);
        });
    }
};
