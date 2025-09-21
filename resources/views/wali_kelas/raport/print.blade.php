<!DOCTYPE html>
<html>
<head>
    <title>RAPORT SISWA - {{ $siswa->name ?? 'Siswa' }}</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Global Styles */
        body {
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 0;
            font-size:12pt;
            line-height: 1.5;
            color: #000;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* A3 Paper Size (untuk generasi PDF) */
        @page {
            size: A3 portrait;
            margin: 1.5cm 2.5cm;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            padding: 0;
            box-sizing: border-box;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            position: relative;
            padding-top: 10px;
        }

        .header img.logo-left {
            position: absolute;
            left: 0;
            top: 5px;
            width: 80px;
            height: auto;
            z-index: 10;
        }

        .header img.logo-right {
            position: absolute;
            right: 0;
            top: 5px;
            width: 80px;
            height: auto;
            z-index: 10;
        }

        .header h4, .header h3, .header p {
            margin: 0;
            padding: 0;
        }

        .header h4 {
            font-size: 12pt;
            font-weight: normal;
        }

        .header h3 {
            font-size: 12pt;
            margin-top: 5px;
        }

        .header p {
            font-size: 12pt;
            margin-top: 2px;
        }

        #lineA {
            border-bottom: 2px solid #000;
            margin: 10px 0 2px 0;
        }
        #lineB{
            border-bottom: 1px solid #000;
            margin: 0 0 15px 0;
        }

        .report-title {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 15px;
        }

        /* STUDENT INFO */
        .student-info table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12pt;
            border: none;
            margin-bottom: 15px;
        }

        .student-info table td {
            padding: 1px 0;
            vertical-align: top;
            border: none;
        }

        .student-info table td.label-col {
            width: 120px;
            text-align: left;
        }
        .student-info table td.value-col {
            width: 100%;
            text-align: left;
        }
        .student-info table td.spacer-col {
            width: 10%;
            /* Removed fixed width to allow more flexibility if needed */
        }

        .section-title {
            font-weight: bold;
            font-size: 12pt;
            margin-top: 20px;
            margin-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12pt;
        }

        table th, table td {
            border: 1px solid #000;
            padding: 4px 6px;
            text-align: center;
            vertical-align: top;
        }

        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .text-left {
            text-align: left;
        }

        .text-left .kehadiran{
            text-align: left;
            width: auto;
        }

        /* Penyesuaian lebar kolom untuk tabel nilai akademik */
        /* Total lebar 100% */
        .table-akademik th:nth-child(1) { width: 0.1%; } /* Mata Pelajaran */
        .table-akademik th:nth-child(2) { width: 0.5%; } /* KKM */
        .table-akademik th:nth-child(3) { width: 0.5%; } /* Pengetahuan Angka */
        .table-akademik th:nth-child(4) { width: 0.5%; } /* Predikat Pengetahuan */
        .table-akademik th:nth-child(5) { width: 0.5%; } /* Keterampilan Angka */
        .table-akademik th:nth-child(6) { width: 0.5%; } /* Predikat Keterampilan */
        .table-akademik th:nth-child(7) { width: 0.5%; } /* Sikap Spiritual Predikat */
        .table-akademik th:nth-child(8) { width: 0.5%; } /* Sikap Spiritual Deskripsi */
        .table-akademik th:nth-child(9) { width: 0.5%; } /* Sikap Sosial Predikat */
        .table-akademik th:nth-child(10) { width: 0.5%; } /* Sikap Sosial Deskripsi */
        
        /* Baris rata-rata nilai akhir */
        .rata-rata-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        .rata-rata-table td {
            border: none;
            padding: 3px 5px;
        }

        /* Layout untuk Ekstrakurikuler & Presensi */
        .two-column-layout {
            display: table;
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        .two-column-layout .column-item {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 15px;
            box-sizing: border-box;
        }

        .two-column-layout .column-item:last-child {
            padding-right: 0;
            padding-left: 15px;
        }

        /* Lebar kolom untuk tabel dalam dua-column-layout */
        /* Perhatikan: Jika Anda ingin kolom 'No.' di ekskul, Anda perlu menambahkan <th> di HTML */
        /* Saat ini, tabel ekskul memiliki 3 kolom data (Nama, Jenis, Predikat) */
        .table-ekskul th:nth-child(1) { width: 40%; } /* Nama Kegiatan */
        .table-ekskul th:nth-child(2) { width: 20%; } /* Jenis */
        .table-ekskul th:nth-child(3) { width: 40%; } /* Predikat */


        .table-presensi th:nth-child(1) { width: 10%; } /* No. */
        .table-presensi th:nth-child(2) { width: 60%; } /* Keterangan */
        .table-presensi th:nth-child(3) { width: 30%; } /* Jumlah Hari */

        /* Catatan Wali Kelas */
        .catatan-wali-kelas table {
            margin-top: 15px;
        }
        .catatan-wali-kelas table td {
            height: 60px; /* Sesuaikan tinggi jika perlu */
            vertical-align: top;
            padding: 8px;
        }
        .catatan-wali-kelas p {
            margin: 0;
        }
        .catatan-wali-kelas p:first-child {
            margin-bottom: 5px;
        }
        .catatan-wali-kelas p:last-child {
            margin-top: 10px;
        }

        /* SIGNATURE AREA */
        .signature-top-row {
            width: 130%; /* Mengatur lebar total agar bisa di-center */
            display: table;
            table-layout: fixed;
            margin: 50px auto 0 40px; /* Tengah secara horizontal, margin-top 50px */
            page-break-inside: avoid;
            
        }

        .signature-block {
            display: table-cell;
            width: 20%; /* Dua kolom untuk Orang Tua dan Wali Kelas */
            text-align: left; /* Kembali ke tengah */
            padding: 0 5px;
            vertical-align: top;
        }

        .signature-block.kepala-sekolah {
            width: 60%; /* Tetap 40% */
            display: block; /* Agar bisa diatur margin auto */
            margin: 30px auto 0 350px; /* Memberi jarak dari atas dan tengah horizontal */
            text-align: left;
        }

        /* Style untuk nama yang berada di atas garis */
        .signature-block .signature-name {
            font-weight: bold;
            margin-bottom: 5px; /* Jarak antara nama dan garis */
            margin-top: 70px; /* Memberi ruang di atas nama untuk tanda tangan */
        }

        /* Garis tanda tangan umum (untuk Orang Tua/Wali) */
        .signature-line {
            border-bottom: 1px solid #000;
            width: 40%; /* Lebar garis */
            margin: 0 0; /* Tengah garis di dalam bloknya */
            z-index: 1; 
            margin-top:95px;
            
        }
        .signature-line.name-above{
            border-bottom: 1px solid #000;
            width: 40%; /* Lebar garis */
            margin: 0 0; /* Tengah garis di dalam bloknya */
            z-index: 1; 
            margin-top:0px;
        }

        /* Style untuk NIP yang berada di bawah garis */
        .signature-block .signature-nip {
            margin-top: 5px; /* Jarak antara garis dan NIP */
        }

        .signature-block p {
            margin: 2px 0;
            font-size: 12pt;
        }
        .signature-block p:first-of-type {
            margin-bottom: 5px;
        }
        .signature-block p:last-of-type {
            margin-top: 0; /* Override default margin-top */
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            {{-- Logo Kiri (Menggunakan Base64 untuk memastikan gambar muncul) --}}
            @php
                // Ambil pengaturan sekolah
                $schoolSettings = \App\Models\SchoolSetting::first();
                $base64_logo_kiri = '';
                if ($schoolSettings && $schoolSettings->logo_kiri_path && Storage::disk('public')->exists($schoolSettings->logo_kiri_path)) {
                    $path = Storage::disk('public')->path($schoolSettings->logo_kiri_path);
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64_logo_kiri = 'data:image/' . $type . ';base64,' . base64_encode($data);
                }
            @endphp
            @if($base64_logo_kiri)
                <img src="{{ $base64_logo_kiri }}" alt="Logo Kiri" class="logo-left">
            @endif
            
            {{-- Logo Kanan (Menggunakan Base64 untuk memastikan gambar muncul) --}}
            @php
                $base64_logo_kanan = '';
                if ($schoolSettings && $schoolSettings->logo_kanan_path && Storage::disk('public')->exists($schoolSettings->logo_kanan_path)) {
                    $path = Storage::disk('public')->path($schoolSettings->logo_kanan_path);
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64_logo_kanan = 'data:image/' . $type . ';base64,' . base64_encode($data);
                }
            @endphp
            @if($base64_logo_kanan)
                <img src="{{ $base64_logo_kanan }}" alt="Logo Kanan" class="logo-right">
            @endif
            
            {{-- Informasi Sekolah dari Database --}}
            <h4>PEMERINTAH PROVINSI SUMATERA UTARA</h4>
            <h4>DINAS PENDIDIKAN</h4>
            <h3>NSSN : {{ $schoolSettings->nssn ?? 'NSSN Sekolah' }}, NPSN: {{ $schoolSettings->npsn ?? 'NPSN Sekolah' }}</h3>
            <p>Alamat: {{ $schoolSettings->alamat ?? 'Alamat Sekolah' }}</p>
        </div>
        <div class="line-separator" id="lineA"></div>
        <div class="line-separator" id="lineB"></div>

        <div class="report-title">LAPORAN HASIL BELAJAR PESERTA DIDIK</div>

        <div class="student-info">
            <table>
                <tr>
                    <td class="label-col">Nama Siswa</td>
                    <td class="value-col">: {{ $siswa->name ?? '-' }}</td>
                    <td class="spacer-col"></td>
                    <td class="label-col">Kelas/Semester</td>
                    <td class="value-col">: {{ $siswa->kelas->nama_kelas ?? '-' }} - {{ $activeSemester->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">NIS / NISN</td>
                    <td class="value-col">: {{ $siswa->nis ?? '-' }} / {{ $siswa->nisn ?? '-' }}</td>
                    <td class="spacer-col"></td>
                    <td class="label-col">Tahun Pelajaran</td>
                    <td class="value-col">: {{ $activeTahunAjaran->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">Nama Sekolah</td>
                    <td class="value-col">: {{ $schoolSettings->nama_sekolah ?? 'Nama Sekolah' }}</td>
                    <td class="spacer-col"></td>
                    <td class="label-col">Wali Kelas</td>
                    <td class="value-col">: {{ $kelasWali->waliKelas->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">Program Keahlian</td>
                    <td class="value-col">: {{ $siswa->jurusan->nama_jurusan ?? '-' }}</td>
                    <td class="spacer-col"></td>
                    <td class="label-col">Paket Keahlian</td>
                    <td class="value-col">: {{ $siswa->jurusan->nama_jurusan ?? '-' }}</td>
                </tr>
            </table>
        </div>
        <div class="table-container">
            <table class="table-akademik">
                <thead>
                    <tr>
                        <th rowspan="2" style="padding-top:20px;">Mata Pelajaran</th>
                        <th rowspan="2" style="padding-top:20px;">KKM</th>
                        <th colspan="2">Pengetahuan</th>
                        <th colspan="2">Keterampilan</th>
                        <th colspan="2">Sikap Spiritual</th>
                        <th colspan="2">Sikap Sosial</th>
                    </tr>
                    <tr>
                        <th>Angka</th>
                        <th>Predikat</th>
                        <th>Angka</th>
                        <th>Predikat</th>
                        <th>Predikat</th>
                        <th>Deskripsi</th>
                        <th>Predikat</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($groupedNilaiMapel as $kelompokNama => $mataPelajaranDalamKelompok)
                        <tr>
                            <td colspan="10" class="text-left" style="font-weight: bold; background-color: #f2f2f2;">
                                {{ $kelompokNama }}
                            </td>
                        </tr>
                        @foreach($mataPelajaranDalamKelompok as $rekap)
                            <tr>
                                <td class="text-left" style="width:2.5%;">{{ $rekap->mataPelajaran->nama_mapel ?? '-' }}</td>
                                <td>{{ $rekap->kkm_mapel ?? '-' }}</td>
                                <td>{{ round((float)$rekap->nilai_pengetahuan_angka ?? 0) }}</td>
                                <td>{{ $rekap->nilai_pengetahuan_predikat ?? '-' }}</td>
                                <td>{{ round((float)$rekap->nilai_keterampilan_angka ?? 0) }}</td>
                                <td>{{ $rekap->nilai_keterampilan_predikat ?? '-' }}</td>
                                <td>{{ $rekap->nilai_sikap_spiritual_predikat ?? '-' }}</td>
                                <td class="text-left" style="width:1%;">{{ $rekap->deskripsi_sikap_spiritual ?? '-' }}</td>
                                <td>{{ $rekap->nilai_sikap_sosial_predikat ?? '-' }}</td>
                                <td class="text-left"style="width:2%;">{{ $rekap->deskripsi_sikap_sosial ?? '-' }}</td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">Belum ada rekap nilai mata pelajaran untuk siswa ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Bagian Ekstrakurikuler dan Presensi berdampingan --}}
        <div class="two-column-layout">
            <div class="column-item">
                <div class="table-container">
                    <table class="table-ekskul">
                        <thead>
                            <tr>
                                <th colspan="3">Kegiatan Ekstrakurikuler</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            @forelse($ekstrakurikuler as $key => $ekskul)
                            <tr>
                                <td class="text-left ekskul">{{ $ekskul->nama_ekskul ?? '-' }}</td>
                                <td>{{ $ekskul->jenis_ekskul ?? '-' }}</td>
                                <td>{{ $ekskul->predikat ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3">Tidak ada kegiatan ekstrakurikuler.</td> {{-- KOREKSI DI SINI --}}
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="column-item">
                <div class="table-container">
                    <table class="table-presensi">
                        <thead>
                            <tr>
                               <th colspan="2">Daftar Hadir</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-left ">Sakit</td>
                                <td>{{ $presensiAkhir->sakit ?? 0 }} hari</td>
                            </tr>
                            <tr>
                                <td class="text-left ">Izin</td>
                                <td>{{ $presensiAkhir->izin ?? 0 }} hari</td>
                            </tr>
                            <tr>
                                <td class="text-left">Tanpa Keterangan (Alpha)</td>
                                <td>{{ $presensiAkhir->alpha ?? 0 }} hari</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    
        <div class="table-container catatan-wali-kelas">
            <table>
                <tbody>
                    <tr>
                        <td class="text-left">
                            <p style="font-weight: bold;">Catatan Wali Kelas</p>
                            <p>{{ $catatanWaliKelas->catatan ?? 'Belum ada catatan dari wali kelas.' }}</p>
                            <p style="font-weight: bold;">
                                Rata-rata Nilai: {{ round((float)$raport->rata_rata_nilai ?? 0) }}<br>
                                {{-- Menggunakan status kenaikan kelas yang disimpan di raport --}}
                                @if($raport && $raport->status_kenaikan_kelas)
                                    Status: {{ $raport->status_kenaikan_kelas }}
                                    @if($raport->saran_kenaikan_kelas)
                                        <br>Keterangan: {{ $raport->saran_kenaikan_kelas }}
                                    @endif
                                @else
                                    Status kenaikan kelas belum ditentukan.
                                @endif
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div style="text-align: right; margin-top: 30px; margin-bottom: -50px; margin-right:148px; font-size: 12pt;">
            {{ $raport->tempat_cetak ?? 'Hilimbaruzu' }}, {{ \Carbon\Carbon::parse($raport->tanggal_cetak ?? now())->locale('id')->isoFormat('D MMMM YYYY') }}<br>
        </div>

        {{-- Signature area for Orang Tua/Wali and Wali Kelas --}}
        <div class="signature-top-row">
            <div class="signature-block orang-tua">
                <p>Orang Tua/Wali</p>
                {{-- Garis default untuk Orang Tua/Wali --}}
                <div class="signature-line"></div>
            </div>

            <div class="signature-block walikelas">
                <p>Wali Kelas, {{ $siswa->kelas->nama_kelas ?? '-' }}</p>
                <p class="signature-name">{{ $kelasWali->waliKelas->name ?? '-' }}</p> 
                <div class="signature-line name-above"></div>
                @if(($kelasWali->waliKelas->kategori ?? '') == 'PNS')
                    <p class="signature-nip">NIP. {{ $kelasWali->waliKelas->nip ?? '-' }}</p>
                @else
                    <p class="signature-nip">NIP. -</p>
                @endif
            </div>
        </div>

        {{-- Signature area for Kepala Sekolah (moved below and centered) --}}
        <div class="signature-block kepala-sekolah">
            <p>Mengetahui,</p>
            <p>Kepala {{ $schoolSettings->nama_sekolah ?? 'Nama Sekolah' }}</p>
            <p class="signature-name">{{ $kepalaSekolahNama ?? 'Nama Kepala Sekolah' }}</p>
            <div class="signature-line name-above"></div>
            <p class="signature-nip">NIP. {{ $kepalaSekolahNip ?? 'NIP Kepala Sekolah' }}</p>
        </div>
    </div>
</body>
</html>
