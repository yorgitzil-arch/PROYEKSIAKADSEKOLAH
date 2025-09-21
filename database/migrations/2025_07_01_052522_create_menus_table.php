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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama menu, contoh: "Beranda", "Tentang Kami"
            $table->string('url')->nullable(); // URL tujuan, contoh: "/", "/tentang-kami"
            $table->foreignId('parent_id')->nullable()->constrained('menus')->onDelete('cascade'); // Untuk sub-menu
            $table->integer('order')->default(0); // Urutan tampilan menu
            $table->boolean('is_active')->default(true); // Status aktif/tidak aktif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
