<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\RoleMiddleware;
use Symfony\Component\HttpKernel\Exception\HttpException;

// Paket PHP di Wasmer Edge tidak memuat ekstensi mbstring, sedangkan
// symfony/polyfill-mbstring versi ini tidak lagi menyediakan mb_split().
// Laravel memakai mb_split() di Str::studly(), jadi kita lengkapi sendiri.
if (! function_exists('mb_split')) {
    function mb_split(string $pattern, string $string, int $limit = -1): array|false
    {
        return preg_split('/'.str_replace('/', '\/', $pattern).'/u', $string, $limit);
    }
}

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'login',
            '/login',
            'logout',
            '/logout',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (HttpException $e, Request $request) {
            if ($e->getStatusCode() !== 419) {
                return null;
            }

            if ($request->is('logout', 'logout/*') || $request->routeIs('logout')) {
                return redirect('/login')->with('error', 'Anda telah berhasil keluar.');
            }

            if (Auth::check()) {
                Auth::logout();
            }

            if ($request->hasSession()) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            return redirect('/login')->with('error', 'Sesi Anda telah berakhir, silakan login kembali.');
        });
    })->create();

// Hormati LARAVEL_STORAGE_PATH (mis. volume Wasmer /data/storage) agar upload & file persisten.
$storagePath = $_SERVER['LARAVEL_STORAGE_PATH'] ?? $_ENV['LARAVEL_STORAGE_PATH'] ?? getenv('LARAVEL_STORAGE_PATH') ?: null;
if (is_string($storagePath) && $storagePath !== '' && is_dir(dirname($storagePath))) {
    $app->useStoragePath($storagePath);
}

return $app;
