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
            // Menambahkan kolom 'nisn' setelah 'nis'
            // Pastikan 'nisn' bersifat unique dan nullable (jika tidak wajib diisi)
            $table->string('nisn')->unique()->nullable()->after('nis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            // Menghapus kolom 'nisn' jika migrasi di-rollback
            $table->dropColumn('nisn');
        });
    }
};
