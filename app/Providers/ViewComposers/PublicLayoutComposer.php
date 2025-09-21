<?php

namespace App\Providers\ViewComposers;

use Illuminate\View\View;
use App\Models\ContactInfo; // Pastikan model ini diimpor
use App\Models\Footer;     // Pastikan model ini diimpor
use App\Models\Menu;       // Pastikan model ini diimpor (untuk menu dinamis)

class PublicLayoutComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        // Ambil ContactInfo
        $contactInfo = ContactInfo::first();
        // Ambil Footer
        $footer = Footer::first();
        // Ambil menu navigasi dinamis (untuk app_public)
        $mainMenus = Menu::whereNull('parent_id')
            ->where('is_active', true)
            ->with(['children' => function($query) {
                $query->where('is_active', true)->orderBy('order');
            }])
            ->orderBy('order')
            ->get();

        // Bagikan variabel ini ke view
        $view->with('contactInfo', $contactInfo);
        $view->with('footer', $footer);
        $view->with('mainMenus', $mainMenus); // Ini untuk menu dinamis di app_public
    }
}
