<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BanCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->banned) {
            // Разрешаем выход даже забаненному
            if ($request->route() && in_array($request->route()->getName(), ['banned', 'logout'])) {
                return $next($request);
            }
            return redirect()->route('banned');
        }
        return $next($request);
    }
} 