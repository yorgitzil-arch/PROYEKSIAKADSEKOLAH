<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pembayaran SPP</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 14px;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 40px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            margin: 0;
            color: #1a73e8;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table th, .info-table td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .info-table th {
            width: 30%;
            font-weight: bold;
            color: #555;
        }
        .info-table .total-amount {
            font-size: 18px;
            font-weight: bold;
            color: green;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <h1>Bukti Pembayaran SPP</h1>
            <p><strong>SMK Negeri 1</strong></p>
        </div>

        <table class="info-table">
            <tbody>
                <tr>
                    <th>Nama Siswa</th>
                    <td>{{ $sppPayment->siswa->name }}</td>
                </tr>
                <tr>
                    <th>Kelas / Jurusan</th>
                    <td>{{ $sppPayment->siswa->kelas->nama_kelas ?? '-' }} / {{ $sppPayment->siswa->jurusan->nama_jurusan ?? '-' }}</td>
                </tr>
                <tr>
                    <th>ID Transaksi</th>
                    <td>{{ $sppPayment->id }}</td>
                </tr>
                <tr>
                    <th>Tipe SPP</th>
                    <td>{{ $sppPayment->sppType->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Tahun Ajaran / Semester</th>
                    <td>{{ $sppPayment->tahunAjaran->nama ?? '-' }} / {{ $sppPayment->semester->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Tanggal Pembayaran</th>
                    <td>{{ \Carbon\Carbon::parse($sppPayment->payment_date)->format('d F Y, H:i') }}</td>
                </tr>
                <tr>
                    <th>Jumlah Dibayar</th>
                    <td class="total-amount">Rp{{ number_format($sppPayment->amount, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ ucfirst($sppPayment->status) }}</td>
                </tr>
                <tr>
                    <th>Dikonfirmasi Oleh</th>
                    <td>{{ $sppPayment->admin->name ?? '-' }}</td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <p>Terima kasih atas pembayaran Anda.</p>
            <p>Bukti ini adalah validasi resmi pembayaran. Harap simpan dengan baik.</p>
        </div>
    </div>

</body>
</html>