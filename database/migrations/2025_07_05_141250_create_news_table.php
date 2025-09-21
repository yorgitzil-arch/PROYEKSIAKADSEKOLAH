<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique(); // Untuk URL yang rapi (SEO friendly)
            $table->string('image_path')->nullable(); // Path gambar berita
            $table->text('short_description')->nullable(); // Deskripsi singkat
            $table->longText('content'); // Konten berita lengkap
            $table->string('source_url')->nullable(); // Link/URL Berita asli (opsional)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news');
    }
}
