<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticatedMultiGuard
{
    /**
     * Handle an incoming request.
     * Redirect authenticated users away from login/register pages.
     * Checks all configured guards (viajero, hotel, admin).
     */
    public function handle(Request $request, Closure $next)
    {
        $guards = ['viajero', 'hotel', 'admin'];
        
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // User is authenticated; redirect to appropriate dashboard
                if ($guard === 'hotel') {
                    return redirect()->route('hotel.dashboard');
                }
                // Viajero and admin go to reservas index
                return redirect()->route('reservas.index');
            }
        }

        // Also check default guard
        if (Auth::check()) {
            return redirect()->route('reservas.index');
        }

        return $next($request);
    }
}
