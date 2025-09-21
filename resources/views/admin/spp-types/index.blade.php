{{-- resources/views/admin/spp-types/index.blade.php --}}

@extends('layouts.app_admin')

@section('title', 'Manajemen Jenis SPP')
@section('page_title', 'Daftar Jenis Pembayaran SPP')

@section('content')
    <div class="row">
        <div class="col-12">
            <a href="{{ route('admin.spp-types.create') }}" class="btn btn-primary mb-3 rounded-lg"><i class="fas fa-plus mr-2"></i>Tambah Tipe SPP Baru</a>
            <div class="card rounded-lg">
                <div class="card-header">
                    <h3 class="card-title">Tabel Jenis SPP</h3>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success rounded-lg">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger rounded-lg">{{ session('error') }}</div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama </th>
                                    <th>Jumlah (Rp)</th>
                                    <th>Durasi (Bulan)</th>
                                    <th class="text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sppTypes as $type)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $type->name }}</td>
                                        <td>{{ number_format($type->amount, 0, ',', '.') }}</td>
                                        <td>{{ $type->duration_in_months }}</td>
                                        <td class="text-right">
                                            <a href="{{ route('admin.spp-types.edit', $type) }}" class="btn btn-warning btn-sm rounded-lg">Edit</a>
                                            <form action="{{ route('admin.spp-types.destroy', $type) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm rounded-lg" onclick="return confirm('Yakin ingin menghapus?')" disabled>Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Belum ada data tipe SPP.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection