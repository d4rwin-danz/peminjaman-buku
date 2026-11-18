<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Pengunjung yang mencoba meminjam buku diminta login dulu.
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->hasSession() && ! $request->expectsJson()) {
                $request->session()->flash(
                    'info',
                    'Silakan login terlebih dahulu untuk meminjam buku. Belum punya akun? Daftar dulu.'
                );
            }

            return route('login');
        });

        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();