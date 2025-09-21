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
            $table->foreignId('tahun_ajaran_id')->after('kelas_id')->nullable()->constrained('tahun_ajarans')->onDelete('restrict');
            $table->foreignId('semester_id')->after('tahun_ajaran_id')->nullable()->constrained('semesters')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropForeign(['tahun_ajaran_id']);
            $table->dropColumn('tahun_ajaran_id');
            $table->dropForeign(['semester_id']);
            $table->dropColumn('semester_id');
        });
    }
};