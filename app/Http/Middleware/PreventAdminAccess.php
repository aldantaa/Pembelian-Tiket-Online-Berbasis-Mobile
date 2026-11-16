<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventAdminAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->role === 'admin') {
            abort(403, 'Admin tidak dapat mengakses halaman jadwal pelanggan.');
        }

        return $next($request);
    }
}
