<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  ...$roles  // Esto capturará todos los roles que pases (ej: 'medicos', 'secretario')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::user();

        if (!$user || !in_array($user->rol, $roles)) {

            return response()->json([
                'message' => 'Acceso no autorizado. No tienes el rol permitido.'
            ], 403);
        }

        return $next($request);
    }
}
