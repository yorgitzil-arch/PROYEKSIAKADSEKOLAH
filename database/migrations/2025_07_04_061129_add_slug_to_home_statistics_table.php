<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSlugToHomeStatisticsTable extends Migration
{
public function up()
{
Schema::table('home_statistics', function (Blueprint $table) {
$table->string('slug')->unique()->after('title')->nullable(); // Tambahkan setelah title
});
}

public function down()
{
Schema::table('home_statistics', function (Blueprint $table) {
$table->dropColumn('slug');
});
}
}
