<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authenticate extends Middleware
{
    /**
     * Devuelve un error JSON si no está autenticado.
     */
    protected function unauthenticated($request, array $guards)
    {
        abort(response()->json([
            'message' => 'No autenticado. Por favor, inicia sesión.'
        ], Response::HTTP_UNAUTHORIZED));
    }

    /**
     * No redirigir nunca en APIs.
     */
    protected function redirectTo(Request $request): ?string
    {
        return null;
    }
}
