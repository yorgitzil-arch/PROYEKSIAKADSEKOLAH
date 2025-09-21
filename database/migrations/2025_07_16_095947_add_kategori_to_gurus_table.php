// File: 2025_07_16_095947_add_kategori_to_gurus_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gurus', function (Blueprint $table) {
            if (!Schema::hasColumn('gurus', 'kategori')) {
                $table->enum('kategori', ['PNS', 'Non-PNS'])->default('Non-PNS')->after('nip');
            }
        });
    }

    public function down(): void
    {
        Schema::table('gurus', function (Blueprint $table) {
            if (Schema::hasColumn('gurus', 'kategori')) {
                $table->dropColumn('kategori');
            }
        });
    }
};