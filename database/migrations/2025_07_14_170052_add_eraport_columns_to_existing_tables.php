// File: 2025_07_14_170052_add_eraport_columns_to_existing_tables.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add is_wali_kelas to gurus table
        Schema::table('gurus', function (Blueprint $table) {
            if (!Schema::hasColumn('gurus', 'is_wali_kelas')) {
                $table->boolean('is_wali_kelas')->default(false)->after('password');
            }
        });

        // Add kelompok to mata_pelajarans table
        Schema::table('mata_pelajarans', function (Blueprint $table) {
            if (!Schema::hasColumn('mata_pelajarans', 'kelompok')) {
                $table->string('kelompok')->nullable()->after('nama_mapel');
            }
        });

        // Bagian untuk 'kelas' (guru_wali_kelas_id) DIHAPUS SEPENUHNYA dari migrasi ini
    }

    public function down(): void
    {
        // Drop is_wali_kelas from gurus table
        Schema::table('gurus', function (Blueprint $table) {
            if (Schema::hasColumn('gurus', 'is_wali_kelas')) {
                $table->dropColumn('is_wali_kelas');
            }
        });

        // Drop kelompok from mata_pelajarans table
        Schema::table('mata_pelajarans', function (Blueprint $table) {
            if (Schema::hasColumn('mata_pelajarans', 'kelompok')) {
                $table->dropColumn('kelompok');
            }
        });

        // Bagian untuk 'kelas' (guru_wali_kelas_id) DIHAPUS SEPENUHNYA dari migrasi ini
    }
};