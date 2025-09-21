<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;
use App\Models\Siswa;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\SchoolProfile; // <--- TAMBAHKAN INI

class ScheduleAttendanceController extends Controller
{
    /**
     * Menampilkan daftar jadwal mengajar guru untuk hari ini.
     * Ini akan menjadi titik masuk utama untuk presensi berbasis jadwal.
     */
    public function dailyScheduleOverview(Request $request)
    {
        $guru = Auth::guard('guru')->user();
        $today = Carbon::today();
        // Mendapatkan nama hari dalam bahasa Indonesia (misal: Senin, Selasa)
        $dayOfWeek = $today->isoFormat('dddd');

        $schedules = Schedule::where('guru_id', $guru->id)
            ->where('day_of_week', $dayOfWeek)
            ->with(['mataPelajaran', 'kelas'])
            ->orderBy('start_time')
            ->get();

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('guru.schedule_attendance.daily_overview', compact('schedules', 'today', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menampilkan form untuk mengisi atau mengedit presensi berdasarkan jadwal dan tanggal.
     */
    public function showAttendanceForm(Schedule $schedule, Request $request)
    {
        // Pastikan jadwal ini milik guru yang sedang login
        if ($schedule->guru_id !== Auth::guard('guru')->id()) {
            return redirect()->route('guru.dashboard')->with('error', 'Anda tidak memiliki izin untuk mengakses jadwal ini.');
        }

        $date = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();

        $students = Siswa::where('kelas_id', $schedule->kelas_id)
            ->orderBy('name')
            ->get();

        // Ambil data presensi yang sudah ada untuk schedule_id dan tanggal ini
        $attendanceRecords = Attendance::where('schedule_id', $schedule->id)
            ->where('date', $date)
            ->get()
            ->keyBy('siswa_id');

        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('guru.schedule_attendance.form', compact('schedule', 'students', 'date', 'attendanceRecords', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Menyimpan data presensi siswa dari form untuk jadwal tertentu.
     */
    public function storeAttendance(Request $request, Schedule $schedule)
    {
        // Pastikan jadwal ini milik guru yang sedang login
        if ($schedule->guru_id !== Auth::guard('guru')->id()) {
            return redirect()->route('guru.dashboard')->with('error', 'Anda tidak memiliki izin untuk menyimpan presensi ini.');
        }

        $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.status' => 'required|in:hadir,sakit,izin,alpha',
            'attendance.*.keterangan' => 'nullable|string|max:255',
        ]);

        $date = Carbon::parse($request->date);

        // Hapus semua catatan presensi lama untuk schedule_id dan tanggal ini
        // Ini penting agar guru bisa mengedit presensi yang sudah diisi
        Attendance::where('schedule_id', $schedule->id)
            ->where('date', $date)
            ->delete();

        // Buat catatan presensi baru untuk setiap siswa
        foreach ($request->attendance as $siswaId => $data) {
            Attendance::create([
                'schedule_id' => $schedule->id, // Menggunakan schedule_id
                'siswa_id' => $siswaId,
                'date' => $date,
                'status' => $data['status'],
                'keterangan' => $data['keterangan'] ?? null,
            ]);
        }

        return redirect()->route('guru.schedule_attendance.dailyOverview')->with('success', 'Presensi berhasil disimpan untuk jadwal ' . $schedule->mataPelajaran->nama_mapel . ' kelas ' . $schedule->kelas->nama_kelas . ' pada tanggal ' . $date->translatedFormat('d F Y') . '!');
    }
}