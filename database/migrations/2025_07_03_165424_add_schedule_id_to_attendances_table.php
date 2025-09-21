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
// Tambahkan kolom schedule_id. Dibuat nullable agar tidak mengganggu data lama yang hanya punya assignment_id.
// Posisi after('assignment_id') agar lebih rapi, tapi opsional.
$table->foreignId('schedule_id')->nullable()->after('assignment_id')->constrained('schedules')->onDelete('set null');
});
}

/**
* Reverse the migrations.
*/
public function down(): void
{
Schema::table('attendances', function (Blueprint $table) {
// Drop foreign key dan kolom saat rollback
$table->dropForeign(['schedule_id']);
$table->dropColumn('schedule_id');
});
}
};
