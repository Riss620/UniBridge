<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifiedMiddleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (auth()->check() && !auth()->user()->isVerified()) {
            if (!$request->routeIs('otp.*', 'logout')) {
                return redirect()->route('otp.verify');
            }
        }
        return $next($request);
    }
}
