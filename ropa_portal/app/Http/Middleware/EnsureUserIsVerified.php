<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsVerified
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !$request->user()->is_verified) {
            return redirect()->route('verify.otp');
        }

        return $next($request);
    }
}
