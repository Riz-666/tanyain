<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $role)
    {
        if (!auth()->check()) {
            return redirect()->route('index')->with('auth', 'Kamu tidak punya akses ke halaman ini');
        }

        if (auth()->user()->role !== $role) {
            return redirect()->route('index')->with('auth', 'Kamu tidak punya akses ke halaman ini');
        }

        return $next($request);
    }
}
