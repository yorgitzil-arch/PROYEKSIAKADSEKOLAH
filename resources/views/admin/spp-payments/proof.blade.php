<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pembayaran SPP</title>
    <style>
        @page {
            margin: 2cm; /* Margin 2, 2, 2, 2 */
        }
        body {
            font-family: times new romans;
            font-size: 15px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .header h1 {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .content table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .content table td {
            padding: 4px 0;
        }
        .content table td.label {
            width: 30%;
            font-weight: bold;
        }
        .signature-container {
            width: 100%;
            margin-top: 40px;
            display: table; /* Menggunakan display table untuk horizontal */
        }
        .signature-col {
            width: 50%;
            display: table-cell;
            text-align: center;
        }
        .signature-name {
            margin-top: 50px;
            font-weight: bold;
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
            font-size: 9pt;
            margin-top: 2px;
        }

    </style>
</head>
<body>
    <div class="header">
    <table style="width: 100%;">
        <tr>
            {{-- Logo Kiri --}}
            <td style="width: 20%; text-align: left;">
                @php
                    $base64_logo_kiri = '';
                    if ($schoolSettings && $schoolSettings->logo_kiri_path && Storage::disk('public')->exists($schoolSettings->logo_kiri_path)) {
                        $path = Storage::disk('public')->path($schoolSettings->logo_kiri_path);
                        $type = pathinfo($path, PATHINFO_EXTENSION);
                        $data = file_get_contents($path);
                        $base64_logo_kiri = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    }
                @endphp
                @if($base64_logo_kiri)
                    <img src="{{ $base64_logo_kiri }}" alt="Logo Kiri" style="width: 80px; height:80px;">
                @endif
            </td>
            
            {{-- Informasi Sekolah --}}
            <td style="width: 80%; text-align: center; font-family:times new romans;">
                <h4>DINAS PENDIDIKAN</h4>
                <h4>{{ $schoolSettings->nama_sekolah ?? 'Nama Sekolah' }}</h4>
                <h3>NSSN : {{ $schoolSettings->nssn ?? 'NSSN Sekolah' }}, NPSN: {{ $schoolSettings->npsn ?? 'NPSN Sekolah' }}</h3>
                <p>Alamat: {{ $schoolSettings->alamat ?? 'Alamat Sekolah' }}</p>
            </td>
            
            {{-- Logo Kanan --}}
            <td style="width: 20%; text-align: right;">
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
                    <img src="{{ $base64_logo_kanan }}" alt="Logo Kanan" style="width: 80px; height:80px;">
                @endif
            </td>
        </tr>
    </table>
    <hr>
    <h1 style="text-align: center;">BUKTI PEMBAYARAN SPP</h1>
</div>

    <div class="content">
        <table>
            <tr>
                <td class="label">No. Transaksi</td>
                <td>: {{ $sppPayment->id }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Pembayaran</td>
                <td>: {{ \Carbon\Carbon::parse($sppPayment->payment_date)->translatedFormat('d F Y') }}</td>
            </tr>
        </table>
        
        <table>
            <tr>
                <td class="label">Nama Siswa</td>
                <td>: {{ $sppPayment->siswa->name }}</td>
            </tr>
            <tr>
                <td class="label">NISN</td>
                <td>: {{ $sppPayment->siswa->nisn }}</td>
            </tr>
            <tr>
                <td class="label">Kelas</td>
                <td>: {{ $sppPayment->siswa->kelas->nama_kelas ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Jurusan</td>
                <td>: {{ $sppPayment->siswa->jurusan->nama_jurusan ?? '-' }}</td>
            </tr>
        </table>

        <table>
            <tr>
                <td class="label">Tahun Ajaran</td>
                <td>: {{ $sppPayment->tahunAjaran->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Semester</td>
                <td>: {{ $sppPayment->semester->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Tipe Pembayaran</td>
                <td>: {{ $sppPayment->sppType->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Jumlah Pembayaran</td>
                <td>: Rp{{ number_format($sppPayment->amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">Status</td>
                <td>: {{ ucwords($sppPayment->status) }}</td>
            </tr>
            <tr>
                <td class="label">Catatan</td>
                <td>: {{ $sppPayment->notes ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="signature-container">
        <div class="signature-col">
            <p style="margin-top:55px;">Dibayar oleh,</p>
            <br><br>
            <p class="signature-name">(..................................)</p>
        </div>
        <div class="signature-col">
            <p style ="text-align:right; margin-right:10px;">Yogyakarta, {{ \Carbon\Carbon::parse($sppPayment->payment_date)->translatedFormat('d F Y')}}</p>
            <p>Diterima oleh,</p>
            <br><br>
            <p class="signature-name">({{ $sppPayment->admin->name ?? 'Admin' }})</p>
        </div>
    </div>

</body>
</html>