<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        if (!auth()->user()) {
            return response()->json(
                [
                    'status' => 0,
                    'message' => 'Unauthenticated'
                ]
            );
        }
        if (auth()->check()) {
            if (auth()->user()->type == 1) {
                return $next($request);
            }
            return response()->json(
                [
                    'status' => 0,
                    'message' => 'your token for tourist but this api to admin'
                ]
            );
        }
        return response()->json(['error' => 'Forbidden'], 403);
    }
}
