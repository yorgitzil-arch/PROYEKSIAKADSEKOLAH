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
        Schema::table('assignments', function (Blueprint $table) {
            // Tambahkan kolom status_konfirmasi
            // Defaultnya 'menunggu', bisa 'dikonfirmasi' atau 'ditolak'
            $table->string('status_konfirmasi')->default('menunggu')->after('kelas_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            // Hapus kolom jika migrasi di-rollback
            $table->dropColumn('status_konfirmasi');
        });
    }
};
