<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Assignment;
use App\Models\TeachingMaterial;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

// Tambahkan model yang mungkin dibutuhkan jika belum ada
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\LessonSchedule;
use App\Models\SchoolProfile; // <--- TAMBAHKAN INI

class JadwalPelajaranController extends Controller
{
    /**
     * Menampilkan jadwal pelajaran untuk siswa yang sedang login.
     * Metode ini tidak diubah dari kode yang Anda berikan.
     */
    public function index()
    {
        $siswa = Auth::guard('siswa')->user();

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        if (!$siswa || !$siswa->kelas_id) {
            return view('siswa.jadwal-pelajaran.index', compact('schoolProfile'))->with('info', 'Anda belum terdaftar di kelas manapun. Silakan lengkapi data diri Anda.'); // <--- Tambahkan 'schoolProfile' di compact
        }

        $kelasId = $siswa->kelas_id;

        // Ambil semua penugasan mengajar yang dikonfirmasi untuk kelas siswa ini
        $jadwalPelajaran = Assignment::where('kelas_id', $kelasId)
            ->where('status_konfirmasi', 'Dikonfirmasi') // Hanya yang sudah dikonfirmasi guru
            ->with(['guru', 'mataPelajaran', 'kelas.jurusan'])
            ->orderBy('kelas_id')
            ->orderBy('mata_pelajaran_id')
            ->paginate(10); // Gunakan paginate()

        return view('siswa.jadwal-pelajaran.index', compact('jadwalPelajaran', 'siswa', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Mengunduh materi ajar (buku mengajar/modul) yang terkait dengan penugasan mengajar.
     * Metode ini tidak diubah dari kode yang Anda berikan.
     */
    public function downloadMaterial(TeachingMaterial $teachingMaterial)
    {
        if ($teachingMaterial->file_path && \Storage::disk('public')->exists($teachingMaterial->file_path)) {
            return \Storage::disk('public')->download($teachingMaterial->file_path, $teachingMaterial->title . '.' . pathinfo($teachingMaterial->file_path, PATHINFO_EXTENSION));
        }

        return redirect()->back()->with('error', 'File materi tidak ditemukan.');
    }

    /**
     * Menampilkan riwayat presensi siswa untuk semua mata pelajaran yang diikutinya.
     * Metode ini disesuaikan untuk memfilter presensi lama dan meningkatkan efisiensi.
     */
    public function showAttendanceHistory()
    {
        $siswa = Auth::guard('siswa')->user();

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        if (!$siswa || !$siswa->kelas_id) {
            return redirect()->route('siswa.jadwal-pelajaran.index')->with('info', 'Anda belum terdaftar di kelas manapun.');
        }

        // Ambil semua penugasan mengajar yang dikonfirmasi untuk kelas siswa ini
        $assignments = Assignment::where('kelas_id', $siswa->kelas_id)
            ->where('status_konfirmasi', 'Dikonfirmasi')
            ->pluck('id');

        // Ambil semua record presensi untuk siswa ini dari assignment yang relevan
        // PENTING: Menggunakan 'date' sebagai kolom tanggal presensi sebenarnya
        $allAttendanceRecords = Attendance::where('siswa_id', $siswa->id)
            ->whereIn('assignment_id', $assignments)
            ->whereNotNull('lesson_schedule_id') // Filter data lama yang tidak terkait LessonSchedule
            ->with('assignment.mataPelajaran') // Eager load relasi
            ->orderBy('date', 'asc') // <--- KOREKSI: DIKOREKSI DARI 'created_at' MENJADI 'date'
            ->get();

        // Siapkan data presensi untuk tampilan tabel
        $attendanceBySubject = [];
        // Kumpulkan semua tanggal unik dari record yang sudah terfilter
        $allDates = $allAttendanceRecords->pluck('date')->map(function($date) { // <--- KOREKSI: DIKOREKSI DARI 'created_at' MENJADI 'date'
            return Carbon::parse($date)->format('Y-m-d');
        })->unique()->sort()->values();

        foreach ($allAttendanceRecords as $record) {
            // Gunakan relasi yang sudah di-eager load
            $subjectName = $record->assignment->mataPelajaran->nama_mapel ?? 'N/A';
            $dateString = Carbon::parse($record->date)->format('Y-m-d'); // <--- KOREKSI: DIKOREKSI DARI 'created_at' MENJADI 'date'

            if (!isset($attendanceBySubject[$subjectName])) {
                $attendanceBySubject[$subjectName] = [
                    'mapel' => $subjectName,
                    'dates' => [],
                    'records' => [], // Untuk menyimpan record asli agar mudah diakses
                ];
            }
            $attendanceBySubject[$subjectName]['records'][$dateString] = [
                'status' => $record->status,
                'keterangan' => $record->keterangan ?? '-',
            ];
        }

        $finalAttendanceData = [];
        foreach ($attendanceBySubject as $subjectName => $data) {
            $finalAttendanceData[$subjectName]['mapel'] = $subjectName;
            foreach ($allDates as $date) {
                // Jika ada record untuk tanggal ini, gunakan statusnya, jika tidak, default ke 'alpha'
                $finalAttendanceData[$subjectName]['dates'][$date] = $data['records'][$date] ?? [
                    'status' => 'alpha',
                    'keterangan' => '-',
                ];
            }
        }

        return view('siswa.jadwal-pelajaran.attendance_history', compact('siswa', 'finalAttendanceData', 'allDates', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }
}