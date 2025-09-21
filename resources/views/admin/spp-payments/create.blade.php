@extends('layouts.app_admin')

@section('title', 'Tambah Pembayaran SPP')
@section('page_title', 'Tambah Pembayaran SPP Baru')

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card rounded-lg">
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Pembayaran SPP</h3>
                </div>
                <form action="{{ route('admin.spp-payments.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show rounded-lg m-3" role="alert">
                                <h5><i class="icon fas fa-exclamation-triangle mr-2"></i> Terjadi Kesalahan!</h5>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="siswa_id">Siswa</label>
                            <select name="siswa_id" id="siswa_id" class="form-control rounded-lg @error('siswa_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Siswa --</option>
                                @foreach($siswas as $siswa)
                                    <option 
                                        value="{{ $siswa->id }}" 
                                        {{ old('siswa_id') == $siswa->id ? 'selected' : '' }}
                                        data-spp-type-id="{{ $siswa->sppType->id ?? '' }}"
                                        data-spp-type-amount="{{ $siswa->sppType->amount ?? '' }}">
                                        {{ $siswa->name }} (NISN: {{ $siswa->nisn }})
                                    </option>
                                @endforeach
                            </select>
                            @error('siswa_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="spp_type_id">Kategori Pembayaran (Tipe SPP)</label>
                            <select name="spp_type_id" id="spp_type_id" class="form-control rounded-lg @error('spp_type_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($sppTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('spp_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }} (Rp{{ number_format($type->amount, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('spp_type_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="tahun_ajaran_id">Tahun Ajaran</label>
                            <select name="tahun_ajaran_id" id="tahun_ajaran_id" class="form-control rounded-lg @error('tahun_ajaran_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Tahun Ajaran --</option>
                                @foreach($tahunAjarans as $tahun)
                                    <option value="{{ $tahun->id }}" {{ old('tahun_ajaran_id') == $tahun->id ? 'selected' : '' }}>
                                        {{ $tahun->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tahun_ajaran_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="semester_id">Semester</label>
                            <select name="semester_id" id="semester_id" class="form-control rounded-lg @error('semester_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Semester --</option>
                                @foreach($semesters as $semester)
                                    <option value="{{ $semester->id }}" {{ old('semester_id') == $semester->id ? 'selected' : '' }}>
                                        {{ $semester->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('semester_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="amount">Jumlah Pembayaran</label>
                            <input type="number" name="amount" class="form-control rounded-lg @error('amount') is-invalid @enderror" id="amount" placeholder="Masukkan jumlah" value="{{ old('amount') }}" required>
                            @error('amount')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control rounded-lg @error('status') is-invalid @enderror" required>
                                <option value="lunas" {{ old('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                <option value="belum lunas" {{ old('status') == 'belum lunas' ? 'selected' : '' }}>Belum Lunas</option>
                            </select>
                            @error('status')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="notes">Catatan (Opsional)</label>
                            <textarea name="notes" id="notes" class="form-control rounded-lg @error('notes') is-invalid @enderror" rows="3" placeholder="Tambahkan catatan jika perlu">{{ old('notes') }}</textarea>
                            @error('notes')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary rounded-lg">
                            <i class="fas fa-save mr-1"></i> Simpan Pembayaran
                        </button>
                        <a href="{{ route('admin.spp-payments.index') }}" class="btn btn-secondary rounded-lg">
                            <i class="fas fa-times mr-1"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const siswaSelect = document.getElementById('siswa_id');
        const sppTypeSelect = document.getElementById('spp_type_id');
        const amountInput = document.getElementById('amount');

        siswaSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const sppTypeId = selectedOption.getAttribute('data-spp-type-id');
            const sppTypeAmount = selectedOption.getAttribute('data-spp-type-amount');

            // Set the SPP Type dropdown
            if (sppTypeId) {
                sppTypeSelect.value = sppTypeId;
            } else {
                sppTypeSelect.value = ''; // Reset if no SPP type is assigned
            }

            // Set the amount field
            if (sppTypeAmount) {
                amountInput.value = sppTypeAmount;
            } else {
                amountInput.value = ''; // Reset if no amount is found
            }
        });
    });
</script>
@endsection