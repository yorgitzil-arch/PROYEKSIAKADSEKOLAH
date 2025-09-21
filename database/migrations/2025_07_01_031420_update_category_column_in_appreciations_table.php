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
        Schema::table('appreciations', function (Blueprint $table) {
            // Mengubah kolom 'category' menjadi VARCHAR dengan panjang 255
            // Pastikan tidak ada data yang akan terpotong jika ada nilai yang sudah ada
            $table->string('category', 255)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appreciations', function (Blueprint $table) {
            // Mengembalikan ke ENUM atau VARCHAR yang lebih kecil jika diperlukan
            // Hati-hati jika ada data yang lebih panjang dari batasan sebelumnya
            // Anda mungkin perlu menyesuaikan ini jika ada ENUM sebelumnya
            // Contoh: $table->enum('category', ['baik', 'sangat luar biasa', 'buruk'])->change();
            // Atau jika sebelumnya varchar(50)
            // $table->string('category', 50)->change();
            // Untuk amannya, kita bisa mengembalikan ke varchar yang lebih kecil jika tidak ada data yang lebih panjang
            $table->string('category', 50)->change(); // Ganti 50 dengan panjang yang sesuai jika sebelumnya lebih kecil
        });
    }
};
