<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $authToken = $request->header('Authorization');
        // dd($authToken);
        $accessToken = PersonalAccessToken::findToken($authToken);
        // dd("dsd");
        // dd($accessToken);
        if ($accessToken) {
            $user = $accessToken->tokenable;
            dd($user);
        } else {
            // المستخدم غير مصادق
            return response()->json(['error' => 'Unauthenticated cff'], 401);
        }
        // $user = $request->header('Authorization');
        // dd($user);
        // // $token = $user->currentAccessToken();
        // // dd($token);
        // dd($request->headers);
        if (auth()->check() && auth()) {
            return $next($request);
        }
        return response()->json(['error' => 'Forbidden'], 403);
    }
}
