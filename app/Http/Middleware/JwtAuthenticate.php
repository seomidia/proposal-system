<?php

namespace App\Http\Middleware;

use App\Services\JwtService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JwtAuthenticate
{
    public function handle(Request $request, Closure $next): Response
    {
        $header = $request->header('Authorization');
        if (!$header || !str_starts_with($header, 'Bearer ')) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $token = substr($header, 7);
        $payload = JwtService::decode($token, env('JWT_SECRET', 'secret'));

        if (!$payload) {
            return response()->json(['message' => 'Invalid token.'], 401);
        }

        $request->attributes->set('jwt_payload', $payload);
        return $next($request);
    }
}
