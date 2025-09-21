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
            // Hapus kolom yang tidak relevan untuk penugasan mengajar (tugas siswa)
            // Pastikan kolom ini benar-benar ada di tabel Anda sebelum menjalankan dropColumn
            if (Schema::hasColumn('assignments', 'judul_tugas')) {
                $table->dropColumn('judul_tugas');
            }
            if (Schema::hasColumn('assignments', 'deskripsi')) {
                $table->dropColumn('deskripsi');
            }
            if (Schema::hasColumn('assignments', 'tanggal_deadline')) {
                $table->dropColumn('tanggal_deadline');
            }

            // Tambahkan kolom baru untuk tipe mengajar
            // Pastikan kolom ini belum ada sebelum menambahkannya
            if (!Schema::hasColumn('assignments', 'tipe_mengajar')) {
                $table->enum('tipe_mengajar', ['Praktikum', 'Teori'])->after('kelas_id')->nullable(); // Tambahkan nullable() jika ingin bisa null
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            // Kembalikan kolom yang dihapus jika migrasi di-rollback
            // Anda mungkin perlu menyesuaikan tipe data dan nullable sesuai aslinya
            if (!Schema::hasColumn('assignments', 'judul_tugas')) {
                $table->string('judul_tugas')->nullable();
            }
            if (!Schema::hasColumn('assignments', 'deskripsi')) {
                $table->text('deskripsi')->nullable();
            }
            if (!Schema::hasColumn('assignments', 'tanggal_deadline')) {
                $table->date('tanggal_deadline')->nullable();
            }

            // Hapus kolom tipe_mengajar jika migrasi di-rollback
            if (Schema::hasColumn('assignments', 'tipe_mengajar')) {
                $table->dropColumn('tipe_mengajar');
            }
        });
    }
};

