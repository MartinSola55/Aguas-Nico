<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next)
    {
        // Verifica si el usuario tiene el rol de administrador
        if ($request->user() && $request->user()->rol_id === 1) {
            // Si el usuario es administrador, permite el acceso a la ruta
            return $next($request);
        }

        // Si el usuario no es administrador, redirige a una página de error o muestra un mensaje de error
        return redirect()->route('home')->with('error', 'No tienes permiso para acceder a esta página');
    }
}
