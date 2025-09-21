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
            Schema::table('attendances', function (Blueprint $table) {
                // Tambahkan kolom 'date' setelah 'lesson_schedule_id'
                // Pastikan tipe data dan nullable sesuai kebutuhan Anda.
                // Jika presensi selalu punya tanggal, bisa diubah jadi ->date()->after('lesson_schedule_id');
                $table->date('date')->nullable()->after('lesson_schedule_id');
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::table('attendances', function (Blueprint $table) {
                // Hapus kolom 'date' jika migrasi di-rollback
                $table->dropColumn('date');
            });
        }
    };
    