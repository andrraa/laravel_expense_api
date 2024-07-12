<?php

namespace App\Http\Middleware;

use App\Models\Token;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('Authorization');

        if (!$token) {
            return response()->json([
                "errors" => [
                    "message" => "Unauthorized"
                ]
            ], 401);
        }

        $tokenData = Token::query()->where('token', $token)->first();

        if (!$tokenData || !$tokenData->user) {
            return response()->json([
                "errors" => [
                    "message" => "Unauthorized"
                ]
            ], 401);
        }

        Auth::login($tokenData->user);

        return $next($request);
    }
}
