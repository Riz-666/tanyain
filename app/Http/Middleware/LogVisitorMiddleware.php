<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Visitor;
use App\Models\VisitorSummary;
use App\Models\Artikel;
use App\Models\ArtikelSummary;
use App\Models\Repositori;
use App\Models\RepoSummary;

class LogVisitorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->routeIs(['dashboard.admin', 'admin.*'])) {
            // 1️⃣ Catat visitor detail
            $visitor = Visitor::updateOrCreate(
                [
                    'user_id' => auth()->check() ? auth()->id() : null,
                    'ip_address' => $request->ip(),
                    'date' => now()->toDateString(),
                ],
                [
                    'user_agent' => $request->header('User-Agent'),
                ],
            );

            $year = now()->year;
            $month = now()->month;

            // 2️⃣ Update visitor summary
            $totalVisitor = Visitor::whereYear('date', $year)->whereMonth('date', $month)->distinct('ip_address')->count('ip_address');

            VisitorSummary::updateOrCreate(['year' => $year, 'month' => $month], ['total_visitors' => $totalVisitor]);

            // 3️⃣ Update artikel summary
            $totalArtikel = Artikel::whereYear('created_at', $year)->whereMonth('created_at', $month)->count();

            ArtikelSummary::updateOrCreate(['year' => $year, 'month' => $month], ['total_artikel' => $totalArtikel]);

            // 4️⃣ Update repo summary
            $totalRepo = Repositori::whereYear('created_at', $year)->whereMonth('created_at', $month)->count();

            RepoSummary::updateOrCreate(['year' => $year, 'month' => $month], ['total_repo' => $totalRepo]);
        }

        return $next($request);
    }
}
