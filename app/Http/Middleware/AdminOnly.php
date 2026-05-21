<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Sirf Admin yeh page dekh sakta hai!');
        }

        return $next($request);
    }
}

// ─── bootstrap/app.php mein register karein: ─────────────────
//
// ->withMiddleware(function (Middleware $middleware) {
//     $middleware->alias([
//         'admin'   => \App\Http\Middleware\AdminOnly::class,
//     ]);
// })
//
// ─── Phir routes mein use karein: ────────────────────────────
//
// Route::resource('users', UserController::class)->middleware('admin');
