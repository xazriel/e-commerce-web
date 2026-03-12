<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    // Cek apakah user sudah login DAN apakah rolenya admin
    if (auth()->check() && auth()->user()->role === 'admin') {
        return $next($request);
    }

    // Jika bukan admin, tendang balik ke dashboard user dengan pesan error
    return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses admin.');
}
}