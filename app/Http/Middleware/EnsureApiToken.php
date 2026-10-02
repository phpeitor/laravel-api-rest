<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $configuredToken = (string) config('services.api_token', '');
        $providedToken = (string) $request->bearerToken();

        if ($configuredToken === '') {
            return response()->json([
                'message' => 'La autenticación de la API no está configurada.',
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        if ($providedToken === '' || ! hash_equals($configuredToken, $providedToken)) {
            return response()->json([
                'message' => 'Token Bearer ausente o inválido.',
            ], Response::HTTP_UNAUTHORIZED, [
                'WWW-Authenticate' => 'Bearer',
            ]);
        }

        return $next($request);
    }
}
