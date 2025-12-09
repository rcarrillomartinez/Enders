<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckMultiGuardAuth
{
    /**
     * Manejar una solicitud entrante.
     * Verificar todos los guardias (viajero, hotel, admin) para autenticación.
     */
    public function handle(Request $request, Closure $next)
    {
        // Verificar si el usuario está autenticado en cualquiera de los guardias personalizados
        $guards = ['viajero', 'hotel', 'admin'];
        
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // El usuario está autenticado en este guardián
                return $next($request);
            }
        }

        // Verificar si el usuario está autenticado en el guardián por defecto
        if (Auth::check()) {
            return $next($request);
        }

        // El usuario no está autenticado, redirigir al inicio de sesión
        return redirect()->route('login');
    }
}
