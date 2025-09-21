// File: 2025_07_14_170050_add_wali_kelas_id_to_kelas_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            if (!Schema::hasColumn('kelas', 'wali_kelas_id')) {
                $table->foreignId('wali_kelas_id')->nullable()->after('nama_kelas')->constrained('gurus')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            if (Schema::hasColumn('kelas', 'wali_kelas_id')) {
                $table->dropForeign(['wali_kelas_id']);
                $table->dropColumn('wali_kelas_id');
            }
        });
    }
};