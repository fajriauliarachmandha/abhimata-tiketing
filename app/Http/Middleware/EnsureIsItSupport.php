<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsItSupport
{
    public function handle(Request $request, Closure $next): Response
    {

        if (auth()->user()?->role !== 'it_support') {
            abort(403, 'akses ditolak');
        }

        return $next($request);
    }
}
