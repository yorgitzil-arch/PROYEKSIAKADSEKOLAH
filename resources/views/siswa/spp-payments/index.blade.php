@extends('layouts.app_siswa')

@section('title', 'Riwayat Pembayaran SPP')
@section('page_title', 'Riwayat Pembayaran SPP')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card rounded-lg">
            <div class="card-header">
                <h3 class="card-title">Daftar Pembayaran SPP Anda</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tipe SPP</th>
                                <th>Jumlah</th>
                                <th>Tahun/Semester</th>
                                <th>Status</th>
                                <th>Tanggal Bayar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($payments as $payment)
                            <tr>
                                <td>{{ $payment->id }}</td>
                                <td>{{ $payment->sppType->name ?? '-' }}</td>
                                <td>Rp{{ number_format($payment->amount, 0, ',', '.') }}</td>
                                <td>{{ $payment->tahunAjaran->nama ?? '-' }} / {{ $payment->semester->nama ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $payment->status === 'lunas' ? 'bg-success' : ($payment->status === 'menunggu konfirmasi' ? 'bg-warning' : 'bg-danger') }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td>{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') : '-' }}</td>
                                <td>
                                    @if ($payment->status === 'belum lunas')
                                    <a href="{{ route('siswa.spp-payments.pay', $payment->id) }}" class="btn btn-success btn-sm">
                                        <i class="fas fa-money-bill-wave"></i> Bayar
                                    </a>
                                    @elseif ($payment->status === 'menunggu konfirmasi')
                                    <button class="btn btn-warning btn-sm" disabled>
                                        <i class="fas fa-hourglass-half"></i> Menunggu Konfirmasi
                                    </button>
                                    @endif
                                    <a href="{{ route('siswa.spp-payments.show', $payment->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada riwayat pembayaran.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{ $payments->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection