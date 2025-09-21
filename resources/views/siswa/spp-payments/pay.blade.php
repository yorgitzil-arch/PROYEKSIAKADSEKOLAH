@extends('layouts.app_siswa')

@section('title', 'Pembayaran SPP')
@section('page_title', 'Form Pembayaran SPP')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card rounded-lg">
            <div class="card-header">
                <h3 class="card-title">Unggah Bukti Pembayaran</h3>
            </div>
            <div class="card-body">
                <p>Silakan lakukan pembayaran sebesar **Rp{{ number_format($sppPayment->amount, 0, ',', '.') }}** untuk **{{ $sppPayment->sppType->name }}**.</p>
                <p>Setelah melakukan transfer, unggah bukti pembayaran Anda di bawah ini. Pembayaran akan diverifikasi oleh admin dalam 1x24 jam.</p>

                <hr>

                <form action="{{ route('siswa.spp-payments.submit', $sppPayment->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="proof_of_payment">Bukti Pembayaran (JPG, PNG, PDF)</label>
                        <input type="file" class="form-control-file @error('proof_of_payment') is-invalid @enderror" id="proof_of_payment" name="proof_of_payment" required>
                        @error('proof_of_payment')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-upload mr-1"></i> Unggah & Bayar
                    </button>
                    <a href="{{ route('siswa.spp-payments.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection