@extends('layouts.app_siswa')

@section('title', 'Detail Pembayaran')
@section('page_title', 'Detail Pembayaran SPP')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card rounded-lg">
            <div class="card-header">
                <h3 class="card-title">Detail Pembayaran</h3>
                <div class="card-tools">
                    <a href="{{ route('siswa.spp-payments.index') }}" class="btn btn-secondary btn-sm rounded-lg">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                    @if($sppPayment->status === 'lunas')
                        <a href="{{ route('siswa.spp-payments.print', $sppPayment->id) }}" class="btn btn-primary btn-sm rounded-lg">
                            <i class="fas fa-print mr-1"></i> Cetak Bukti
                        </a>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Nama Siswa</th>
                            <td>{{ $sppPayment->siswa->name }}</td>
                        </tr>
                        <tr>
                            <th>Tipe SPP</th>
                            <td>{{ $sppPayment->sppType->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Jumlah Bayar</th>
                            <td>Rp{{ number_format($sppPayment->amount, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Tahun Ajaran</th>
                            <td>{{ $sppPayment->tahunAjaran->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Semester</th>
                            <td>{{ $sppPayment->semester->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Status Pembayaran</th>
                            <td>
                                <span class="badge {{ $sppPayment->status == 'lunas' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($sppPayment->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Tanggal Bayar</th>
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
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection