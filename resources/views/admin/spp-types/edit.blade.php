{{-- resources/views/admin/spp-types/edit.blade.php --}}

@extends('layouts.app_admin')

@section('title', 'Edit Tipe SPP')
@section('page_title', 'Edit Tipe Pembayaran SPP')

@section('content')
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card rounded-lg">
                <div class="card-header">
                    <h3 class="card-title">Form Edit Tipe SPP</h3>
                </div>
                <form action="{{ route('admin.spp-types.update', $sppType) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Nama Tipe</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $sppType->name) }}" required>
                            @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="amount">Jumlah (Rp)</label>
                            <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $sppType->amount) }}" required>
                            @error('amount')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="duration_in_months">Durasi (dalam bulan, opsional)</label>
                            <input type="number" name="duration_in_months" id="duration_in_months" class="form-control @error('duration_in_months') is-invalid @enderror" value="{{ old('duration_in_months', $sppType->duration_in_months) }}">
                            @error('duration_in_months')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary rounded-lg">Perbarui</button>
                        <a href="{{ route('admin.spp-types.index') }}" class="btn btn-secondary rounded-lg">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection