@extends('layouts.app_siswa')

@section('title', 'Edit Data Diri Siswa')
@section('page_title', 'Lengkapi Data Diri & Dokumen')

@section('content')
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Lengkapi Data Diri: {{ $siswa->name }}</h3>
                </div>
                {{-- --- UBAH BARIS FORM ACTION INI --- --}}
                <form action="{{ route('siswa.data-diri.update', $siswa->id) }}" method="POST" enctype="multipart/form-data">
                    {{-- --------------------------------- --}}
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <h5><i class="icon fas fa-ban"></i> Terjadi Kesalahan!</h5>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <h5>Informasi Akun</h5>
                        <p class="text-muted text-sm">NIS dan Email tidak bisa diubah di sini. Jika perlu perubahan, hubungi Admin.</p>
                        <div class="form-group">
                            <label for="nis">NIS</label>
                            <input type="text" id="nis" class="form-control" value="{{ $siswa->nis }}" disabled>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" class="form-control" value="{{ $siswa->email }}" disabled>
                        </div>

                        <hr>
                        <h5>Data Pribadi</h5>
                        <div class="form-group">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $siswa->name) }}" required>
                            @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        {{-- === KOREKSI PENTING: TAMBAHKAN FIELD NISN DI SINI === --}}
                        <div class="form-group">
                            <label for="nisn">NISN</label>
                            <input type="text" name="nisn" id="nisn" class="form-control @error('nisn') is-invalid @enderror" value="{{ old('nisn', $siswa->nisn) }}" disabled>
                            @error('nisn')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        {{-- ================================================= --}}
                        <div class="form-group">
                            <label for="tempat_lahir">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}">
                            @error('tempat_lahir')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="tanggal_lahir">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" value="{{ old('tanggal_lahir', $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('Y-m-d') : '') }}">
                            @error('tanggal_lahir')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="jenis_kelamin">Jenis Kelamin</label>
                            <select name="jenis_kelamin" id="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror">
                                <option value="">-- Pilih --</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="agama">Agama</label>
                            <input type="text" name="agama" id="agama" class="form-control @error('agama') is-invalid @enderror" value="{{ old('agama', $siswa->agama) }}">
                            @error('agama')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3">{{ old('alamat', $siswa->alamat) }}</textarea>
                            @error('alamat')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="nomor_telepon">Nomor Telepon</label>
                            <input type="text" name="nomor_telepon" id="nomor_telepon" class="form-control @error('nomor_telepon') is-invalid @enderror" value="{{ old('nomor_telepon', $siswa->nomor_telepon) }}">
                            @error('nomor_telepon')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <hr>
                        <h5>Data Orang Tua</h5>
                        <div class="form-group">
                            <label for="nama_ayah">Nama Ayah</label>
                            <input type="text" name="nama_ayah" id="nama_ayah" class="form-control @error('nama_ayah') is-invalid @enderror" value="{{ old('nama_ayah', $siswa->nama_ayah) }}">
                            @error('nama_ayah')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="pekerjaan_ayah">Pekerjaan Ayah</label>
                            <input type="text" name="pekerjaan_ayah" id="pekerjaan_ayah" class="form-control @error('pekerjaan_ayah') is-invalid @enderror" value="{{ old('pekerjaan_ayah', $siswa->pekerjaan_ayah) }}">
                            @error('pekerjaan_ayah')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="nama_ibu">Nama Ibu</label>
                            <input type="text" name="nama_ibu" id="nama_ibu" class="form-control @error('nama_ibu') is-invalid @enderror" value="{{ old('nama_ibu', $siswa->nama_ibu) }}">
                            @error('nama_ibu')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="pekerjaan_ibu">Pekerjaan Ibu</label>
                            <input type="text" name="pekerjaan_ibu" id="pekerjaan_ibu" class="form-control @error('pekerjaan_ibu') is-invalid @enderror" value="{{ old('pekerjaan_ibu', $siswa->pekerjaan_ibu) }}">
                            @error('pekerjaan_ibu')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <hr>
                        <h5>Dokumen Pendukung (Maks. 2MB, Format: PDF, JPG, PNG)</h5>
                        <p class="text-muted text-sm">Unggah ulang dokumen hanya jika ada perubahan atau belum diunggah.</p>

                        <div class="form-group">
                            <label for="foto_profile">Foto Profil (JPG, PNG)</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="foto_profile" id="foto_profile" class="custom-file-input @error('foto_profile') is-invalid @enderror">
                                    <label class="custom-file-label" for="foto_profile">{{ $siswa->foto_profile_path ? basename($siswa->foto_profile_path) : 'Pilih file' }}</label>
                                </div>
                            </div>
                            @error('foto_profile')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                            @if($siswa->foto_profile_path)
                                <small class="form-text text-muted">File saat ini: <a href="{{ asset('storage/' . $siswa->foto_profile_path) }}" target="_blank">{{ basename($siswa->foto_profile_path) }}</a></small>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="ijazah">Ijazah (PDF, JPG, PNG)</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="ijazah" id="ijazah" class="custom-file-input @error('ijazah') is-invalid @enderror">
                                    <label class="custom-file-label" for="ijazah">{{ $siswa->ijazah_path ? basename($siswa->ijazah_path) : 'Pilih file' }}</label>
                                </div>
                            </div>
                            @error('ijazah')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                            @if($siswa->ijazah_path)
                                <small class="form-text text-muted">File saat ini: <a href="{{ asset('storage/' . $siswa->ijazah_path) }}" target="_blank">{{ basename($siswa->ijazah_path) }}</a></small>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="raport">Raport (PDF, JPG, PNG)</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="raport" id="raport" class="custom-file-input @error('raport') is-invalid @enderror">
                                    <label class="custom-file-label" for="raport">{{ $siswa->raport_path ? basename($siswa->raport_path) : 'Pilih file' }}</label>
                                </div>
                            </div>
                            @error('raport')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                            @if($siswa->raport_path)
                                <small class="form-text text-muted">File saat ini: <a href="{{ asset('storage/' . $siswa->raport_path) }}" target="_blank">{{ basename($siswa->raport_path) }}</a></small>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="kk">Kartu Keluarga (KK) (PDF, JPG, PNG)</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="kk" id="kk" class="custom-file-input @error('kk') is-invalid @enderror">
                                    <label class="custom-file-label" for="kk">{{ $siswa->kk_path ? basename($siswa->kk_path) : 'Pilih file' }}</label>
                                </div>
                            </div>
                            @error('kk')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                            @if($siswa->kk_path)
                                <small class="form-text text-muted">File saat ini: <a href="{{ asset('storage/' . $siswa->kk_path) }}" target="_blank">{{ basename($siswa->kk_path) }}</a></small>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="ktp_ortu">KTP Orang Tua (PDF, JPG, PNG)</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="ktp_ortu" id="ktp_ortu" class="custom-file-input @error('ktp_ortu') is-invalid @enderror">
                                    <label class="custom-file-label" for="ktp_ortu">{{ $siswa->ktp_ortu_path ? basename($siswa->ktp_ortu_path) : 'Pilih file' }}</label>
                                </div>
                            </div>
                            @error('ktp_ortu')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                            @if($siswa->ktp_ortu_path)
                                <small class="form-text text-muted">File saat ini: <a href="{{ asset('storage/' . $siswa->ktp_ortu_path) }}" target="_blank">{{ basename($siswa->ktp_ortu_path) }}</a></small>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="akta_lahir">Akta Lahir (PDF, JPG, PNG)</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="akta_lahir" id="akta_lahir" class="custom-file-input @error('akta_lahir') is-invalid @enderror">
                                    <label class="custom-file-label" for="akta_lahir">{{ $siswa->akta_lahir_path ? basename($siswa->akta_lahir_path) : 'Pilih file' }}</label>
                                </div>
                            </div>
                            @error('akta_lahir')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                            @if($siswa->akta_lahir_path)
                                <small class="form-text text-muted">File saat ini: <a href="{{ asset('storage/' . $siswa->akta_lahir_path) }}" target="_blank">{{ basename($siswa->akta_lahir_path) }}</a></small>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="sk_lulus">Surat Keterangan Lulus (SKL) (PDF, JPG, PNG)</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="sk_lulus" id="sk_lulus" class="custom-file-input @error('sk_lulus') is-invalid @enderror">
                                    <label class="custom-file-label" for="sk_lulus">{{ $siswa->sk_lulus_path ? basename($siswa->sk_lulus_path) : 'Pilih file' }}</label>
                                </div>
                            </div>
                            @error('sk_lulus')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                            @if($siswa->sk_lulus_path)
                                <small class="form-text text-muted">File saat ini: <a href="{{ asset('storage/' . $siswa->sk_lulus_path) }}" target="_blank">{{ basename($siswa->sk_lulus_path) }}</a></small>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="kis">Kartu Indonesia Sehat (KIS) (PDF, JPG, PNG)</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="kis" id="kis" class="custom-file-input @error('kis') is-invalid @enderror">
                                    <label class="custom-file-label" for="kis">{{ $siswa->kis_path ? basename($siswa->kis_path) : 'Pilih file' }}</label>
                                </div>
                            </div>
                            @error('kis')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                            @if($siswa->kis_path)
                                <small class="form-text text-muted">File saat ini: <a href="{{ asset('storage/' . $siswa->kis_path) }}" target="_blank">{{ basename($siswa->kis_path) }}</a></small>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="kks">Kartu Keluarga Sejahtera (KKS) / Bantuan Sosial (PDF, JPG, PNG)</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="kks" id="kks" class="custom-file-input @error('kks') is-invalid @enderror">
                                    <label class="custom-file-label" for="kks">{{ $siswa->kks_path ? basename($siswa->kks_path) : 'Pilih file' }}</label>
                                </div>
                            </div>
                            @error('kks')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                            @if($siswa->kks_path)
                                <small class="form-text text-muted">File saat ini: <a href="{{ asset('storage/' . $siswa->kks_path) }}" target="_blank">{{ basename($siswa->kks_path) }}</a></small>
                            @endif
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                        <a href="{{ route('siswa.data-diri.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Script untuk menampilkan nama file yang dipilih pada input file kustom
        document.addEventListener('DOMContentLoaded', function() {
            const fileInputs = document.querySelectorAll('.custom-file-input');
            fileInputs.forEach(input => {
                input.addEventListener('change', function(e) {
                    const fileName = e.target.files[0].name;
                    const nextSibling = e.target.nextElementSibling;
                    nextSibling.innerText = fileName;
                });
            });
        });
    </script>
@endpush
