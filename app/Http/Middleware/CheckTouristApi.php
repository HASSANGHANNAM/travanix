<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class CheckTouristApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->user()) {
            return response()->json(
                [
                    'status' => 0,
                    'message' => 'Unauthenticated'
                ]
            );
        }
        if (auth()->check()) {
            if (auth()->user()->type == 2) {
                return $next($request);
            }
            return response()->json(
                [
                    'status' => 0,
                    'message' => 'your token for admin but this api to tourist'
                ]
            );
        }
        return response()->json(['error' => 'Forbidden'], 403);
    }
}
