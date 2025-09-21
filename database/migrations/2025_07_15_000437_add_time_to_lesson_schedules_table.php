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
                $table->time('start_time')->after('date'); // Menambahkan setelah kolom 'date'
                $table->time('end_time')->after('start_time'); // Menambahkan setelah kolom 'start_time'
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
                $table->dropColumn('start_time');
                $table->dropColumn('end_time');
            });
        }
    };
    