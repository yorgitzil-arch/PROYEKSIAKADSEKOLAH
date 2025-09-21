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
    public function up(): void
    {
        // Tambahkan kolom ke nilai_akademik
        Schema::table('nilai_akademik', function (Blueprint $table) {
            if (!Schema::hasColumn('nilai_akademik', 'mata_pelajaran_id')) {
                // KOREKSI INI: TAMBAHKAN 's' di 'mata_pelajarans'
                $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajarans')->onDelete('cascade')->after('assignment_id');
            }
            if (!Schema::hasColumn('nilai_akademik', 'kkm')) {
                $table->integer('kkm')->nullable()->after('nilai');
            }
            if (!Schema::hasColumn('nilai_akademik', 'nilai_predikat')) {
                $table->string('nilai_predikat')->nullable()->after('kkm');
            }
            if (!Schema::hasColumn('nilai_akademik', 'keterangan')) {
                $table->text('keterangan')->nullable()->after('nilai_predikat');
            }
            if (!Schema::hasColumn('nilai_akademik', 'created_by_guru_id')) {
                $table->foreignId('created_by_guru_id')->nullable()->constrained('gurus')->onDelete('set null');
            }
        });

        // Tambahkan kolom ke nilai_keterampilan
        Schema::table('nilai_keterampilan', function (Blueprint $table) {
            if (!Schema::hasColumn('nilai_keterampilan', 'mata_pelajaran_id')) {
                // KOREKSI INI: TAMBAHKAN 's' di 'mata_pelajarans'
                $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajarans')->onDelete('cascade')->after('assignment_id');
            }
            if (!Schema::hasColumn('nilai_keterampilan', 'created_by_guru_id')) {
                $table->foreignId('created_by_guru_id')->nullable()->constrained('gurus')->onDelete('set null');
            }
        });

        // Tambahkan kolom ke nilai_sikap
        Schema::table('nilai_sikap', function (Blueprint $table) {
            if (!Schema::hasColumn('nilai_sikap', 'mata_pelajaran_id')) {
                // KOREKSI INI: TAMBAHKAN 's' di 'mata_pelajarans'
                $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajarans')->onDelete('cascade')->after('assignment_id');
            }
            if (!Schema::hasColumn('nilai_sikap', 'created_by_guru_id')) {
                $table->foreignId('created_by_guru_id')->nullable()->constrained('gurus')->onDelete('set null');
            }
            // KOREKSI UNIQUE CONSTRAINT UNTUK NILAI SIKAP
            // Jika Anda ingin sikap unik per siswa, per mata pelajaran, per jenis sikap, per semester,
            // dan assignment_id bisa nullable, maka constraint yang lebih tepat adalah:
            // $table->unique(['siswa_id', 'mata_pelajaran_id', 'jenis_sikap', 'semester_id', 'tahun_ajaran_id'], 'nilai_sikap_unique_per_period_fixed');
            // Jika unique constraint lama Anda sudah ada dan berfungsi, biarkan saja.
            // Saya tidak akan menambahkan drop/add unique constraint di sini untuk menghindari masalah.
            // Pastikan logika controller sesuai dengan unique constraint yang ada.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('nilai_akademik', function (Blueprint $table) {
            if (Schema::hasColumn('nilai_akademik', 'mata_pelajaran_id')) {
                $table->dropForeign(['mata_pelajaran_id']);
                $table->dropColumn('mata_pelajaran_id');
            }
            if (Schema::hasColumn('nilai_akademik', 'kkm')) {
                $table->dropColumn('kkm');
            }
            if (Schema::hasColumn('nilai_akademik', 'nilai_predikat')) {
                $table->dropColumn('nilai_predikat');
            }
            if (Schema::hasColumn('nilai_akademik', 'keterangan')) {
                $table->dropColumn('keterangan');
            }
            if (Schema::hasColumn('nilai_akademik', 'created_by_guru_id')) {
                $table->dropForeign(['created_by_guru_id']);
                $table->dropColumn('created_by_guru_id');
            }
        });

        Schema::table('nilai_keterampilan', function (Blueprint $table) {
            if (Schema::hasColumn('nilai_keterampilan', 'mata_pelajaran_id')) {
                $table->dropForeign(['mata_pelajaran_id']);
                $table->dropColumn('mata_pelajaran_id');
            }
            if (Schema::hasColumn('nilai_keterampilan', 'created_by_guru_id')) {
                $table->dropForeign(['created_by_guru_id']);
                $table->dropColumn('created_by_guru_id');
            }
        });

        Schema::table('nilai_sikap', function (Blueprint $table) {
            if (Schema::hasColumn('nilai_sikap', 'mata_pelajaran_id')) {
                $table->dropForeign(['mata_pelajaran_id']);
                $table->dropColumn('mata_pelajaran_id');
            }
            if (Schema::hasColumn('nilai_sikap', 'created_by_guru_id')) {
                $table->dropForeign(['created_by_guru_id']);
                $table->dropColumn('created_by_guru_id');
            }
        });
    }
};
