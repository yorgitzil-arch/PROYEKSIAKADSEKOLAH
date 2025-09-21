@extends('layouts.app_admin')

@section('title', 'Manajemen Pembayaran SPP')
@section('page_title', 'Manajemen Pembayaran SPP')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card rounded-lg">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Daftar Pembayaran SPP</h3>
                <a href="{{ route('admin.spp-payments.create') }}" class="btn btn-primary rounded-lg">
                    <i class="fas fa-plus-circle mr-1"></i> Tambah Pembayaran Baru
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.spp-payments.index') }}" method="GET" class="form-inline mb-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari Siswa (Nama/NISN/NIS)" value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit">Cari</button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Siswa</th>
                                <th>Kelas/Jurusan</th>
                                <th>Tipe SPP</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Tanggal Bayar</th>
                                <th>Dikonfirmasi Oleh</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($payments as $payment)
                            <tr>
                                <td>{{ $payment->id }}</td>
                                <td>{{ $payment->siswa->name ?? '-' }}</td>
                                <td>{{ ($payment->siswa->kelas->nama_kelas ?? '-') . ' / ' . ($payment->siswa->jurusan->nama_jurusan ?? '-') }}</td>
                                <td>{{ $payment->sppType->name ?? '-' }}</td>
                                <td>Rp{{ number_format($payment->amount, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge {{ $payment->status === 'lunas' ? 'bg-success' : ($payment->status === 'menunggu konfirmasi' ? 'bg-warning' : 'bg-danger') }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td>{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') : '-' }}</td>
                                <td>{{ $payment->admin->name ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.spp-payments.show', $payment->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    @if($payment->status === 'menunggu konfirmasi')
                                    <form action="{{ route('admin.spp-payments.approve', $payment->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Apakah Anda yakin ingin mengkonfirmasi pembayaran ini?')">
                                            <i class="fas fa-check"></i> Konfirmasi
                                        </button>
                                    </form>
                                    @endif
                                    <form action="{{ route('admin.spp-payments.destroy', $payment->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus pembayaran ini?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data pembayaran.</td>
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