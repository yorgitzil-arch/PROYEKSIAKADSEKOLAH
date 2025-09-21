<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SchoolProfileController extends Controller
{
    public function index()
    {
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        return view('admin.public_content.school_profile.index', compact('schoolProfile'));
    }

    public function storeUpdate(Request $request)
    {
        $rules = [
            'name' => 'nullable|string|max:255',
            'history' => 'nullable|string',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ];

        $validatedData = $request->validate($rules);
        $data = $validatedData;
        $currentProfile = SchoolProfile::first();

        if ($request->hasFile('logo')) {
            if ($currentProfile && $currentProfile->logo_path) {
                Storage::disk('public')->delete($currentProfile->logo_path);
            }
            $logoPath = $request->file('logo')->store('school_logos', 'public');
            $data['logo_path'] = $logoPath;
        } else {
            if ($currentProfile && $currentProfile->logo_path && !isset($data['logo_path'])) {
                $data['logo_path'] = $currentProfile->logo_path;
            }
        }

        if ($request->hasFile('banner')) {
            if ($currentProfile && $currentProfile->banner_path) {
                Storage::disk('public')->delete($currentProfile->banner_path);
            }
            $bannerPath = $request->file('banner')->store('school_banners', 'public');
            $data['banner_path'] = $bannerPath;
        } else {
            if ($currentProfile && $currentProfile->banner_path && !isset($data['banner_path'])) {
                $data['banner_path'] = $currentProfile->banner_path;
            }
        }

        try {
            SchoolProfile::updateOrCreate(
                ['id' => $currentProfile->id ?? null],
                $data
            );
            $message = 'Profil sekolah berhasil disimpan!';
            return redirect()->route('admin.school-profile.index')->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan profil sekolah: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan profil sekolah: ' . $e->getMessage());
        }
    }

    public function deleteLogo()
    {
        $profile = SchoolProfile::first();

        if ($profile && $profile->logo_path) {
            try {
                Storage::disk('public')->delete($profile->logo_path);
                $profile->logo_path = null;
                $profile->save();
                return redirect()->route('admin.school-profile.index')->with('success', 'Logo sekolah berhasil dihapus!');
            } catch (\Exception $e) {
                Log::error('Gagal menghapus logo sekolah: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus logo sekolah: ' . $e->getMessage());
            }
        }
        return redirect()->route('admin.school-profile.index')->with('error', 'Tidak ada logo untuk dihapus.');
    }

    public function deleteBanner()
    {
        $profile = SchoolProfile::first();

        if ($profile && $profile->banner_path) {
            try {
                Storage::disk('public')->delete($profile->banner_path);
                $profile->banner_path = null;
                $profile->save();
                return redirect()->route('admin.school-profile.index')->with('success', 'Banner sekolah berhasil dihapus!');
            } catch (\Exception $e) {
                Log::error('Gagal menghapus banner sekolah: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus banner sekolah: ' . $e->getMessage());
            }
        }
        return redirect()->route('admin.school-profile.index')->with('error', 'Tidak ada banner untuk dihapus.');
    }

    public function resetProfile()
    {
        $profile = SchoolProfile::first();

        if ($profile) {
            try {
                if ($profile->logo_path) {
                    Storage::disk('public')->delete($profile->logo_path);
                }
                if ($profile->banner_path) {
                    Storage::disk('public')->delete($profile->banner_path);
                }
                $profile->delete();
                return redirect()->route('admin.school-profile.index')->with('success', 'Profil sekolah berhasil direset dan siap diisi ulang!');
            } catch (\Exception $e) {
                Log::error('Gagal mereset profil sekolah: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Terjadi kesalahan saat mereset profil sekolah: ' . $e->getMessage());
            }
        }
        return redirect()->route('admin.school-profile.index')->with('info', 'Tidak ada profil sekolah yang ditemukan untuk direset.');
    }
}