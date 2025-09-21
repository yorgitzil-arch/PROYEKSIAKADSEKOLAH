<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Kelas;
use App\Models\Jurusan;
use Illuminate\Support\Facades\Log;

class FixKelasJurusanCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:kelas-jurusan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Memperbaiki jurusan_id yang NULL di tabel kelas berdasarkan nama kelas.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai perbaikan data jurusan_id di tabel kelas...');
        Log::info('Artisan command fix:kelas-jurusan dimulai.');

        // Ambil semua kelas yang jurusan_id-nya NULL
        $kelasToFix = Kelas::whereNull('jurusan_id')->get();

        if ($kelasToFix->isEmpty()) {
            $this->info('Tidak ada kelas dengan jurusan_id NULL yang ditemukan. Selesai.');
            Log::info('Tidak ada kelas dengan jurusan_id NULL yang ditemukan.');
            return Command::SUCCESS;
        }

        $fixedCount = 0;
        $failedCount = 0;

        foreach ($kelasToFix as $kelas) {
            $namaKelas = $kelas->nama_kelas;
            $jurusanId = null;

            // Logika untuk menentukan jurusan_id berdasarkan nama_kelas
            // Anda bisa menyesuaikan logika ini sesuai pola nama kelas Anda
            if (str_contains(strtoupper($namaKelas), 'AKUNTANSI')) {
                $jurusan = Jurusan::where('nama_jurusan', 'like', '%Akuntansi%')->first();
                if ($jurusan) {
                    $jurusanId = $jurusan->id;
                }
            } elseif (str_contains(strtoupper($namaKelas), 'TKJ')) {
                $jurusan = Jurusan::where('nama_jurusan', 'like', '%Teknik Komputer dan Jaringan%')
                    ->orWhere('kode_jurusan', 'like', '%TKJ%')
                    ->first();
                if ($jurusan) {
                    $jurusanId = $jurusan->id;
                }
            } elseif (str_contains(strtoupper($namaKelas), 'RPL')) {
                $jurusan = Jurusan::where('nama_jurusan', 'like', '%Rekayasa Perangkat Lunak%')
                    ->orWhere('kode_jurusan', 'like', '%RPL%')
                    ->first();
                if ($jurusan) {
                    $jurusanId = $jurusan->id;
                }
            }
            // Tambahkan logika lain jika ada jurusan lain yang perlu diperbaiki
            // elseif (str_contains(strtoupper($namaKelas), 'NAMA_JURUSAN_LAIN')) {
            //     $jurusan = Jurusan::where('nama_jurusan', 'like', '%Nama Jurusan Lain%')->first();
            //     if ($jurusan) {
            //         $jurusanId = $jurusan->id;
            //     }
            // }


            if ($jurusanId) {
                $kelas->jurusan_id = $jurusanId;
                $kelas->save();
                $fixedCount++;
                $this->info("Kelas '{$namaKelas}' berhasil diperbarui dengan jurusan_id: {$jurusanId}.");
                Log::info("Kelas '{$namaKelas}' berhasil diperbarui dengan jurusan_id: {$jurusanId}.");
            } else {
                $failedCount++;
                $this->warn("Tidak dapat menemukan jurusan yang cocok untuk kelas: '{$namaKelas}'.");
                Log::warning("Tidak dapat menemukan jurusan yang cocok untuk kelas: '{$namaKelas}'.");
            }
        }

        $this->info("Proses perbaikan selesai. Total diperbaiki: {$fixedCount}, Gagal: {$failedCount}.");
        Log::info("Artisan command fix:kelas-jurusan selesai. Diperbaiki: {$fixedCount}, Gagal: {$failedCount}.");

        return Command::SUCCESS;
    }
}
