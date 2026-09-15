<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            $routeName = $request->route()->getName();

            // Allow admin access to form editing and updating (for restricted steps)
            if (! in_array($routeName, ['ropa.edit', 'ropa.update', 'ropa.add-more'])) {
                return redirect()->route('admin.dashboard');
            }
        }

        return $next($request);
    }
}
