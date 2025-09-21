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
                Schema::create('attendances', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('lesson_schedule_id')->constrained('lesson_schedules')->onDelete('cascade'); // Relasi ke jadwal pelajaran
                    $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade'); // Relasi ke siswa
                    $table->foreignId('assignment_id')->constrained('assignments')->onDelete('cascade'); // Relasi ke penugasan (dari lesson_schedule)
                    $table->date('tanggal_presensi'); // Kolom TANGGAL PRESENSI yang benar
                    $table->string('status'); // Contoh: 'Hadir', 'Sakit', 'Izin', 'Alpha'
                    $table->text('keterangan')->nullable(); // Keterangan tambahan (misal: alasan sakit)
                    $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->onDelete('cascade'); // Relasi ke Tahun Ajaran
                    $table->foreignId('semester_id')->constrained('semesters')->onDelete('cascade'); // Relasi ke Semester
                    $table->timestamps();

                    // Tambahkan unique constraint untuk mencegah duplikasi presensi pada tanggal yang sama
                    $table->unique(['lesson_schedule_id', 'siswa_id', 'tanggal_presensi'], 'unique_attendance_per_lesson_siswa_date');
                });
            }

            /**
             * Reverse the migrations.
             *
             * @return void
             */
            public function down()
            {
                Schema::dropIfExists('attendances');
            }
        };
        