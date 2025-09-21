<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->unsignedBigInteger('spp_type_id')->nullable()->after('nisn');
            $table->foreign('spp_type_id')->references('id')->on('spp_types')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropForeign(['spp_type_id']);
            $table->dropColumn('spp_type_id');
        });
    }
};