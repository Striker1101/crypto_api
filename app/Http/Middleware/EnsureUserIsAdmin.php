<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // $user = auth()->user();

        // // Check if the user is not authenticated and redirect to login
        // if (!$user && !$request->is('login'))
        // {
        //     return redirect()->route('login');  // Redirect to login if not authenticated
        // }

        // // Check if the user is authenticated but doesn't have the required type
        // if ($user && !in_array($user->type, ['owner', 'admin']))
        // {
        //     abort(403, 'Unauthorized access');  // or redirect to dashboard
        // }

        return $next($request);
    }

}
