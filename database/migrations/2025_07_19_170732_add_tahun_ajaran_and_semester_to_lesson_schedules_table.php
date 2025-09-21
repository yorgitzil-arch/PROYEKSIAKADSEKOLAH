<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lesson_schedules', function (Blueprint $table) {
            // Pastikan kolom belum ada sebelum menambahkannya
            if (!Schema::hasColumn('lesson_schedules', 'tahun_ajaran_id')) {
                $table->foreignId('tahun_ajaran_id')->nullable()->constrained('tahun_ajarans')->onDelete('set null');
            }
            if (!Schema::hasColumn('lesson_schedules', 'semester_id')) {
                $table->foreignId('semester_id')->nullable()->constrained('semesters')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lesson_schedules', function (Blueprint $table) {
            // Hapus foreign key constraint dan kolom hanya jika mereka ada
            if (Schema::hasColumn('lesson_schedules', 'tahun_ajaran_id')) {
                // Pastikan foreign key constraint dihapus sebelum kolom
                // Nama foreign key biasanya 'table_name_column_name_foreign'
                // Anda bisa cek nama pastinya di database atau log error jika masih ada masalah
                $table->dropForeign(['tahun_ajaran_id']);
                $table->dropColumn('tahun_ajaran_id');
            }
            
            if (Schema::hasColumn('lesson_schedules', 'semester_id')) {
                $table->dropForeign(['semester_id']);
                $table->dropColumn('semester_id');
            }
        });
    }
};
