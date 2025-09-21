<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => 'admin', // Biarkan ini 'admin' atau 'web' sesuai preferensi Anda
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great starting point is the "web" guard which uses
    | session storage and the Eloquent user provider. Of course, you may
    | add more guards for any other type of security authentication you
    | need.
    |
    */

    'guards' => [
        'web' => [ // <<<--- PASTIKAN INI ADA DAN TIDAK DIKOMEN
            'driver' => 'session',
            'provider' => 'users',
        ],
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
            'cookie' => 'admin_session',
        ],
        'guru' => [
            'driver' => 'session',
            'provider' => 'gurus',
            'cookie' => 'guru_session',
        ],
        'siswa' => [
            'driver' => 'session',
            'provider' => 'siswas',
            'cookie' => 'siswa_session',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have several different user tables or models, you may configure
    | several sources and pass the provider name to the Guard's provider
    | configuration array. Alternatively, you may also add an "Eloquent"
    | driver which will use the Eloquent client as a user provider.
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],
        'gurus' => [
            'driver' => 'eloquent',
            'model' => App\Models\Guru::class,
        ],
        'siswas' => [
            'driver' => 'eloquent',
            'model' => App\Models\Siswa::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | You may specify how the password reset functionality of your application
    | works. This "password" section corresponds to the password reset blade
    | views that are packaged with this application. This gives you the
    | flexibility of setting up as many password reset configurations as needed.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        'admins' => [
            'provider' => 'admins',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        'gurus' => [
            'provider' => 'gurus',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        'siswas' => [
            'provider' => 'siswas',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the number of seconds before a password confirmation
    | times out and the user is prompted to re-enter their password via the
    | confirmation screen. By default, the timeout lasts for three hours.
    |
    */

    'password_timeout' => 10800,

];
