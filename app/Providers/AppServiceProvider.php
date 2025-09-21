<?php

namespace App\Providers;

    use Illuminate\Support\ServiceProvider;
    use Illuminate\Support\Facades\View; // Pastikan baris ini ada
    use App\Providers\ViewComposers\PublicLayoutComposer; // Pastikan baris ini ada

    class AppServiceProvider extends ServiceProvider
    {
        /**
         * Register any application services.
         */
        public function register(): void
        {
            //
        }

        /**
         * Bootstrap any application services.
         */
        public function boot(): void
        {
            // Daftarkan namespace 'adminlte' agar Laravel tahu di mana mencari view AdminLTE.
            $this->loadViewsFrom(resource_path('views/adminlte'), 'adminlte');

            // Daftarkan View Composer untuk SEMUA layout utama
            View::composer(
                [
                    'layouts.app_public',
                    'layouts.app_admin',
                    'layouts.app_guru',
                    'layouts.app_siswa'
                ],
                PublicLayoutComposer::class
            );
            // Kode pendaftaran Google Drive yang lama sudah dihapus dari sini
        }
    }
    