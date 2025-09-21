<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|null  ...$guards
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        // Jika tidak ada guard yang spesifik, gunakan guard default 'web'
        if (empty($guards)) {
            $guards = [null]; // Menggunakan null akan memeriksa guard default
        }

        foreach ($guards as $guard) {
            // Periksa apakah user sudah login di guard ini
            if (Auth::guard($guard)->check()) {
                // Jika guard adalah 'admin', arahkan ke dashboard admin
                if ($guard === 'admin') {
                    return redirect(RouteServiceProvider::ADMIN_DASHBOARD);
                }
                // Jika guard adalah 'guru', arahkan ke dashboard guru
                elseif ($guard === 'guru') {
                    return redirect(RouteServiceProvider::GURU_DASHBOARD);
                }
                // Jika guard adalah 'siswa', arahkan ke dashboard siswa
                elseif ($guard === 'siswa') {
                    return redirect(RouteServiceProvider::SISWA_DASHBOARD);
                }
                // Jika guard default 'web' atau guard lain, arahkan ke HOME
                else {
                    return redirect(RouteServiceProvider::HOME);
                }
            }
        }

        return $next($request);
    }
}

