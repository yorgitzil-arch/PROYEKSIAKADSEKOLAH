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
            // Menambahkan kolom untuk status kenaikan kelas
            // Default bisa NULL atau string kosong, tergantung kebutuhan
            $table->string('status_kenaikan_kelas')->nullable()->after('peringkat_ke');
            // Menambahkan kolom untuk saran kenaikan kelas (jika ada deskripsi tambahan)
            $table->text('saran_kenaikan_kelas')->nullable()->after('status_kenaikan_kelas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('raports', function (Blueprint $table) {
            $table->dropColumn('status_kenaikan_kelas');
            $table->dropColumn('saran_kenaikan_kelas');
        });
    }
};
