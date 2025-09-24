@extends('layouts.app_admin')

@section('title', 'Tambah Penugasan Guru')
@section('page_title', 'Tambah Penugasan Guru Baru')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        /* Custom CSS untuk tampilan yang lebih modern */
        .card {
            border-radius: 15px; /* Sudut lebih membulat */
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08); /* Bayangan lebih halus dan dalam */
            background-color: #ffffff;
            color: #343a40;
            margin-bottom: 30px;
        }

        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, .125);
            padding: 1.25rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #343a40;
            margin-bottom: 0;
        }

        .form-group label {
            font-weight: 600; /* Label lebih tebal */
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border-radius: 8px; /* Sudut membulat pada input/select */
            border: 1px solid #ced4da;
            padding: 0.75rem 1rem;
            height: auto; /* Otomatis menyesuaikan padding */
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            font-size: 0.875rem;
            color: #dc3545;
            display: block; /* Pastikan feedback terlihat */
            margin-top: 0.25rem;
        }

        /* Styling untuk Tombol */
        .btn {
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px; /* Jarak antara ikon dan teks */
        }

        .btn-primary { /* Digunakan untuk tombol Simpan */
            background: linear-gradient(45deg, #007bff, #0056b3);
            border: none;
            color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 123, 255, 0.3);
            background: linear-gradient(45deg, #0056b3, #007bff);
        }

        .btn-secondary { /* Digunakan untuk tombol Batal */
            background-color: #6c757d;
            border-color: #6c757d;
            color: #ffffff;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
            transform: translateY(-1px);
        }

        /* Input Group Styling */
        .input-group-prepend .input-group-text {
            background-color: #e9ecef;
            border-color: #ced4da;
            border-radius: 8px 0 0 8px;
            padding: 0.75rem 1rem;
        }

        .input-group .form-control {
            border-radius: 0 8px 8px 0;
        }

        /* Tambahan untuk responsif */
        @media (max-width: 575.98px) {
            .btn {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus-circle mr-1"></i> Form Tambah Penugasan Guru Baru
                    </h3>
                </div>
                <form action="{{ route('admin.guru-assignments.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="guru_id">Guru: <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                </div>
                                <select name="guru_id" id="guru_id" class="form-control @error('guru_id') is-invalid @enderror" required>
                                    <option value="">Pilih Guru</option>
                                    @foreach ($gurus as $guru)
                                        <option value="{{ $guru->id }}" {{ old('guru_id') == $guru->id ? 'selected' : '' }}>{{ $guru->name }}</option>
                                    @endforeach
                                </select>
                                @error('guru_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- === PENAMBAHAN: Dropdown untuk Kelompok Mata Pelajaran === --}}
                        <div class="form-group">
                            <label for="kelompok_mapel_filter">Filter Berdasarkan Kelompok Mata Pelajaran:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                </div>
                                <select name="kelompok_mapel_filter" id="kelompok_mapel_filter" class="form-control">
                                    <option value="">Semua Kelompok</option>
                                    @foreach($kelompokMataPelajaran as $kelompok)
                                        <option value="{{ $kelompok }}" {{ old('kelompok_mapel_filter') == $kelompok ? 'selected' : '' }}>{{ $kelompok }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="mata_pelajaran_id">Mata Pelajaran: <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-book-open"></i></span>
                                </div>
                                <select name="mata_pelajaran_id" id="mata_pelajaran_id" class="form-control @error('mata_pelajaran_id') is-invalid @enderror" required>
                                    <option value="">Pilih Mata Pelajaran</option>
                                    {{-- Opsi mata pelajaran akan dimuat dinamis oleh JavaScript --}}
                                </select>
                                @error('mata_pelajaran_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="tahun_ajaran_id">Tahun Ajaran: <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                </div>
                                <select name="tahun_ajaran_id" id="tahun_ajaran_id" class="form-control @error('tahun_ajaran_id') is-invalid @enderror" required>
                                    <option value="">Pilih Tahun Ajaran</option>
                                    @foreach ($tahunAjarans as $ta)
                                        <option value="{{ $ta->id }}" {{ old('tahun_ajaran_id') == $ta->id ? 'selected' : '' }}>{{ $ta->nama }}</option>
                                    @endforeach
                                </select>
                                @error('tahun_ajaran_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="semester_id">Semester: <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-graduation-cap"></i></span>
                                </div>
                                <select name="semester_id" id="semester_id" class="form-control @error('semester_id') is-invalid @enderror" required>
                                    <option value="">Pilih Semester</option>
                                    @foreach ($semesters as $s)
                                        <option value="{{ $s->id }}" {{ old('semester_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                                    @endforeach
                                </select>
                                @error('semester_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="jurusan_filter">Jurusan (Filter Kelas):</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                </div>
                                <select name="jurusan_filter" id="jurusan_filter" class="form-control @error('jurusan_filter') is-invalid @enderror">
                                    <option value="">Tampilkan Semua Jurusan</option>
                                    @foreach ($jurusans as $jurusan)
                                        <option value="{{ $jurusan->id }}" {{ old('jurusan_filter') == $jurusan->id ? 'selected' : '' }}>{{ $jurusan->nama_jurusan }}</option>
                                    @endforeach
                                </select>
                                @error('jurusan_filter') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="kelas_id">Kelas: <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-school"></i></span>
                                </div>
                                <select name="kelas_id" id="kelas_id" class="form-control @error('kelas_id') is-invalid @enderror" required>
                                    <option value="">Pilih Kelas</option>
                                    @foreach ($kelas as $kls)
                                        <option class="kelas-option jurusan-{{ $kls->jurusan_id }}" value="{{ $kls->id }}" {{ old('kelas_id') == $kls->id ? 'selected' : '' }} data-jurusan-id="{{ $kls->jurusan_id }}">
                                            {{ $kls->nama_kelas }} ({{ $kls->jurusan->nama_jurusan ?? 'Tidak Ada Jurusan' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('kelas_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="tipe_mengajar">Tipe Mengajar: <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-lightbulb"></i></span>
                                </div>
                                <select name="tipe_mengajar" id="tipe_mengajar" class="form-control @error('tipe_mengajar') is-invalid @enderror" required>
                                    <option value="">Pilih Tipe Mengajar</option>
                                    <option value="Praktikum" {{ old('tipe_mengajar') == 'Praktikum' ? 'selected' : '' }}>Praktikum</option>
                                    <option value="Teori" {{ old('tipe_mengajar') == 'Teori' ? 'selected' : '' }}>Teori</option>
                                    <option value="Teori" {{ old('tipe_mengajar') == 'Teori&Praktikum' ? 'selected' : '' }}>Teori & Praktikum</option>
                                </select>
                                @error('tipe_mengajar') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="status_konfirmasi">Status Konfirmasi: <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                </div>
                                <select name="status_konfirmasi" id="status_konfirmasi" class="form-control @error('status_konfirmasi') is-invalid @enderror" required>
                                    <option value="">Pilih Status</option>
                                    <option value="Pending" {{ old('status_konfirmasi', 'Pending') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Dikonfirmasi" {{ old('status_konfirmasi') == 'Dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi</option>
                                    <option value="Ditolak" {{ old('status_konfirmasi') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                                @error('status_konfirmasi') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary float-right ml-2">
                            <i class="fas fa-save mr-1"></i> Simpan Penugasan
                        </button>
                        <a href="{{ route('admin.guru-assignments.index') }}" class="btn btn-secondary float-right">
                            <i class="fas fa-times-circle mr-1"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        // --- Penambahan Console.log untuk Debugging ---
        // Verifikasi jQuery dimuat
        if (typeof jQuery != 'undefined') {
            console.log('jQuery is loaded!');
        } else {
            console.error('jQuery is NOT loaded!');
        }

        var $j = jQuery.noConflict(true);

        // Verifikasi $j (jQuery noConflict)
        if (typeof $j != 'undefined') {
            console.log('$j (jQuery noConflict) is loaded!');
        } else {
            console.error('$j (jQuery noConflict) is NOT loaded!');
        }
        // --- Akhir Penambahan Console.log ---

        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOMContentLoaded fired!'); // Pesan saat DOMContentLoaded

            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };

            @if (session('success'))
                toastr.success("{{ session('success') }}");
            @endif

            @if (session('error'))
                toastr.error("{{ session('error') }}");
            @endif

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error("{{ $error }}");
                @endforeach
            @endif

            // === LOGIKA FILTER MATA PELAJARAN BERDASARKAN KELOMPOK ===
            function loadMataPelajaran(selectedKelompok, selectedMataPelajaranId = null) {
                console.log('loadMataPelajaran called with kelompok:', selectedKelompok); // Log parameter
                var mataPelajaranSelect = $j('#mata_pelajaran_id');
                mataPelajaranSelect.empty().append('<option value="">Memuat Mata Pelajaran...</option>');

                $j.ajax({
                    url: '{{ route('admin.guru-assignments.getMataPelajaranByKelompok') }}',
                    type: 'GET',
                    data: { kelompok: selectedKelompok },
                    success: function(data) {
                        console.log('AJAX Success! Data received:', data); // Log data sukses
                        mataPelajaranSelect.empty().append('<option value="">Pilih Mata Pelajaran</option>');
                        if (data.length > 0) {
                            $j.each(data, function(key, value) {
                                mataPelajaranSelect.append('<option value="' + value.id + '">' + value.nama_mapel + '</option>');
                            });
                        } else {
                            mataPelajaranSelect.append('<option value="">Tidak ada Mata Pelajaran di kelompok ini</option>');
                        }

                        // Pilih mata pelajaran yang sebelumnya dipilih (untuk old() pada create)
                        if (selectedMataPelajaranId) {
                            mataPelajaranSelect.val(selectedMataPelajaranId);
                        } else if ('{{ old('mata_pelajaran_id') }}') {
                            mataPelajaranSelect.val('{{ old('mata_pelajaran_id') }}');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error loading mata pelajaran:", error); // Log error
                        console.error("Response Text:", xhr.responseText); // Log respons error
                        mataPelajaranSelect.empty().append('<option value="">Gagal memuat Mata Pelajaran</option>');
                        toastr.error('Gagal memuat daftar mata pelajaran. Silakan cek konsol browser untuk detail.');
                    }
                });
            }

            // Panggil saat halaman dimuat untuk memuat mata pelajaran awal
            var initialKelompok = $j('#kelompok_mapel_filter').val();
            console.log('Initial Kelompok Filter Value (on DOMContentLoaded):', initialKelompok); // Log nilai awal
            var initialMataPelajaran = '{{ old('mata_pelajaran_id') }}';
            loadMataPelajaran(initialKelompok, initialMataPelajaran);

            // Event listener saat dropdown kelompok berubah
            $j('#kelompok_mapel_filter').on('change', function() {
                console.log('Kelompok Mapel Filter changed event fired!'); // Log event change
                var selectedKelompok = $j(this).val();
                loadMataPelajaran(selectedKelompok);
            });
            // === AKHIR LOGIKA FILTER MATA PELAJARAN ===


            // === LOGIKA FILTER KELAS BERDASARKAN JURUSAN (EXISTING LOGIC) ===
            var allKelasOptions = $j('#kelas_id .kelas-option'); // Simpan semua opsi kelas

            $j('#jurusan_filter').on('change', function() {
                var selectedJurusanId = $j(this).val();
                $j('#kelas_id').val(''); // Reset pilihan kelas
                
                allKelasOptions.hide(); // Sembunyikan semua opsi
                
                if (selectedJurusanId === '') {
                    // Jika "Tampilkan Semua Jurusan" dipilih, tampilkan semua opsi kelas
                    allKelasOptions.show();
                } else {
                    // Tampilkan hanya kelas yang cocok dengan jurusan yang dipilih
                    $j('.kelas-option[data-jurusan-id="' + selectedJurusanId + '"]').show();
                }
            });

            // Panggil filter saat halaman dimuat untuk menerapkan filter jika old('jurusan_filter') ada
            $j('#jurusan_filter').trigger('change');
            
            // Jaga agar kelas yang terpilih sebelumnya (old('kelas_id')) tetap terseleksi
            // walaupun filter jurusan diterapkan
            var oldKelasId = "{{ old('kelas_id') }}";
            if (oldKelasId) {
                $j('#kelas_id').val(oldKelasId);
            }
            // === AKHIR LOGIKA FILTER KELAS ===
        });
    </script>
@endpush