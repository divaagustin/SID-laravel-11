<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class WargaAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('warga')->check()) {
            return redirect()->route('mandiri.login')
                ->with('error', 'Silakan login terlebih dahulu untuk mengakses Layanan Mandiri Warga.');
        }

        return $next($request);
    }
}
