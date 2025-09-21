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
        Schema::create('spp_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: '1 Bulan', '3 Bulan', '6 Bulan'
            $table->decimal('amount', 10, 2); // Jumlah nominal SPP
            $table->integer('duration_in_months'); // Durasi dalam bulan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spp_types');
    }
};
