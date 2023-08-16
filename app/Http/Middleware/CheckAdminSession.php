<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdminSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role)
    {
        $userRole = session('role'); // Adjust this to your session data

        // Check if the user's role matches the required role
        if ($userRole !== $role) {
            // Redirect or return a response based on unauthorized access
            return redirect()->route('login'); // Adjust the route as needed
        }
        return $next($request);
    }
}
