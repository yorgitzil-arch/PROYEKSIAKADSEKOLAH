<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\Semester;
use App\Models\RekapNilaiMapel;
use App\Models\PresensiAkhir;
use App\Models\CatatanWaliKelas;
use App\Models\Raport;
use App\Models\MataPelajaran;
use App\Models\Assignment;
use App\Models\NilaiAkademik;
use App\Models\NilaiKeterampilan;
use App\Models\NilaiSikap;
use App\Models\Admin;
use App\Models\RaportEkstrakurikuler;
use App\Models\SchoolProfile; 
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use PDF;

class RaportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:guru');
        $this->middleware(function ($request, $next) {
            if (!Auth::guard('guru')->user()->is_wali_kelas) {
                return redirect()->route('guru.dashboard')->with('error', 'Anda tidak memiliki akses sebagai wali kelas.');
            }
            return $next($request);
        });
    }

    /**
     * Menampilkan daftar siswa di kelas wali kelas yang sedang aktif.
     * NISN siswa akan tersedia di objek $siswa untuk ditampilkan di view.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $guru = Auth::guard('guru')->user();
            $kelasWali = $guru->kelasWali;

            // --- Tambahkan ini untuk $schoolProfile ---
            $schoolProfile = SchoolProfile::firstOrCreate([]);
            // -----------------------------------------

            if (!$kelasWali) {
                return redirect()->back()->with('error', 'Anda belum diatur sebagai wali kelas untuk kelas manapun.');
            }

            $activeTahunAjaran = TahunAjaran::where('is_active', true)->firstOrFail();
            $activeSemester = Semester::where('is_active', true)->firstOrFail();

            $siswas = Siswa::where('kelas_id', $kelasWali->id)
                               ->orderBy('name')
                               ->get();

            return view('wali_kelas.raport.index', compact('siswas', 'kelasWali', 'activeTahunAjaran', 'activeSemester', 'schoolProfile')); // <--- Tambahkan 'schoolProfile'
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error("Tahun Ajaran atau Semester aktif tidak ditemukan: " . $e->getMessage());
            // --- Tambahkan ini untuk $schoolProfile jika terjadi error sebelum di-load ---
            $schoolProfile = SchoolProfile::firstOrCreate([]);
            // --------------------------------------------------------------------------
            return redirect()->back()->with('error', 'Tahun Ajaran atau Semester aktif tidak ditemukan. Harap hubungi administrator.')->with(compact('schoolProfile')); // <--- Tambahkan 'schoolProfile'
        } catch (\Exception $e) {
            Log::error("Error in WaliKelas RaportController index: " . $e->getMessage());
            // --- Tambahkan ini untuk $schoolProfile jika terjadi error sebelum di-load ---
            $schoolProfile = SchoolProfile::firstOrCreate([]);
            // --------------------------------------------------------------------------
            return redirect()->back()->with('error', 'Gagal memuat data rapor. ' . $e->getMessage())->with(compact('schoolProfile')); // <--- Tambahkan 'schoolProfile'
        }
    }

    /**
     * Menampilkan detail rapor untuk siswa tertentu.
     * NISN siswa akan tersedia di objek $siswa untuk ditampilkan di view.
     *
     * @param  \App\Models\Siswa  $siswa
     * @return \Illuminate\Http\Response
     */
    public function show(Siswa $siswa)
    {
        try {
            $guru = Auth::guard('guru')->user();
            $kelasWali = $guru->kelasWali;

            // --- Tambahkan ini untuk $schoolProfile ---
            $schoolProfile = SchoolProfile::firstOrCreate([]);
            // -----------------------------------------

            if (!$kelasWali || $siswa->kelas_id !== $kelasWali->id) {
                return redirect()->back()->with('error', 'Siswa ini bukan dari kelas yang Anda ampu sebagai wali kelas.');
            }

            $activeTahunAjaran = TahunAjaran::where('is_active', true)->firstOrFail();
            $activeSemester = Semester::where('is_active', true)->firstOrFail();

            // Eager load relasi ekstrakurikuler untuk rapor
            $raport = Raport::with('ekstrakurikulerRaport') // Load relasi ekstrakurikuler
                                ->where('siswa_id', $siswa->id)
                                ->where('tahun_ajaran_id', $activeTahunAjaran->id)
                                ->where('semester_id', $activeSemester->id)
                                ->first();

            // Eager load mataPelajaran untuk mendapatkan kelompok
            $rekapNilaiMapel = RekapNilaiMapel::with(['mataPelajaran', 'guruPengampu'])
                                            ->where('siswa_id', $siswa->id)
                                            ->where('tahun_ajaran_id', $activeTahunAjaran->id)
                                            ->where('semester_id', $activeSemester->id)
                                            ->get();

            $presensiAkhir = PresensiAkhir::where('siswa_id', $siswa->id)
                                            ->where('tahun_ajaran_id', $activeTahunAjaran->id)
                                            ->where('semester_id', $activeSemester->id)
                                            ->first();

            $catatanWaliKelas = CatatanWaliKelas::where('siswa_id', $siswa->id)
                                                ->where('tahun_ajaran_id', $activeTahunAjaran->id)
                                                ->where('semester_id', $activeSemester->id)
                                                ->first();

            // Data ekstrakurikuler untuk form (akan kosong jika belum ada)
            $ekstrakurikulerRaport = $raport ? $raport->ekstrakurikulerRaport : collect();


            return view('wali_kelas.raport.show', compact(
                'siswa',
                'raport',
                'rekapNilaiMapel',
                'presensiAkhir',
                'catatanWaliKelas',
                'kelasWali',
                'activeTahunAjaran',
                'activeSemester',
                'ekstrakurikulerRaport',
                'schoolProfile' // <--- Tambahkan 'schoolProfile'
            ));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error("Tahun Ajaran atau Semester aktif tidak ditemukan: " . $e->getMessage());
            // --- Tambahkan ini untuk $schoolProfile jika terjadi error sebelum di-load ---
            $schoolProfile = SchoolProfile::firstOrCreate([]);
            // --------------------------------------------------------------------------
            return redirect()->back()->with('error', 'Tahun Ajaran atau Semester aktif tidak ditemukan. Harap hubungi administrator.')->with(compact('schoolProfile')); // <--- Tambahkan 'schoolProfile'
        } catch (\Exception $e) {
            Log::error("Error in WaliKelas RaportController show: " . $e->getMessage());
            // --- Tambahkan ini untuk $schoolProfile jika terjadi error sebelum di-load ---
            $schoolProfile = SchoolProfile::firstOrCreate([]);
            // --------------------------------------------------------------------------
            return redirect()->back()->with('error', 'Gagal memuat detail rapor. ' . $e->getMessage())->with(compact('schoolProfile')); // <--- Tambahkan 'schoolProfile'
        }
    }

    /**
     * Menyimpan atau memperbarui data presensi akhir siswa.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Siswa  $siswa
     * @return \Illuminate\Http\Response
     */
    public function storePresensi(Request $request, Siswa $siswa)
    {
        $guru = Auth::guard('guru')->user();
        $kelasWali = $guru->kelasWali;

        if (!$kelasWali || $siswa->kelas_id !== $kelasWali->id) {
            return redirect()->back()->with('error', 'Siswa ini bukan dari kelas yang Anda ampu sebagai wali kelas.');
        }

        $activeTahunAjaran = TahunAjaran::where('is_active', true)->firstOrFail();
        $activeSemester = Semester::where('is_active', true)->firstOrFail();

        $request->validate([
            'sakit' => 'required|integer|min:0',
            'izin' => 'required|integer|min:0',
            'alpha' => 'required|integer|min:0',
        ]);

        try {
            PresensiAkhir::updateOrCreate(
                [
                    'siswa_id' => $siswa->id,
                    'tahun_ajaran_id' => $activeTahunAjaran->id,
                    'semester_id' => $activeSemester->id,
                ],
                [
                    'sakit' => $request->sakit,
                    'izin' => $request->izin,
                    'alpha' => $request->alpha,
                    'created_by_guru_id' => $guru->id,
                ]
            );

            return redirect()->back()->with('success', 'Data presensi akhir berhasil disimpan.');
        } catch (\Exception $e) {
            Log::error("Error saving presensi akhir: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyimpan presensi akhir: ' . $e->getMessage());
        }
    }

    /**
     * Menyimpan atau memperbarui catatan wali kelas.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Siswa  $siswa
     * @return \Illuminate\Http\Response
     */
    public function storeCatatan(Request $request, Siswa $siswa)
    {
        $guru = Auth::guard('guru')->user();
        $kelasWali = $guru->kelasWali;

        if (!$kelasWali || $siswa->kelas_id !== $kelasWali->id) {
            return redirect()->back()->with('error', 'Siswa ini bukan dari kelas yang Anda ampu sebagai wali kelas.');
        }

        $activeTahunAjaran = TahunAjaran::where('is_active', true)->firstOrFail();
        $activeSemester = Semester::where('is_active', true)->firstOrFail();

        $request->validate([
            'catatan' => 'nullable|string|max:2000',
        ]);

        try {
            CatatanWaliKelas::updateOrCreate(
                [
                    'siswa_id' => $siswa->id,
                    'tahun_ajaran_id' => $activeTahunAjaran->id,
                    'semester_id' => $activeSemester->id,
                ],
                [
                    'catatan' => $request->catatan,
                    'created_by_guru_id' => $guru->id,
                ]
            );

            return redirect()->back()->with('success', 'Catatan wali kelas berhasil disimpan.');
        } catch (\Exception $e) {
            Log::error("Error saving catatan wali kelas: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyimpan catatan wali kelas: ' . $e->getMessage());
        }
    }

    /**
     * Menyimpan atau memperbarui data Kepala Sekolah, Status Kenaikan Kelas, dan Info Cetak Rapor.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Siswa  $siswa
     * @return \Illuminate\Http\Response
     */
    public function storeKepalaSekolah(Request $request, Siswa $siswa)
    {
        $guru = Auth::guard('guru')->user();
        $kelasWali = $guru->kelasWali;

        if (!$kelasWali || $siswa->kelas_id !== $kelasWali->id) {
            return redirect()->back()->with('error', 'Siswa ini bukan dari kelas yang Anda ampu sebagai wali kelas.');
        }

        $activeTahunAjaran = TahunAjaran::where('is_active', true)->firstOrFail();
        $activeSemester = Semester::where('is_active', true)->firstOrFail();

        $request->validate([
            'kepala_sekolah_nama' => 'nullable|string|max:255',
            'kepala_sekolah_nip' => 'nullable|string|max:255',
            'status_kenaikan_kelas' => ['nullable', 'string', Rule::in(['Naik Kelas', 'Tinggal Kelas', 'Lulus', 'Tidak Lulus'])],
            'saran_kenaikan_kelas' => 'nullable|string|max:2000',
            'tempat_cetak' => 'required|string|max:255', // Validasi baru
            'tanggal_cetak' => 'required|date_format:d-m-Y', // Validasi baru
        ]);

        try {
            // Temukan atau buat rapor utama siswa
            $raport = Raport::firstOrNew(
                [
                    'siswa_id' => $siswa->id,
                    'kelas_id' => $kelasWali->id,
                    'tahun_ajaran_id' => $activeTahunAjaran->id,
                    'semester_id' => $activeSemester->id,
                ]
            );

            // --- KOREKSI PENTING DIMULAI DI SINI ---
            // Tugaskan nilai-nilai dari request ke objek $raport
            $raport->kepala_sekolah_nama = $request->kepala_sekolah_nama;
            $raport->kepala_sekolah_nip = $request->kepala_sekolah_nip;
            $raport->status_kenaikan_kelas = $request->status_kenaikan_kelas;
            $raport->saran_kenaikan_kelas = $request->saran_kenaikan_kelas;
            $raport->tempat_cetak = $request->tempat_cetak;
            
            // Konversi tanggal dari format d-m-Y ke Y-m-d untuk database
            $raport->tanggal_cetak = Carbon::createFromFormat('d-m-Y', $request->tanggal_cetak)->format('Y-m-d');
            // --- KOREKSI PENTING BERAKHIR DI SINI ---

            // Pastikan wali_kelas_id terisi jika baru dibuat (logika ini sudah benar)
            if (is_null($raport->id)) { // Jika ini adalah record baru
                $raport->wali_kelas_id = $guru->id;
                $raport->status_final = false;
            }

            $raport->save(); // Simpan perubahan pada rapor utama

            return redirect()->back()->with('success', 'Data Kepala Sekolah, Status Kenaikan Kelas, dan Info Cetak Rapor berhasil disimpan.');
        } catch (\Exception $e) {
            Log::error("Error saving Kepala Sekolah data, promotion status, or print info: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyimpan data Rapor Utama: ' . $e->getMessage());
        }
    }

    /**
     * Menyimpan atau memperbarui data Ekstrakurikuler.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Siswa  $siswa
     * @return \Illuminate\Http\Response
     */
    public function storeEkstrakurikuler(Request $request, Siswa $siswa)
    {
        $guru = Auth::guard('guru')->user();
        $kelasWali = $guru->kelasWali;

        if (!$kelasWali || $siswa->kelas_id !== $kelasWali->id) {
            return redirect()->back()->with('error', 'Siswa ini bukan dari kelas yang Anda ampu sebagai wali kelas.');
        }

        $activeTahunAjaran = TahunAjaran::where('is_active', true)->firstOrFail();
        $activeSemester = Semester::where('is_active', true)->firstOrFail();

        $request->validate([
            'ekskul.*.nama_ekskul' => 'required|string|max:255',
            'ekskul.*.jenis_ekskul' => ['required', Rule::in(['Wajib', 'Pilihan'])],
            'ekskul.*.predikat' => ['required', Rule::in(['A', 'B', 'C', 'D'])],
        ]);

        try {
            // Dapatkan atau buat rapor utama siswa
            $raport = Raport::firstOrNew( // Menggunakan firstOrNew
                [
                    'siswa_id' => $siswa->id,
                    'kelas_id' => $kelasWali->id,
                    'tahun_ajaran_id' => $activeTahunAjaran->id,
                    'semester_id' => $activeSemester->id,
                ]
            );

            // Simpan nilai tempat_cetak dan tanggal_cetak yang sudah ada sebelum update
            // Catatan: Jika Anda ingin menyimpan nilai-nilai ini dari form ekstrakurikuler,
            // Anda harus menambahkannya ke validasi dan menugaskannya di sini.
            // Saat ini, nilai-nilai ini diasumsikan diatur oleh storeKepalaSekolah.
            $existingTempatCetak = $raport->tempat_cetak;
            $existingTanggalCetak = $raport->tanggal_cetak;

            // Pastikan wali_kelas_id terisi jika baru dibuat
            if (is_null($raport->id)) { // Jika ini adalah record baru
                $raport->wali_kelas_id = $guru->id;
                $raport->status_final = false;
                // Jangan set tanggal_cetak/tempat_cetak di sini jika baru, biarkan storeKepalaSekolah yang mengisi
            }

            // Kembalikan nilai tempat_cetak dan tanggal_cetak yang sudah ada
            $raport->tempat_cetak = $existingTempatCetak;
            $raport->tanggal_cetak = $existingTanggalCetak;

            $raport->save(); // Simpan perubahan pada rapor utama


            // Proses setiap item ekstrakurikuler dari request
            foreach ($request->ekskul as $ekskulData) {
                RaportEkstrakurikuler::updateOrCreate(
                    [
                        'id' => $ekskulData['id'] ?? null, // Gunakan ID jika ada untuk update
                        'raport_id' => $raport->id,
                        'nama_ekskul' => $ekskulData['nama_ekskul'], // Unique constraint
                    ],
                    [
                        'jenis_ekskul' => $ekskulData['jenis_ekskul'],
                        'predikat' => $ekskulData['predikat'],
                    ]
                );
            }

            return redirect()->back()->with('success', 'Data Ekstrakurikuler berhasil disimpan/diperbarui.');
        } catch (\Exception $e) {
            Log::error("Error saving Ekstrakurikuler data: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyimpan data Ekstrakurikuler: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus data ekstrakurikuler.
     * Dipanggil via AJAX dari Blade.
     *
     * @param  \App\Models\RaportEkstrakurikuler  $ekstrakurikuler
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyEkstrakurikuler(RaportEkstrakurikuler $ekstrakurikuler)
    {
        try {
            // Pastikan wali kelas yang login berhak menghapus ini
            // Anda bisa menambahkan logika otorisasi lebih lanjut di sini
            // Misalnya, cek apakah raport_id terkait dengan kelas wali kelas
            $guru = Auth::guard('guru')->user();
            $kelasWali = $guru->kelasWali;

            if (!$kelasWali || $ekstrakurikuler->raport->kelas_id !== $kelasWali->id) {
                return response()->json(['error' => 'Anda tidak memiliki akses untuk menghapus ekstrakurikuler ini.'], 403);
            }

            $ekstrakurikuler->delete();
            Log::info("Ekstrakurikuler deleted: ID {$ekstrakurikuler->id}");
            return response()->json(['success' => 'Ekstrakurikuler berhasil dihapus.']);
        } catch (\Exception $e) {
            Log::error("Error deleting Ekstrakurikuler: " . $e->getMessage());
            return response()->json(['error' => 'Gagal menghapus ekstrakurikuler: ' . $e->getMessage()], 500);
        }
    }


    /**
     * Menghitung dan menyimpan rekap nilai mata pelajaran.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Siswa  $siswa
     * @return \Illuminate\Http\Response
     */
    public function rekapNilai(Request $request, Siswa $siswa)
    {
        $guru = Auth::guard('guru')->user();
        $kelasWali = $guru->kelasWali;

        if (!$kelasWali || $siswa->kelas_id !== $kelasWali->id) {
            return redirect()->back()->with('error', 'Siswa ini bukan dari kelas yang Anda ampu sebagai wali kelas.');
        }

        $activeTahunAjaran = TahunAjaran::where('is_active', true)->firstOrFail();
        $activeSemester = Semester::where('is_active', true)->firstOrFail();

        $assignments = Assignment::where('kelas_id', $kelasWali->id)
                                   ->where('tahun_ajaran_id', $activeTahunAjaran->id)
                                   ->where('semester_id', $activeSemester->id)
                                   ->with('mataPelajaran')
                                   ->get();

        if ($assignments->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada penugasan yang ditemukan untuk kelas ini di tahun ajaran dan semester aktif.');
        }

        try {
            foreach ($assignments as $assignment) {
                // Hitung rata-rata nilai akademik
                $rataRataAkademik = NilaiAkademik::where('siswa_id', $siswa->id)
                                                    ->where('assignment_id', $assignment->id)
                                                    ->where('mata_pelajaran_id', $assignment->mata_pelajaran_id)
                                                    ->avg('nilai');

                // Hitung rata-rata nilai keterampilan
                $rataRataKeterampilan = NilaiKeterampilan::where('siswa_id', $siswa->id)
                                                            ->where('assignment_id', $assignment->id)
                                                            ->where('mata_pelajaran_id', $assignment->mata_pelajaran_id)
                                                            ->avg('nilai');

                // Ambil DESKRIPSI nilai sikap (spiritual dan sosial)
                $nilaiSikapSpiritual = NilaiSikap::where('siswa_id', $siswa->id)
                                                    ->where('mata_pelajaran_id', $assignment->mata_pelajaran_id)
                                                    ->where('jenis_sikap', 'spiritual')
                                                    ->where('tahun_ajaran_id', $activeTahunAjaran->id)
                                                    ->where('semester_id', $activeSemester->id)
                                                    ->first();

                $nilaiSikapSosial = NilaiSikap::where('siswa_id', $siswa->id)
                                                ->where('mata_pelajaran_id', $assignment->mata_pelajaran_id)
                                                ->where('jenis_sikap', 'sosial')
                                                ->where('tahun_ajaran_id', $activeTahunAjaran->id)
                                                ->where('semester_id', $activeSemester->id)
                                                ->first();

                // Tentukan predikat nilai akhir (contoh: berdasarkan rata-rata akademik)
                $kkmMapel = $assignment->mataPelajaran->kkm ?? 75; // Ambil KKM dari mapel
                $predikatAkademik = $rataRataAkademik ? $this->determinePredikat($rataRataAkademik, $kkmMapel) : null;
                $predikatKeterampilan = $rataRataKeterampilan ? $this->determinePredikat($rataRataKeterampilan, $kkmMapel) : null;

                // KOREKSI PENTING: Ambil DESKRIPSI dari objek NilaiSikap, lalu konversi menjadi predikat
                $predikatSikapSpiritual = $nilaiSikapSpiritual ? $this->determineSikapPredikat($nilaiSikapSpiritual->deskripsi) : null;
                $predikatSikapSosial = $nilaiSikapSosial ? $this->determineSikapPredikat($nilaiSikapSosial->deskripsi) : null;


                RekapNilaiMapel::updateOrCreate(
                    [
                        'siswa_id' => $siswa->id,
                        'mapel_id' => $assignment->mata_pelajaran_id,
                        'tahun_ajaran_id' => $activeTahunAjaran->id,
                        'semester_id' => $activeSemester->id,
                    ],
                    [
                        'guru_pengampu_id' => $assignment->guru_id,
                        'kelas_id' => $kelasWali->id,
                        'kkm_mapel' => $kkmMapel,
                        'nilai_pengetahuan_angka' => round($rataRataAkademik ?? 0),
                        'nilai_pengetahuan_predikat' => $predikatAkademik,
                        'deskripsi_pengetahuan' => 'Deskripsi pengetahuan otomatis dari rata-rata nilai akademik.',
                        'nilai_keterampilan_angka' => round($rataRataKeterampilan ?? 0),
                        'nilai_keterampilan_predikat' => $predikatKeterampilan,
                        'deskripsi_keterampilan' => 'Deskripsi keterampilan otomatis dari rata-rata nilai keterampilan.',
                        // KOREKSI: Simpan predikat yang sudah dikonversi
                        'nilai_sikap_spiritual_predikat' => $predikatSikapSpiritual,
                        'deskripsi_sikap_spiritual' => $nilaiSikapSpiritual ? $nilaiSikapSpiritual->deskripsi : null,
                        'nilai_sikap_sosial_predikat' => $predikatSikapSosial,
                        'deskripsi_sikap_sosial' => $nilaiSikapSosial ? $nilaiSikapSosial->deskripsi : null,
                        'created_by_guru_id' => $guru->id,
                    ]
                );
            }
            return redirect()->back()->with('success', 'Rekapitulasi nilai mata pelajaran berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error("Error rekapitulasi nilai: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal melakukan rekapitulasi nilai: ' . $e->getMessage());
        }
    }

    /**
     * Menggenerate atau memperbarui data Raport utama.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Siswa  $siswa
     * @return \Illuminate\Http\Response
     */
    public function generateRaport(Request $request, Siswa $siswa)
    {
        $guru = Auth::guard('guru')->user();
        $kelasWali = $guru->kelasWali;

        if (!$kelasWali || $siswa->kelas_id !== $kelasWali->id) {
            return redirect()->back()->with('error', 'Siswa ini bukan dari kelas yang Anda ampu sebagai wali kelas.');
        }

        $activeTahunAjaran = TahunAjaran::where('is_active', true)->firstOrFail();
        $activeSemester = Semester::where('is_active', true)->firstOrFail();

        try {
            $rataRataNilai = RekapNilaiMapel::where('siswa_id', $siswa->id)
                                            ->where('tahun_ajaran_id', $activeTahunAjaran->id)
                                            ->where('semester_id', $activeSemester->id)
                                            ->avg('nilai_pengetahuan_angka');

            $presensi = PresensiAkhir::where('siswa_id', $siswa->id)
                                        ->where('tahun_ajaran_id', $activeTahunAjaran->id)
                                        ->where('semester_id', $activeSemester->id)
                                        ->first();

            $catatan = CatatanWaliKelas::where('siswa_id', $siswa->id)
                                        ->where('tahun_ajaran_id', $activeTahunAjaran->id)
                                        ->where('semester_id', $activeSemester->id)
                                        ->first();

            // Ambil rapor yang sudah ada atau buat instance baru
            $raport = Raport::firstOrNew(
                [
                    'siswa_id' => $siswa->id,
                    'kelas_id' => $kelasWali->id,
                    'tahun_ajaran_id' => $activeTahunAjaran->id,
                    'semester_id' => $activeSemester->id,
                ]
            );

            // Simpan nilai tempat_cetak dan tanggal_cetak yang sudah ada
            // Ini penting agar nilai yang diinput manual tidak ditimpa
            $existingTempatCetak = $raport->tempat_cetak;
            $existingTanggalCetak = $raport->tanggal_cetak;

            // Update atribut rapor
            $raport->wali_kelas_id = $guru->id;
            $raport->catatan_wali_kelas = $catatan->catatan ?? null;
            $raport->jumlah_sakit = $presensi->sakit ?? 0;
            $raport->jumlah_izin = $presensi->izin ?? 0;
            $raport->jumlah_alfa = $presensi->alpha ?? 0;
            $raport->rata_rata_nilai = round($rataRataNilai ?? 0);
            $raport->status_final = false; // Saat digenerate/diperbarui, status kembali ke draft
            $raport->peringkat_ke = null; // Peringkat biasanya dihitung saat finalisasi

            // Kembalikan nilai tempat_cetak dan tanggal_cetak yang sudah ada
            $raport->tempat_cetak = $existingTempatCetak;
            $raport->tanggal_cetak = $existingTanggalCetak;

            $raport->save(); // Simpan perubahan

            return redirect()->back()->with('success', 'Data rapor berhasil digenerate/diperbarui.');
        } catch (\Exception $e) {
            Log::error("Error generating raport: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menggenerate rapor: ' . $e->getMessage());
        }
    }

    /**
     * Menandai rapor sebagai final.
     *
     * @param  \App\Models\Siswa  $siswa
     * @return \Illuminate\Http\Response
     */
    public function finalisasiRaport(Siswa $siswa)
    {
        $guru = Auth::guard('guru')->user();
        $kelasWali = $guru->kelasWali;

        if (!$kelasWali || $siswa->kelas_id !== $kelasWali->id) {
            return redirect()->back()->with('error', 'Siswa ini bukan dari kelas yang Anda ampu sebagai wali kelas.');
        }

        $activeTahunAjaran = TahunAjaran::where('is_active', true)->firstOrFail();
        $activeSemester = Semester::where('is_active', true)->firstOrFail();

        try {
            $raport = Raport::where('siswa_id', $siswa->id)
                                ->where('tahun_ajaran_id', $activeTahunAjaran->id)
                                ->where('semester_id', $activeSemester->id)
                                ->firstOrFail();

            $raport->update(['status_final' => true]);

            return redirect()->back()->with('success', 'Raport berhasil difinalisasi.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Raport tidak ditemukan untuk siswa ini.');
        } catch (\Exception $e) {
            Log::error("Error finalisasi raport: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memfinalisasi raport: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan tampilan cetak rapor.
     * NISN siswa akan tersedia di objek $siswa dan harus ditampilkan di PDF.
     *
     * @param  \App\Models\Siswa  $siswa
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function printRaport(Siswa $siswa)
    {
        try {
            $guru = Auth::guard('guru')->user();
            
            // Mengambil ID guru yang login
            $guruId = $guru->id;

            // Mengambil kelas wali secara manual (seperti yang kita uji dan berhasil)
            $kelasWali = Kelas::where('wali_kelas_id', $guruId)->first();
            
            if (!$kelasWali || $siswa->kelas_id !== $kelasWali->id) {
                return redirect()->back()->with('error', 'Siswa ini bukan dari kelas yang Anda ampu sebagai wali kelas atau wali kelas tidak ditemukan untuk kelas ini.');
            }

            $activeTahunAjaran = TahunAjaran::where('is_active', true)->firstOrFail();
            $activeSemester = Semester::where('is_active', true)->firstOrFail();

            // Load raport with kepala sekolah data and extracurricular activities
            $raport = Raport::with('ekstrakurikulerRaport')
                                ->where('siswa_id', $siswa->id)
                                ->where('tahun_ajaran_id', $activeTahunAjaran->id)
                                ->where('semester_id', $activeSemester->id)
                                ->firstOrFail(); // Pastikan rapor sudah digenerate dan ada

            // Eager load mataPelajaran untuk mendapatkan 'kelompok'
            $rekapNilaiMapel = RekapNilaiMapel::with(['mataPelajaran', 'guruPengampu'])
                                            ->where('siswa_id', $siswa->id)
                                            ->where('tahun_ajaran_id', $activeTahunAjaran->id)
                                            ->where('semester_id', $activeSemester->id)
                                            ->get();

            // Kelompokkan mata pelajaran berdasarkan 'kelompok'
            $groupedNilaiMapel = $rekapNilaiMapel->groupBy(function ($item) {
                // Pastikan mataPelajaran tidak null sebelum mengakses kelompok
                return $item->mataPelajaran->kelompok ?? 'Lain-lain';
            })->sortBy(function ($group, $key) {
                // Urutkan kelompok, misalnya Kelompok A, Kelompok B, dst.
                // Anda bisa menyesuaikan urutan ini jika ada urutan khusus
                if ($key === 'Kelompok A') return 1;
                if ($key === 'Kelompok B') return 2;
                if ($key === 'Kelompok C') return 3;
                return 99; // Untuk kelompok lain-lain
            });


            $presensiAkhir = PresensiAkhir::where('siswa_id', $siswa->id)
                                            ->where('tahun_ajaran_id', $activeTahunAjaran->id)
                                            ->where('semester_id', $activeSemester->id)
                                            ->first();

            $catatanWaliKelas = CatatanWaliKelas::where('siswa_id', $siswa->id)
                                                ->where('tahun_ajaran_id', $activeTahunAjaran->id)
                                                ->where('semester_id', $activeSemester->id)
                                                ->first();

            // Mendapatkan kepala sekolah (asumsi ada role atau kolom di tabel admin/guru)
            // Sekarang diambil dari objek $raport
            $kepalaSekolahNama = $raport->kepala_sekolah_nama ?? null;
            $kepalaSekolahNip = $raport->kepala_sekolah_nip ?? null;

            // Data ekstrakurikuler dari relasi raport
            $ekstrakurikuler = $raport->ekstrakurikulerRaport;

            // Saran: Mengambil dari catatan wali kelas di raport utama
            $saran = $raport->saran_kenaikan_kelas ?? "Siswa menunjukkan perkembangan yang baik. Terus tingkatkan prestasi dan kedisiplinan.";

            // Kondisi Kesehatan: Saat ini masih data dummy.
            // Anda perlu mengganti ini dengan data dari database Anda jika ada model/tabel terpisah.
            $tinggiBadan = '165';
            $beratBadan = '55';
            $kondisiKesehatan = "Baik";
            $pendengaran = "Baik";
            $penglihatan = "Baik";
            $gigi = "Baik";

            // --- Tambahkan ini untuk $schoolProfile ---
            $schoolProfile = SchoolProfile::firstOrCreate([]);
            // -----------------------------------------

            // Menggunakan Dompdf untuk generate PDF
            $pdf = PDF::loadView('wali_kelas.raport.print', compact(
                'siswa',
                'raport',
                'groupedNilaiMapel',
                'presensiAkhir',
                'catatanWaliKelas',
                'kelasWali',
                'activeTahunAjaran',
                'activeSemester',
                'kepalaSekolahNama',
                'kepalaSekolahNip',
                'ekstrakurikuler',
                'saran',
                'tinggiBadan',
                'beratBadan',
                'kondisiKesehatan',
                'pendengaran',
                'penglihatan',
                'gigi',
                'schoolProfile' // <--- Tambahkan 'schoolProfile'
            ));
            $pdf->setPaper('A3', 'portrait'); // Set ukuran kertas

            // Membersihkan karakter tidak valid dari nama file
            $fileNameSiswa = str_replace(['/', '\\', ' '], '_', $siswa->name ?? 'siswa');
            $fileNameTahunAjaran = str_replace(['/', '\\', ' '], '_', $activeTahunAjaran->nama ?? 'TahunAjaran');
            $fileNameSemester = str_replace(['/', '\\', ' '], '_', $activeSemester->nama ?? 'Semester');
            $pdfFileName = 'Raport_' . $fileNameSiswa . '_' . $fileNameTahunAjaran . '_' . $fileNameSemester . '.pdf';

            return $pdf->stream($pdfFileName);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error("Raport atau data terkait tidak ditemukan untuk dicetak: " . $e->getMessage());
            return redirect()->back()->with('error', 'Raport atau data terkait tidak ditemukan untuk siswa ini. Pastikan rapor sudah digenerate.');
        } catch (\Exception $e) {
            Log::error("Error generating print view for raport: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat tampilan cetak rapor: ' . $e->getMessage());
        }
    }

    /**
     * Helper function to determine grade predicate based on score and KKM.
     *
     * @param float $score
     * @param int $kkm
     * @return string
     */
    private function determinePredikat(float $score, int $kkm): string
    {
        if ($score >= 90) {
            return 'A';
        } elseif ($score >= 80) {
            return 'B';
        } elseif ($score >= $kkm) { // Lulus KKM
            return 'C';
        } else {
            return 'D'; // Tidak Lulus KKM
        }
    }

    /**
     * Helper function to determine attitude predicate based on description.
     * This is a simplified logic and can be customized based on specific school criteria.
     *
     * @param string|null $description
     * @return string|null
     */
    private function determineSikapPredikat(?string $description): ?string
    {
        if (is_null($description) || trim($description) === '') {
            return null; // Atau 'Tidak Ada' atau '-'
        }

        $descriptionLower = strtolower($description);

        // Sangat Baik (A)
        if (str_contains($descriptionLower, 'sangat baik')) {
            return 'A';
        }
        // Baik (B)
        elseif (str_contains($descriptionLower, 'baik')
                                ) {
            return 'B';
        }
        // Cukup (C)
        elseif (str_contains($descriptionLower, 'cukup')) {
            return 'C';
        }
        // Kurang (D)
        elseif (str_contains($descriptionLower, 'kurang')) {
            return 'D';
        }
        return 'B';
    }
}
