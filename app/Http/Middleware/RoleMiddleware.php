<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Pastikan sudah login dan role sesuai parameter (misal: 'admin')
        if (! $request->user() || $request->user()->role !== $role) {
            abort(403, 'Akses ditolak. Halaman ini khusus untuk ' . $role . '.');
        }

        return $next($request);
    }
}