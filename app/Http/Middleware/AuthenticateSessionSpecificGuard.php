<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateSessionSpecificGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        // Jika tidak ada guard yang ditentukan, coba semua guard yang ada
        if (empty($guards)) {
            $guards = array_keys(config('auth.guards'));
        }

        $authenticated = false;
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $authenticated = true;
                break; // Hentikan loop jika sudah ada yang terautentikasi
            }
        }

        if ($authenticated) {
            return $next($request);
        }

        // Jika tidak ada yang terautentikasi, jangan lakukan redirect di sini.
        // Biarkan middleware 'auth' Laravel yang akan datang (Authenticate.php)
        // atau guard default yang menangani pengalihan ke halaman login.
        // Ini mencegah redirect loop.
        return $next($request);
    }
}
