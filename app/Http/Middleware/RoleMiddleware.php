<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
public function handle($request, Closure $next, ...$roles)
{
    if (! auth()->check()) {
        return redirect()->route('index')->with('auth', 'Kamu Tidak Punya Akses Ke Halaman Ini!');
    }

    if (! in_array(auth()->user()->role, $roles)) {
        if ($request->expectsJson()) {
            return response()->json([
                'icon' => 'error',
                'title' => 'Oops!',
                'text' => 'Kamu Tidak Punya Akses Ke Halaman Ini!',
            ], 403);
        }

        return redirect()->route('dashboard.admin')->with('auth', 'Kamu Tidak Punya Akses Ke Halaman Ini!');
    }

    return $next($request);
}

}
