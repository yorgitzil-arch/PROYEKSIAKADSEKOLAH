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
        Schema::table('mata_pelajarans', function (Blueprint $table) {
            // Ini adalah baris yang akan menambahkan kolom 'kkm'
            // Pastikan kolom 'kelompok' sudah ada, karena 'after' akan menempatkannya setelah itu.
            $table->integer('kkm')->nullable()->after('kelompok');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mata_pelajarans', function (Blueprint $table) {
            // Ini adalah baris yang akan menghapus kolom 'kkm' jika migrasi di-rollback
            $table->dropColumn('kkm');
        });
    }
};
