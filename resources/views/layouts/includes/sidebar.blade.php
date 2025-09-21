<aside class="main-sidebar sidebar-dark-primary elevation-8">
    <a href="#" class="brand-link">
        @if($schoolProfile && $schoolProfile->logo_path)
        <img src="{{ asset('storage/' . $schoolProfile->logo_path)}}" alt="Logo {{ $schoolProfile->name ?? 'Sekolah' }}" class="brand-image img-circle elevation-3" style="opacity: .8">
        @endif
        <span class="brand-text font-weight-light" style="font-size: 15px;">{{ $schoolProfile->name ?? 'nama sekolah'}}</span>
    </a>

    <div class="sidebar" style="font-size: 14px;">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                @if(Auth::guard('admin')->check() && Auth::guard('admin')->user()->profile_picture)
                    <img src="{{ asset('storage/' . Auth::guard('admin')->user()->profile_picture) }}"
                         class="img-circle elevation-2"
                         alt="User Image"
                         style="width: 40px; height: 40px; object-fit: cover;">
                @elseif(Auth::guard('guru')->check() && Auth::guard('guru')->user()->profile_picture)
                    <img src="{{ asset('storage/' . Auth::guard('guru')->user()->profile_picture) }}"
                         class="img-circle elevation-2"
                         alt="User Image"
                         style="width: 40px; height: 40px; object-fit: cover;">
                @elseif(Auth::guard('siswa')->check() && Auth::guard('siswa')->user()->foto_profile_path)
                    <img src="{{ asset('storage/' . Auth::guard('siswa')->user()->foto_profile_path) }}"
                         class="img-circle elevation-2"
                         alt="User Image"
                         style="width: 40px; height: 40px; object-fit: cover;">
                @else
                    <img src="{{ asset('adminlte/dist/img/user2-160x160.jpg') }}"
                         class="img-circle elevation-2"
                         alt="User Image"
                         style="width: 40px; height: 40px; object-fit: cover;">
                @endif
            </div>
            <div class="info" style="font-size: 17px;">
                @if(Auth::guard('admin')->check())
                    <a href="{{ route('admin.profile.index') }}" class="d-block">{{ Auth::guard('admin')->user()->name }} (Admin)</a>
                @elseif(Auth::guard('guru')->check())
                    <a href="{{ route('guru.profile.index') }}" class="d-block">{{ Auth::guard('guru')->user()->name }} (Guru)</a>
                @elseif(Auth::guard('siswa')->check())
                    <a href="{{ route('siswa.profile.index') }}" class="d-block">{{ Auth::guard('siswa')->user()->name }} (Siswa)</a>
                @else
                    <a href="#" class="d-block">Guest</a>
                @endif
            </div>
        </div>

        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @if(Auth::guard('admin')->check())
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard Admin
                            </p>
                        </a>
                    </li>
                    {{-- Manajemen Admin --}}
                    @php
                        $manajemenAdminRoutes = ['admin.profile.*', 'admin.admin-management.*'];
                        $isManajemenAdminActive = Request::routeIs($manajemenAdminRoutes);
                    @endphp
                    <li class="nav-item {{ $isManajemenAdminActive ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $isManajemenAdminActive ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-cog"></i>
                            <p>
                                Manajemen Admin
                                <i class="right fas fa-angle-left" ></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.profile.index') }}" class="nav-link {{ Request::routeIs('admin.profile.index') ? 'active' : '' }}">
                                    <i class="fas fa-user-circle nav-icon"></i> {{-- Icon baru untuk Profil Admin --}}
                                    <p>Profil Admin</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.admin-management.index') }}" class="nav-link {{ Request::routeIs('admin.admin-management.*') ? 'active' : '' }}">
                                    <i class="fas fa-users-cog nav-icon"></i> {{-- Icon baru untuk Kelola Admin Lain --}}
                                    <p>Kelola Admin Lain</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    @php
                        $pengaturanSistemRoutes = ['admin.settings.*'];
                        $isPengaturanSistemActive = Request::routeIs($pengaturanSistemRoutes);
                    @endphp
                    <li class="nav-item {{ $isPengaturanSistemActive ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $isPengaturanSistemActive ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cogs"></i> {{-- Icon for System Settings --}}
                            <p>
                                Pengaturan Sistem
                                <i class="right fas fa-angle-left" ></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.settings.index') }}" class="nav-link {{ Request::routeIs('admin.settings.*') ? 'active' : '' }}">
                                    <i class="fas fa-school nav-icon"></i> {{-- Icon baru untuk Pengaturan Sekolah --}}
                                    <p>Pengaturan Sekolah</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- NEW GROUP: Manajemen Pengajar & Pembelajaran --}}
                    @php
                        $manajemenPengajarPembelajaranRoutes = [
                            'admin.guru-management.*',
                            'admin.guru-assignments.*',
                            'admin.grade-management.*',
                            'admin.appreciation-management.*'
                        ];
                        $isManajemenPengajarPembelajaranActive = Request::routeIs($manajemenPengajarPembelajaranRoutes);
                    @endphp
                    <li class="nav-item {{ $isManajemenPengajarPembelajaranActive ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $isManajemenPengajarPembelajaranActive ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chalkboard"></i> {{-- Icon baru untuk grup ini --}}
                            <p>
                                Pengajar & Pembelajaran
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.guru-management.index') }}" class="nav-link {{ Request::routeIs('admin.guru-management.*') ? 'active' : '' }}">
                                    <i class="fas fa-users nav-icon"></i> {{-- Icon baru untuk Manajemen Akun Guru --}}
                                    <p>Manajemen Akun Guru</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.guru-assignments.index') }}" class="nav-link {{ Request::routeIs('admin.guru-assignments.*') ? 'active' : '' }}">
                                    <i class="fas fa-tasks nav-icon"></i> {{-- Icon baru untuk Penugasan Guru --}}
                                    <p>Penugasan Guru</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.grade-management.index') }}" class="nav-link {{ Request::routeIs('admin.grade-management.*') ? 'active' : '' }}">
                                    <i class="fas fa-graduation-cap nav-icon"></i> {{-- Icon baru untuk Manajemen Nilai --}}
                                    <p>Manajemen Nilai</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.appreciation-management.create') }}" class="nav-link {{ Request::routeIs('admin.appreciation-management.*') ? 'active' : '' }}">
                                    <i class="fas fa-award nav-icon"></i>
                                    <p>Kirim Apresiasi Guru</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    @php
    $keuanganRoutes = ['admin.spp-payments.*'];
    $isKeuanganActive = Request::routeIs($keuanganRoutes);
