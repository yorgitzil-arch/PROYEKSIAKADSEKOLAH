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
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama pengirim
            $table->string('email'); // Email pengirim
            $table->string('subject')->nullable(); // Subjek pesan (opsional)
            $table->text('message'); // Isi pesan
            $table->boolean('is_read')->default(false); // Status sudah dibaca atau belum
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};
