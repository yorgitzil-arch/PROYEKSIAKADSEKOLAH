<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     * Menambahkan kolom-kolom detail ke tabel 'siswas'.
     */
    public function up(): void
    {
        // Pastikan kolom belum ada sebelum menambahkannya
        Schema::table('siswas', function (Blueprint $table) {
            // Kolom untuk data pendaftaran
            if (!Schema::hasColumn('siswas', 'tempat_lahir')) {
                $table->string('tempat_lahir')->nullable()->after('name');
            }
            if (!Schema::hasColumn('siswas', 'tanggal_lahir')) {
                $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            }
            if (!Schema::hasColumn('siswas', 'jenis_kelamin')) {
                $table->string('jenis_kelamin')->nullable()->after('tanggal_lahir'); // L/P
            }
            if (!Schema::hasColumn('siswas', 'agama')) {
                $table->string('agama')->nullable()->after('jenis_kelamin');
            }
            if (!Schema::hasColumn('siswas', 'alamat')) {
                $table->string('alamat')->nullable()->after('agama');
            }
            if (!Schema::hasColumn('siswas', 'nomor_telepon')) {
                $table->string('nomor_telepon')->nullable()->after('alamat');
            }
            if (!Schema::hasColumn('siswas', 'nama_ayah')) {
                $table->string('nama_ayah')->nullable()->after('nomor_telepon');
            }
            if (!Schema::hasColumn('siswas', 'pekerjaan_ayah')) {
                $table->string('pekerjaan_ayah')->nullable()->after('nama_ayah');
            }
            if (!Schema::hasColumn('siswas', 'nama_ibu')) {
                $table->string('nama_ibu')->nullable()->after('pekerjaan_ayah');
            }
            if (!Schema::hasColumn('siswas', 'pekerjaan_ibu')) {
                $table->string('pekerjaan_ibu')->nullable()->after('nama_ibu');
            }

            // Kolom untuk relasi dan status konfirmasi
            // Pastikan tabel 'jurusans' dan 'kelas' sudah ada sebelum menambahkan foreign key
            // Jika Anda menghapus tabel jurusans dan kelas, pastikan migrasi jurusans dan kelas sudah dijalankan duluan
            if (!Schema::hasColumn('siswas', 'jurusan_id')) {
                $table->foreignId('jurusan_id')->nullable()->constrained('jurusans')->onDelete('set null');
            }
            // Kolom kelas_id yang menyebabkan error sebelumnya
            // Jika Anda yakin sudah ada, Anda bisa menghapus baris ini.
            // Namun, jika Anda ingin migrasi ini yang menambahkannya, pastikan tidak ada di DB.
            // Saya tambahkan pengecekan agar tidak duplikat.
            if (!Schema::hasColumn('siswas', 'kelas_id')) {
                $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('set null');
            }
            if (!Schema::hasColumn('siswas', 'wali_kelas_id')) {
                $table->foreignId('wali_kelas_id')->nullable()->constrained('gurus')->onDelete('set null');
            }
            if (!Schema::hasColumn('siswas', 'status')) {
                $table->enum('status', ['pending', 'confirmed'])->default('pending');
            }

            // Kolom untuk jalur dokumen pendukung (nullable string untuk path file)
            if (!Schema::hasColumn('siswas', 'foto_profile_path')) {
                $table->string('foto_profile_path')->nullable();
            }
            if (!Schema::hasColumn('siswas', 'ijazah_path')) {
                $table->string('ijazah_path')->nullable();
            }
            if (!Schema::hasColumn('siswas', 'raport_path')) {
                $table->string('raport_path')->nullable();
            }
            if (!Schema::hasColumn('siswas', 'kk_path')) {
                $table->string('kk_path')->nullable();
            }
            if (!Schema::hasColumn('siswas', 'ktp_ortu_path')) {
                $table->string('ktp_ortu_path')->nullable();
            }
            if (!Schema::hasColumn('siswas', 'akta_lahir_path')) {
                $table->string('akta_lahir_path')->nullable();
            }
            if (!Schema::hasColumn('siswas', 'sk_lulus_path')) {
                $table->string('sk_lulus_path')->nullable(); // Surat Keterangan Lulus
            }
            if (!Schema::hasColumn('siswas', 'kis_path')) {
                $table->string('kis_path')->nullable(); // Kartu Indonesia Sehat
            }
            if (!Schema::hasColumn('siswas', 'kks_path')) {
                $table->string('kks_path')->nullable(); // Kartu Keluarga Sejahtera (atau bantuan sosial lainnya)
            }
            // Tambahkan kolom lain jika diperlukan untuk dokumen
        });
    }

    /**
     * Kembalikan migrasi.
     * Menghapus kolom-kolom yang ditambahkan jika rollback.
     */
    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            // Hapus foreign key terlebih dahulu jika ada
            if (Schema::hasColumn('siswas', 'jurusan_id')) {
                $table->dropForeign(['jurusan_id']);
            }
            if (Schema::hasColumn('siswas', 'kelas_id')) {
                $table->dropForeign(['kelas_id']);
            }
            if (Schema::hasColumn('siswas', 'wali_kelas_id')) {
                $table->dropForeign(['wali_kelas_id']);
            }

            // Hapus kolom-kolom yang ditambahkan jika ada
            $columnsToDrop = [
                'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'alamat',
                'nomor_telepon', 'nama_ayah', 'pekerjaan_ayah', 'nama_ibu', 'pekerjaan_ibu',
                'jurusan_id', 'kelas_id', 'wali_kelas_id', 'status', 'foto_profile_path',
                'ijazah_path', 'raport_path', 'kk_path', 'ktp_ortu_path', 'akta_lahir_path',
                'sk_lulus_path', 'kis_path', 'kks_path',
            ];

            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('siswas', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

