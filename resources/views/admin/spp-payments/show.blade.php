@extends('layouts.app_admin')

@section('title', 'Detail Pembayaran SPP')
@section('page_title', 'Detail Transaksi SPP')

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card rounded-lg">
                <div class="card-header">
                    <h3 class="card-title">Detail Pembayaran #{{ $sppPayment->id }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.spp-payments.index') }}" class="btn btn-secondary btn-sm rounded-lg">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <a href="{{ route('admin.spp-payments.print', $sppPayment->id) }}" class="btn btn-success btn-sm rounded-lg" target="_blank">
                            <i class="fas fa-print mr-1"></i> Cetak Bukti Pembayaran
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 30%;">Nama Siswa</th>
                            <td>{{ $sppPayment->siswa->name }}</td>
                        </tr>
                        <tr>
                            <th>Kelas / Jurusan</th>
                            <td>{{ $sppPayment->siswa->kelas->nama_kelas ?? '-' }} / {{ $sppPayment->siswa->jurusan->nama_jurusan?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Kategori Pembayaran</th>
                            <td>{{ $sppPayment->sppType->name }}</td>
                        </tr>
                        <tr>
                            <th>Tahun Ajaran</th>
                            <td>{{ $sppPayment->tahunAjaran->nama }}</td>
                        </tr>
                        <tr>
                            <th>Semester</th>
                            <td>{{ $sppPayment->semester->nama }}</td>
                        </tr>
                        <tr>
                            <th>Jumlah Pembayaran</th>
                            <td>Rp{{ number_format($sppPayment->amount, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge {{ $sppPayment->status == 'lunas' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($sppPayment->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                    <th>Tanggal Pembayaran</th>
                    <td>{{ \Carbon\Carbon::parse($sppPayment->payment_date)->format('d F Y, H:i') }}</td>
                </tr>
                        <tr>
                            <th>Dikonfirmasi Oleh</th>
                            <td>{{ $sppPayment->admin->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Catatan</th>
                            <td>{{ $sppPayment->notes ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Transaksi Dibuat</th>
                            <td>{{ $sppPayment->created_at->format('d F Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection