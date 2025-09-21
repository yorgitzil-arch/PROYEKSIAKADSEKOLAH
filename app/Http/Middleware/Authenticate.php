<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (! $request->expectsJson()) {
            // Jika request datang dari path admin, redirect ke login admin
            if ($request->is('admin/*')) {
                return route('admin.login');
            }
            // Jika request datang dari path guru, redirect ke login guru
            elseif ($request->is('guru/*')) {
                return route('guru.login');
            }
            // Jika request datang dari path siswa, redirect ke login siswa
            elseif ($request->is('siswa/*')) {
                return route('siswa.login');
            }
            // Default fallback jika tidak cocok dengan pola di atas, arahkan ke halaman utama publik
            return route('public.home'); // Atau route('admin.login') jika Anda ingin default ke login admin
        }
        return null;
    }

    /**
     * Handle an unauthenticated user.
     * Override this to provide specific behavior for each guard.
     */
    protected function unauthenticated($request, array $guards)
    {
        // Jika request mengharapkan JSON, kembalikan response unauthorized
        if ($request->expectsJson()) {
            abort(response()->json(['message' => 'Unauthenticated.'], 401));
        }

        // Jika tidak, redirect ke halaman login yang sesuai
        foreach ($guards as $guard) {
            if ($guard === 'admin') {
                return redirect()->route('admin.login');
            } elseif ($guard === 'guru') {
                return redirect()->route('guru.login');
            } elseif ($guard === 'siswa') {
                return redirect()->route('siswa.login');
            }
        }

        // Fallback jika guard tidak dikenali atau tidak ada guard yang ditentukan
        return redirect()->route('public.home'); // Atau ke halaman login admin
    }
}

