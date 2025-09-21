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
        Schema::table('school_profiles', function (Blueprint $table) {
            // Menambahkan kolom 'banner_path' setelah kolom 'logo_path'
            // atau bisa di akhir tabel jika tidak peduli urutan.
            $table->string('banner_path')->nullable()->after('logo_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('school_profiles', function (Blueprint $table) {
            // Menghapus kolom 'banner_path' jika migrasi di-rollback
            $table->dropColumn('banner_path');
        });
    }
};