@endphp
<li class="nav-item {{ $isKeuanganActive ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ $isKeuanganActive ? 'active' : '' }}">
        <i class="nav-icon fas fa-money-check-alt"></i>
        <p>
            Manajemen Keuangan
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('admin.spp-payments.index') }}" class="nav-link {{ Request::routeIs('admin.spp-payments.*') ? 'active' : '' }}">
                <i class="fas fa-wallet nav-icon"></i>
                <p>Pembayaran SPP</p>
            </a>
        </li>
        <li class="nav-item">
    <a href="{{ route('admin.spp-types.index') }}" class="nav-link {{ request()->routeIs('admin.spp-types.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-list-alt"></i>
        <p>Kelola Tipe SPP</p>
    </a>
</li>
    </ul>
</li>
 

                    {{-- Manajemen Siswa --}}
                    @php
                        $manajemenSiswaRoutes = ['admin.student-data.*'];
                        $isManajemenSiswaActive = Request::routeIs($manajemenSiswaRoutes);
                    @endphp
                    <li class="nav-item {{ $isManajemenSiswaActive ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $isManajemenSiswaActive ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Manajemen Siswa
                                <i class="right fas fa-angle-left" ></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.student-data.create') }}" class="nav-link {{ Request::routeIs('admin.student-data.create') ? 'active' : '' }}"> {{-- Memastikan route create juga di-highlight --}}
                                    <i class="fas fa-user-plus nav-icon"></i> 
                                    <p>Buat Akun Siswa</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.student-data.index') }}" class="nav-link {{ Request::routeIs('admin.student-data.index') || Request::routeIs('admin.student-data.edit') || Request::routeIs('admin.student-data.show') ? 'active' : '' }}"> {{-- Memastikan route index, edit, show juga di-highlight --}}
                                    <i class="fas fa-user-graduate nav-icon"></i> {{-- Icon baru untuk Data Siswa --}}
                                    <p>Data Siswa</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- Manajemen Data Master --}}
                    @php
                        $manajemenDataMasterRoutes = [
                            'admin.mata-pelajaran.*',
                            'admin.kelas.*',
                            'admin.jurusans.*',
                            'admin.tahun-ajaran.*',
                            'admin.semester.*'
                        ];
                        $isManajemenDataMasterActive = Request::routeIs($manajemenDataMasterRoutes);
                    @endphp
                    <li class="nav-item {{ $isManajemenDataMasterActive ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $isManajemenDataMasterActive ? 'active' : '' }}">
                            <i class="nav-icon fas fa-database"></i> {{-- Icon untuk Data Master --}}
                            <p>
                                Manajemen Data Master
                                <i class="right fas fa-angle-left" ></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.mata-pelajaran.index') }}" class="nav-link {{ Request::routeIs('admin.mata-pelajaran.*') ? 'active' : '' }}">
                                    <i class="fas fa-book nav-icon"></i>
                                    <p>Mata Pelajaran</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.kelas.index') }}" class="nav-link {{ Request::routeIs('admin.kelas.*') ? 'active' : '' }}">
                                    <i class="fas fa-chalkboard-teacher nav-icon"></i> 
                                    <p>Kelas</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.jurusans.index') }}" class="nav-link {{ Request::routeIs('admin.jurusans.*') ? 'active' : '' }}">
                                    <i class="fas fa-code-branch nav-icon"></i> 
                                    <p>Jurusan</p>
                                </a>
                            </li>
                            {{-- ADDED: Tahun Ajaran --}}
                            <li class="nav-item">
                                <a href="{{ route('admin.tahun-ajaran.index') }}" class="nav-link {{ Request::routeIs('admin.tahun-ajaran.*') ? 'active' : '' }}">
                                    <i class="fas fa-calendar-alt nav-icon"></i> 
                                    <p>Tahun Ajaran</p>
                                </a>
                            </li>
                            {{-- ADDED: Semester --}}
                            <li class="nav-item">
                                <a href="{{ route('admin.semester.index') }}" class="nav-link {{ Request::routeIs('admin.semester.*') ? 'active' : '' }}">
                                    <i class="fas fa-calendar-check nav-icon"></i> 
                                    <p>Semester</p>
                                </a>
                            </li>
                        </ul>
                    </li>


                    {{-- BAGIAN MANAJEMEN PUBLIK --}}
                    @php
                        $manajemenPublikRoutes = [
                            'admin.school-profile.*',
                        ];
                        $isManajemenPublikActive = Request::routeIs($manajemenPublikRoutes);
                    @endphp
                    <li class="nav-item {{ $isManajemenPublikActive ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $isManajemenPublikActive ? 'active' : '' }}">
                            <i class="nav-icon fas fa-globe"></i>
                            <p>
                                Manajemen Publik
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                        
                            <li class="nav-item">
                                <a href="{{ route('admin.school-profile.index') }}" class="nav-link {{ Request::routeIs('admin.school-profile.*') ? 'active' : '' }}">
                                    <i class="fas fa-info-circle nav-icon"></i> 
                                    <p>Profil Sekolah</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.admin-student-announcements.index') }}" class="nav-link {{ Request::routeIs('admin.admin-student-announcements.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-bullhorn"></i>
                            <p>Pengumuman Sekolah</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.contact-messages.index') }}" class="nav-link {{ Request::routeIs('admin.contact-messages.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-inbox"></i>
                            <p>
                                Pesan Masuk
                                @php
                                    $unreadMessagesCount = \App\Models\ContactMessage::where('is_read', false)->count();
                                @endphp
                                @if($unreadMessagesCount > 0)
                                    <span class="badge badge-danger right">{{ $unreadMessagesCount }}</span>
                                @endif
                            </p>
                        </a>
                    </li>
                    {{-- Bagian Logout Admin --}}
                    <li class="nav-item">
                        <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form-admin-sidebar').submit();">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>
                                Logout
                            </p>
                        </a>
                        <form id="logout-form-admin-sidebar" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                @elseif(Auth::guard('guru')->check())
                    <li class="nav-item">
                        <a href="{{ route('guru.dashboard') }}" class="nav-link {{ Request::routeIs('guru.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard Guru
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('guru.profile.index') }}" class="nav-link {{ Request::routeIs('guru.profile.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-cog"></i>
                            <p>
                                Profil Saya
                            </p>
                        </a>
                    </li>
                    @php
                        $manajemenNilaiPresensiGuruRoutes = [
                            'guru.grades.*',
                            'guru.assignments.lesson_schedules.*',
                            'guru.attendances.*'
                        ];
                        $isManajemenNilaiPresensiGuruActive = Request::routeIs($manajemenNilaiPresensiGuruRoutes);
                    @endphp
                    <li class="nav-item {{ $isManajemenNilaiPresensiGuruActive ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $isManajemenNilaiPresensiGuruActive ? 'active' : '' }}">
                            <i class="nav-icon fas fa-keyboard"></i>
                            <p>
                                Manajemen Nilai Akhir
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('guru.grades.index') }}" class="nav-link {{ Request::routeIs('guru.grades.*') ? 'active' : '' }}">
                                    <i class="fas fa-edit nav-icon"></i>
                                    <p>Penugasan & Nilai Siswa</p>
                                </a>
                            </li>
                    @if(Auth::guard('guru')->user()->is_wali_kelas)
                            <li class="nav-item">
                                {{-- KOREKSI PENTING: Mengubah nama rute menjadi 'guru.wali-kelas.raports.index' --}}
                                <a href="{{ route('guru.wali-kelas.raports.index') }}" class="nav-link {{ Request::routeIs('guru.wali-kelas.raports.*') ? 'active' : '' }}"> {{-- Menggunakan route guru.wali-kelas.raports.* --}}
                                    <i class="nav-icon fas fa-file-invoice"></i> {{-- Mengubah icon menjadi file-invoice --}}
                                    <p>
                                        Wali Kelas (Cetak Rapor)
                                    </p>
                                </a>
                            </li>
                    @endif
                        </ul>
                    </li>
                    @php
                        $manajemenPembelajaranGuruRoutes = [
                            'guru.assignments.*',
                            'guru.teaching-materials.*',
                            'guru.appreciations.*',
                            'guru.student-announcements.*',
                            'guru.assignments-given.*'
                        ];
                        $isManajemenPembelajaranGuruActive = Request::routeIs($manajemenPembelajaranGuruRoutes) && !Request::routeIs($manajemenNilaiPresensiGuruRoutes);
                    @endphp
                    <li class="nav-item {{ $isManajemenPembelajaranGuruActive ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $isManajemenPembelajaranGuruActive ? 'active' : '' }}">
                            <i class="nav-icon fas fa-book-reader"></i>
                            <p>
                                Manajemen Pembelajaran
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('guru.teaching-materials.index') }}" class="nav-link {{ Request::routeIs('guru.teaching-materials.*') ? 'active' : '' }}">
                                    <i class="fas fa-book-open nav-icon"></i> 
                                    <p>Buku Mengajar</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('guru.appreciations.index') }}" class="nav-link {{ Request::routeIs('guru.appreciations.*') ? 'active' : '' }}">
                                    <i class="fas fa-award nav-icon"></i>
                                    <p>Apresiasi</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('guru.student-announcements.index') }}" class="nav-link {{ Request::routeIs('guru.student-announcements.*') ? 'active' : '' }}">
                                    <i class="fas fa-bell nav-icon"></i>
                                    <p>Pengumuman Siswa</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('guru.assignments-given.index') }}" class="nav-link {{ Request::routeIs('guru.assignments-given.*') ? 'active' : '' }}">
                                    <i class="fas fa-file-upload nav-icon"></i> 
                                    <p>Tugas Siswa</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    {{-- Logout Guru --}}
                    <li class="nav-item">
                        <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form-guru-sidebar').submit();">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>
                                Logout
                            </p>
                        </a>
                        <form id="logout-form-guru-sidebar" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                @elseif(Auth::guard('siswa')->check())
                    <li class="nav-item">
                        <a href="{{ route('siswa.dashboard') }}" class="nav-link {{ Request::routeIs('siswa.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard Siswa
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('siswa.profile.index') }}" class="nav-link {{ Request::routeIs('siswa.profile.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user"></i>
                            <p>
                                Profil Akun
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('siswa.data-diri.index') }}" class="nav-link {{ Request::routeIs('siswa.data-diri.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-address-card"></i>
                            <p>
                                Lengkapi Data Diri
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('siswa.my-grades.index') }}" class="nav-link {{ Request::routeIs('siswa.my-grades.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>
                                Nilai Saya
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('siswa.jadwal-pelajaran.index') }}" class="nav-link {{ Request::routeIs('siswa.jadwal-pelajaran.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>
                                Jadwal Pelajaran
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('siswa.assignments-submissions.index') }}" class="nav-link {{ Request::routeIs('siswa.assignments-submissions.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-upload"></i>
                            <p>
                                Tugas Saya
                            </p>
                        </a>
                    </li>
<li class="nav-item">
    <a href="{{ route('siswa.spp-payments.index') }}" class="nav-link {{ Request::routeIs('siswa.spp-payments.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-wallet"></i>
        <p>
            Riwayat Pembayaran SPP
        </p>
    </a>
</li>
                    {{-- Logout Siswa --}}
                    <li class="nav-item">
                        <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form-siswa-sidebar').submit();">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>
                                Logout
                            </p>
                        </a>
                        <form id="logout-form-siswa-sidebar" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>