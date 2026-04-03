<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    // This method is called automatically by Laravel for every
    // request that passes through this middleware
    //
    // $request → the incoming HTTP request
    // $next    → a function that passes the request to the next layer
    // $role    → the role we require (e.g. 'admin'), passed from the route
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // auth()->user() works here because auth:api middleware
        // already ran before this middleware and fetched the user.
        // IMPORTANT: CheckRole must always be used AFTER auth:api
        $user = auth()->user();

        // Check if the authenticated user has the required role
        if ($user->role !== $role) {
            return response()->json([
                'message' => 'Accès refusé. Vous n\'avez pas les droits nécessaires.',
            ], 403);
        }

        // If role matches, pass the request through to the next layer
        // (either another middleware or the controller method)
        return $next($request);
    }
}