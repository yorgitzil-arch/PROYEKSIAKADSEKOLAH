<!DOCTYPE html>
<html lang="id">
<head>
      @php
        $contactInfo = $contactInfo ?? \App\Models\ContactInfo::first();
        $footer = $footer ?? \App\Models\Footer::first();
        $mainMenus = $mainMenus ?? \App\Models\Menu::whereNull('parent_id')
                                         ->where('is_active', true)
                                         ->with(['children' => function($query) {
                                             $query->where('is_active', true)->orderBy('order');
                                         }])
                                         ->orderBy('order')
                                         ->get();
        $admissionInfoForTicker = $admissionInfoForTicker ?? null;
        $announcementsForTicker = $announcementsForTicker ?? collect();
        $awardsForTicker = $awardsForTicker ?? collect();
        $newsForTicker = $newsForTicker ?? collect();
        $tickerItems = collect();

        if ($admissionInfoForTicker && $admissionInfoForTicker->is_active) {
            $tickerItems->push([
                'type' => 'PPDB',
                'title' => $admissionInfoForTicker->title,
                'url' => route('public.admission-info')
            ]);
        }

        foreach ($announcementsForTicker as $announcement) {
            $tickerItems->push([
                'type' => 'Pengumuman',
                'title' => $announcement->title,
                'url' => route('public.announcements.show', $announcement->id)
            ]);
        }

        foreach ($awardsForTicker as $award) {
            $tickerItems->push([
                'type' => 'Penghargaan',
                'title' => $award->title,
                'url' => route('public.awards')
            ]);
        }
        foreach ($newsForTicker as $newsItem) {
            $tickerItems->push([
                'type' => 'Berita',
                'title' => $newsItem->title,
                'url' => route('public.news.show', $newsItem->slug)
            ]);
        }

        $tickerItems = $tickerItems->shuffle();
    @endphp
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $schoolProfile->name ?? 'nama sekolah'}} | @yield('title', 'Beranda')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .carousel-container {
            position: relative;
            overflow: hidden;
            width: 100%;
            height: 550px;
        }
        .carousel-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 0.7s ease-in-out;
        }
        .carousel-slide.active {
            opacity: 1;
        }
        .carousel-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .carousel-slide::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0.3) 70%, rgba(0, 0, 0, 0));
            z-index: 1;
        }

        .carousel-caption {
            position: absolute;
            bottom: 25%;
            left: 10%;
            right: 10%;
            padding: 0;
            background: none;
            color: white;
            text-align: left;
            max-width: 80%;
            transform: translateY(20px);
            opacity: 0;
            transition: transform 0.5s ease-out, opacity 0.5s ease-out;
            z-index: 5;
        }
        .carousel-slide.active .carousel-caption {
            transform: translateY(0);
            opacity: 1;
        }
        .carousel-caption h2 {
            font-size: 3rem;
            line-height: 1.2;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.7);
        }
        .carousel-caption p {
            font-size: 1.25rem;
            line-height: 1.6;
            margin-bottom: 0;
            text-shadow: 1px 1px 6px rgba(0,0,0,0.6);
        }


        .carousel-static-button-wrapper {
            position: absolute;
            bottom: 1%;
            left: 10%;
            z-index: 20;
        }


        .carousel-nav-button {
            display: none;
        }

        @media (max-width: 768px) {
            .carousel-container {
                height: 400px;
            }
            .carousel-slide::before {
                background: linear-gradient(to top, rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0.3) 50%, rgba(0, 0, 0, 0)); /* Gradasi dari bawah gelap ke atas transparan di mobile */
            }
            .carousel-caption {
                bottom: 20%;
                left: 5%;
                right: 5%;
                padding: 0;
                max-width: 90%;
            }
            .carousel-caption h2 {
                font-size: 1.75rem;
                margin-bottom: 0.5rem;
            }
            .carousel-caption p {
                font-size: 1rem;
            }

            .carousel-static-button-wrapper {
                bottom: 5%;
                left: 5%;
            }
        }

        .nav-item.dropdown {
            position: relative;
            display: flex;
            align-items: center;
        }

        .nav-link-line {
            position: relative;
            display: block;
            padding: 10px 8px;

        }
        .nav-item.dropdown .dropdown-toggle {
            display: flex;
            align-items: center;
            white-space: nowrap;
            padding: 10px 8px;
        }

        .nav-item.dropdown .dropdown-menu {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 180px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 100;
            border-radius: 0.5rem;
            padding: 0.5rem 0;
            top: 100%;
            left: 0;
            margin-top: 0;
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.3s ease-out, transform 0.3s ease-out;
            pointer-events: none;
        }

        .nav-item.dropdown:hover .dropdown-menu,
        .nav-item.dropdown.active .dropdown-menu {
            display: block;
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        .nav-item.dropdown:hover .dropdown-toggle .fa-chevron-down,
        .nav-item.dropdown.active .dropdown-toggle .fa-chevron-down {
            transform: rotate(180deg);
        }

        .dropdown-menu a {
            color: #374151;
            padding: 10px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
            transition: background-color 0.2s ease;
        }
        .dropdown-menu a:hover {
            background-color: #f3f4f6;
            color: #1d4ed8;
        }
        .dropdown-menu a.active {
            background-color: #eff6ff;
            color: #2563eb;
            font-weight: 600;
        }

        .nav-link-line::after {
            content: '';
            position: absolute;
            bottom: 3px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #2563eb;
            transform: scaleX(0);
            transition: transform 0.3s ease-in-out;
        }
        .nav-link-line:hover::after,
        .nav-link-line.active::after {
            transform: scaleX(1);
        }

        #mobile-menu a {
            border-bottom: 1px solid #eee;
        }
        #mobile-menu a:last-child {
            border-bottom: none;
        }

        .section-title {
            position: relative;
            text-align: left;
            padding-bottom: 10px;
            margin-bottom: 2.5rem;
        }

        .section-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 80px;
            height: 4px;
            background-color: #2563eb;
            border-radius: 2px;
        }
        .section-padding-y {
            padding-top: 3rem;
            padding-bottom: 3rem;
        }
        @media (min-width: 768px) {
            .section-padding-y {
                padding-top: 4rem;
                padding-bottom: 4rem;
            }
        }

        .news-item {
            margin-right: 40px;
            white-space: nowrap;
            display: inline-block;
        }
        .news-item:last-child {
            margin-right: 0;
        }
        .news-item a {
            color: inherit;
            text-decoration: none;
        }
        .news-item a:hover {
            text-decoration: underline;
        }

        .news-ticker-label {
            background: linear-gradient(90deg, #1d4ed8 0%, #2563eb 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Bayangan untuk kedalaman */
            display: inline-flex; /* Agar ikon dan teks sejajar vertikal */
            align-items: center; /* Pusatkan secara vertikal */
            margin-right: 15px; /* Jarak dari teks ticker */
            flex-shrink: 0; /* Pastikan tidak menyusut */
            font-size: 0.8rem; /* Ukuran font lebih kecil */
            white-space: nowrap; /* Pastikan label tidak patah baris */
        }

        .news-ticker-label i {
            margin-right: 8px; /* Jarak antara ikon dan teks */
            font-size: 1rem; /* Ukuran ikon */
        }

        /* Media queries untuk penyesuaian di layar kecil */
        @media (max-width: 768px) {
            .news-ticker-label {
                padding: 4px 8px;
                font-size: 0.7rem;
                margin-right: 10px;
            }
            .news-ticker-label i {
                margin-right: 5px;
                font-size: 0.9rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100 text-gray-900">
<div class="min-h-screen flex flex-col">
    <header class="bg-white shadow-lg sticky top-0 z-50"> {{-- Sticky header --}}
        <nav class="container mx-auto px-4 py-4 flex justify-between items-center">
            {{-- Logo dan Nama Sekolah --}}
            <a href="{{ route('public.home') }}" class="flex items-center space-x-3">
                @if($schoolProfile && $schoolProfile->logo_path)
                <img src="{{ asset('storage/' . $schoolProfile->logo_path)}}" alt="Logo {{ $schoolProfile->name ?? 'Sekolah' }}" class="h-12 w-auto" style="border-radius:50px;">
                @endif
                <span class="text-2xl md:text-3xl font-extrabold text-blue-700">{{ $schoolProfile->name ?? 'nama sekolah'}}</span>
            </a>

            {{-- Dynamic Desktop Navigation --}}
            <div class="hidden md:flex space-x-6">
                @isset($mainMenus)
                    @foreach($mainMenus as $menu)
                        @if($menu->children->isEmpty())
                            {{-- Menu tanpa sub-menu --}}
                            <a href="{{ $menu->url }}" class="nav-link-line text-gray-700 hover:text-blue-700 font-medium px-2 py-2 transition duration-300 ease-in-out {{ Request::is(ltrim($menu->url, '/')) || ($menu->name === 'Beranda' && Request::is('/')) ? 'active' : '' }}">{{ $menu->name }}</a>
                        @else
                            {{-- Menu dengan sub-menu (dropdown) --}}
                            <div class="nav-item dropdown">
                                <a href="{{ $menu->url ?? '#' }}" class="nav-link-line dropdown-toggle text-gray-700 hover:text-blue-700 font-medium px-2 py-2 transition duration-300 ease-in-out {{ collect($menu->children)->contains(fn($child) => Request::is(ltrim($child->url, '/'))) ? 'active' : '' }}" data-dropdown-toggle="{{ $menu->id }}">
                                    {{ $menu->name }} <i class="fas fa-chevron-down text-xs ml-1 transition-transform duration-300"></i>
                                </a>
                                <div id="dropdown-menu-{{ $menu->id }}" class="dropdown-menu">
                                    @foreach($menu->children as $childMenu)
                                        <a href="{{ $childMenu->url }}" class="dropdown-item {{ Request::is(ltrim($childMenu->url, '/')) ? 'active' : '' }}">{{ $childMenu->name }}</a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                @else
                    {{-- Fallback menu jika $mainMenus belum diatur atau kosong --}}
                    <a href="{{ route('public.home') }}" class="nav-link-line text-gray-700 hover:text-blue-600 font-medium {{ Request::is('/') ? 'active' : '' }}">Beranda</a>
                    <a href="{{ route('public.school-profile') }}" class="nav-link-line text-gray-700 hover:text-blue-600 font-medium {{ Request::is('profil-sekolah') ? 'active' : '' }}">Profil Sekolah</a>
                    <a href="{{ route('public.public-teachers') }}" class="nav-link-line text-gray-700 hover:text-blue-600 font-medium {{ Request::is('guru-dan-staf') ? 'active' : '' }}">Daftar Guru</a>
                    <a href="{{ route('public.awards') }}" class="nav-link-line text-gray-700 hover:text-blue-600 font-medium {{ Request::is('penghargaan') ? 'active' : '' }}">Penghargaan</a>
                    <a href="{{ route('public.admission-info') }}" class="nav-link-line text-gray-700 hover:text-blue-600 font-medium {{ Request::is('informasi-ppdb') ? 'active' : '' }}">Informasi PPDB</a>
                    <a href="{{ route('public.contact-info') }}" class="nav-link-line text-gray-700 hover:text-blue-600 font-medium {{ Request::is('kontak') ? 'active' : '' }}">Kontak</a>
                @endisset
            </div>

            {{-- Mobile Menu Button --}}
            <div class="md:hidden">
                <button id="mobile-menu-button" class="text-gray-700 focus:outline-none">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </nav>
        <div id="mobile-menu" class="hidden md:hidden bg-white shadow-lg py-2 transition-all duration-300 ease-in-out">
            {{-- Dynamic Mobile Navigation --}}
            @isset($mainMenus)
                @foreach($mainMenus as $menu)
                    @if($menu->children->isEmpty())
                        <a href="{{ $menu->url }}" class="block px-6 py-3 text-gray-700 hover:bg-gray-100 {{ Request::is(ltrim($menu->url, '/')) || ($menu->name === 'Beranda' && Request::is('/')) ? 'bg-blue-50 text-blue-700 font-semibold' : '' }}">{{ $menu->name }}</a>
                    @else
                        {{-- Mobile dropdown --}}
                        <div class="block">
                            <a href="{{ $menu->url ?? '#' }}" class="block px-6 py-3 text-gray-700 hover:bg-gray-100 flex justify-between items-center {{ collect($menu->children)->contains(fn($child) => Request::is(ltrim($child->url, '/'))) ? 'bg-blue-50 text-blue-700 font-semibold' : '' }}" data-mobile-dropdown-toggle="{{ $menu->id }}">
                                {{ $menu->name }} <i class="fas fa-chevron-down text-xs ml-2 transition-transform duration-300"></i>
                            </a>
                            <div id="mobile-dropdown-menu-{{ $menu->id }}" class="hidden bg-gray-50 py-2">
                                @foreach($menu->children as $childMenu)
                                    <a href="{{ $childMenu->url }}" class="block px-10 py-2 text-gray-700 hover:bg-gray-200 {{ Request::is(ltrim($childMenu->url, '/')) ? 'bg-blue-100 text-blue-800 font-medium' : '' }}">{{ $childMenu->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                {{-- Fallback menu mobile jika $mainMenus belum diatur atau kosong --}}
                <a href="{{ route('public.home') }}" class="block px-6 py-3 text-gray-700 hover:bg-gray-100 {{ Request::is('/') ? 'bg-blue-50 text-blue-700 font-semibold' : '' }}">Beranda</a>
                <a href="{{ route('public.school-profile') }}" class="block px-6 py-3 text-gray-700 hover:bg-gray-100 {{ Request::is('profil-sekolah') ? 'bg-blue-50 text-blue-700 font-semibold' : '' }}">Profil Sekolah</a>
                <a href="{{ route('public.public-teachers') }}" class="block px-6 py-3 text-gray-700 hover:bg-gray-100 {{ Request::is('guru-dan-staf') ? 'bg-blue-50 text-blue-700 font-semibold' : '' }}">Daftar Guru</a>
                <a href="{{ route('public.awards') }}" class="block px-6 py-3 text-gray-700 hover:bg-gray-100 {{ Request::is('penghargaan') ? 'bg-blue-50 text-blue-700 font-semibold' : '' }}">Penghargaan</a>
                <a href="{{ route('public.admission-info') }}" class="block px-6 py-3 text-gray-700 hover:bg-gray-100 {{ Request::is('informasi-ppdb') ? 'bg-blue-50 text-blue-700 font-semibold' : '' }}">Informasi PPDB</a>
                <a href="{{ route('public.contact-info') }}" class="block px-6 py-3 text-gray-700 hover:bg-gray-100 {{ Request::is('kontak') ? 'bg-blue-50 text-blue-700 font-semibold' : '' }}">Kontak</a>
            @endisset
        </div>
    </header>

    {{-- News Ticker (Teks Berjalan) --}}
    <div class="w-full bg-blue-700 text-white py-2 overflow-hidden relative z-40">
        <div class="container mx-auto px-4 flex items-center">
            {{-- Label INFO TERKINI yang dipercantik --}}
            <span class="news-ticker-label">
                <i class="fas fa-bullhorn"></i> INFO TERKINI
            </span>
            <div class="flex-grow">
                <marquee id="newsTicker" behavior="scroll" direction="left" scrollamount="6" onmouseover="this.stop()" onmouseout="this.start()" class="text-sm">
                    @forelse($tickerItems as $item)
                        <span class="news-item">
                            <strong class="text-blue-200">{{ $item['type'] }}:</strong>
                            <a href="{{ $item['url'] }}" class="hover:underline">{{ $item['title'] }}</a>
                        </span>
                    @empty
                        <span>Tidak ada informasi terbaru saat ini.</span>
                    @endforelse
                </marquee>
            </div>
        </div>
    </div>

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-blue-900 text-white py-10 mt-16 shadow-inner">
        <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-10">
            <div>
                <h3 class="text-2xl font-bold mb-5 text-blue-100">{{ $schoolProfile->name ?? 'nama sekolah'}}</h3>
                <p class="text-gray-300 leading-relaxed mb-2"><i class="fas fa-map-marker-alt mr-2 text-blue-300"></i> {{ $footer?->address_short ?? 'Alamat singkat sekolah belum diatur.' }}</p>
                <p class="text-gray-300 mb-2"><i class="fas fa-phone-alt mr-2 text-blue-300"></i> Telepon: {{ $footer?->phone_short ?? '-' }}</p>
                <p class="text-gray-300"><i class="fas fa-envelope mr-2 text-blue-300"></i> Email: {{ $footer?->email_short ?? '-' }}</p>
            </div>
            <div>
                <h3 class="text-2xl font-bold mb-5 text-blue-100">Link Cepat</h3>
                <ul class="space-y-3">
                    @php
                        $quickLinks = [];
                        if ($footer?->quick_links) {
                            $quickLinks = $footer->quick_links;
                            if (is_string($quickLinks)) {
                                $quickLinks = json_decode($quickLinks, true);
                            }
                            if (!is_array($quickLinks)) {
                                $quickLinks = [];
                            }
                        }
                    @endphp
                    @if(!empty($quickLinks))
                        @foreach($quickLinks as $link)
                            <li><a href="{{ $link['url'] ?? '#' }}" class="text-gray-300 hover:text-blue-200 transition duration-300">{{ $link['text'] ?? '' }}</a></li>
                        @endforeach
                    @else
                        <li><a href="{{ route('public.home') }}" class="text-gray-300 hover:text-blue-200 transition duration-300">Beranda</a></li>
                        <li><a href="{{ route('public.school-profile') }}" class="text-gray-300 hover:text-blue-200 transition duration-300">Profil Sekolah</a></li>
                        <li><a href="{{ route('public.announcements.index') }}" class="text-gray-300 hover:text-blue-200 transition duration-300">Pengumuman</a></li>
                        <li><a href="{{ route('public.contact-info') }}" class="text-gray-300 hover:text-blue-200 transition duration-300">Hubungi Kami</a></li>
                    @endif
                </ul>
            </div>
            <div>
                <h3 class="text-2xl font-bold mb-5 text-blue-100">Ikuti Kami</h3>
                <div class="flex space-x-5">
                    @php
                        $socialMediaLinks = [];
                        if ($contactInfo?->social_media_links) {
                            $socialMediaLinks = $contactInfo->social_media_links;
                            if (is_string($socialMediaLinks)) {
                                $socialMediaLinks = json_decode($socialMediaLinks, true);
                            }
                            if (!is_array($socialMediaLinks)) {
                                $socialMediaLinks = [];
                            }
                        }
                    @endphp
                    @if(!empty($socialMediaLinks))
                        @foreach($socialMediaLinks as $platform => $url)
                            @if($url)
                                <a href="{{ $url }}" target="_blank" class="text-gray-300 hover:text-white text-3xl transition duration-300 transform hover:scale-110">
                                    @if($platform == 'facebook') <i class="fab fa-facebook-f"></i>
                                    @elseif($platform == 'twitter') <i class="fab fa-twitter"></i>
                                    @elseif($platform == 'instagram') <i class="fab fa-instagram"></i>
                                    @elseif($platform == 'youtube') <i class="fab fa-youtube"></i>
                                    @elseif($platform == 'linkedin') <i class="fab fa-linkedin-in"></i>
                                    @endif
                                </a>
                            @endif
                        @endforeach
                    @else
                        <a href="#" class="text-gray-300 hover:text-white text-3xl transition duration-300 transform hover:scale-110"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-gray-300 hover:text-white text-3xl transition duration-300 transform hover:scale-110"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-300 hover:text-white text-3xl transition duration-300 transform hover:scale-110"><i class="fab fa-youtube"></i></a>
                    @endif
                </div>
            </div>
        </div>
        <div class="text-center text-gray-400 mt-10 border-t border-blue-700 pt-8">
            <p>{{ $footer?->copyright_text ?? '© 2025 SMKN 1 Lahusa. All rights reserved.' }}</p>
            <p class="text-sm mt-2">Aplikasi Website ini merupakan wadah informasi tentang sekolah kami</p>
        </div>
    </footer>
</div>

<script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
        // Tambahan: Tutup dropdown mobile yang mungkin terbuka saat toggle menu utama
        document.querySelectorAll('[data-mobile-dropdown-toggle]').forEach(btn => {
            const dropdownMenu = document.getElementById(`mobile-dropdown-menu-${btn.dataset.mobileDropdownToggle}`);
            if (dropdownMenu && !dropdownMenu.classList.contains('hidden')) {
                dropdownMenu.classList.add('hidden');
                btn.querySelector('i.fa-chevron-down')?.classList.remove('rotate-180');
            }
        });
    });

    // Desktop Dropdown Menu Toggle (simple JS for hover and click)
    document.querySelectorAll('.nav-item.dropdown').forEach(dropdown => {
        const toggle = dropdown.querySelector('[data-dropdown-toggle]');
        const menu = dropdown.querySelector('.dropdown-menu');
        const chevron = toggle ? toggle.querySelector('i.fa-chevron-down') : null;

        // Mouse enter/leave for hover effect
        dropdown.addEventListener('mouseenter', function() {
            if (menu) {
                menu.style.display = 'block';
                setTimeout(() => { // Small delay for transition
                    menu.style.opacity = '1';
                    menu.style.transform = 'translateY(0)';
                    menu.style.pointerEvents = 'auto'; // Aktifkan pointer events
                }, 10);
                if (chevron) chevron.classList.add('rotate-180');
            }
        });

        dropdown.addEventListener('mouseleave', function() {
            if (menu) {
                menu.style.opacity = '0';
                menu.style.transform = 'translateY(10px)';
                menu.style.pointerEvents = 'none'; // Nonaktifkan pointer events
                setTimeout(() => { // Hide after transition
                    menu.style.display = 'none';
                }, 300); // Match transition duration
                if (chevron) chevron.classList.remove('rotate-180');
            }
        });

        // Click event for accessibility (optional, if hover is main interaction)
        if (toggle) {
            toggle.addEventListener('click', function(event) {
                if (this.getAttribute('href') === '#') {
                    event.preventDefault();
                }

                if (window.innerWidth < 768) {
                    if (menu.style.display === 'block') {
                        menu.style.opacity = '0';
                        menu.style.transform = 'translateY(10px)';
                        menu.style.pointerEvents = 'none';
                        setTimeout(() => { menu.style.display = 'none'; }, 300);
                        if (chevron) chevron.classList.remove('rotate-180');
                    } else {
                        document.querySelectorAll('.nav-item.dropdown .dropdown-menu').forEach(otherMenu => {
                            if (otherMenu !== menu && otherMenu.style.display === 'block') {
                                otherMenu.style.opacity = '0';
                                otherMenu.style.transform = 'translateY(10px)';
                                otherMenu.style.pointerEvents = 'none'; // Nonaktifkan pointer events
                                setTimeout(() => { otherMenu.style.display = 'none'; }, 300);
                                otherMenu.closest('.nav-item.dropdown').querySelector('i.fa-chevron-down')?.classList.remove('rotate-180');
                            }
                        });
                        menu.style.display = 'block';
                        setTimeout(() => {
                            menu.style.opacity = '1';
                            menu.style.transform = 'translateY(0)';
                            menu.style.pointerEvents = 'auto';
                        }, 10);
                        if (chevron) chevron.classList.add('rotate-180');
                    }
                }
            });
        }
    });

    document.querySelectorAll('[data-mobile-dropdown-toggle]').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const dropdownId = this.dataset.mobileDropdownToggle;
            const dropdownMenu = document.getElementById(`mobile-dropdown-menu-${dropdownId}`);
            if (dropdownMenu) {
                document.querySelectorAll('#mobile-menu .hidden.bg-gray-50').forEach(otherMobileMenu => {
                    if (otherMobileMenu !== dropdownMenu && !otherMobileMenu.classList.contains('hidden')) {
                        otherMobileMenu.classList.add('hidden');
                        otherMobileMenu.closest('.block').querySelector('i.fa-chevron-down')?.classList.remove('rotate-180');
                    }
                });
                dropdownMenu.classList.toggle('hidden');
                const icon = this.querySelector('i.fa-chevron-down');
                if (icon) {
                    icon.classList.toggle('rotate-180');
                }
            }
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        const slides = document.querySelectorAll('.carousel-slide');
        let currentSlide = 0;
        let slideInterval;
        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.remove('active');
                if (i === index) {
                    slide.classList.add('active');
                }
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }

        if (slides.length > 0) {
            showSlide(currentSlide);
            if (slides.length > 1) {
                slideInterval = setInterval(nextSlide, 5000);
            }
        }
    });
</script>
@stack('scripts')
</body>
</html>
