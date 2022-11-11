<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Middleware\EnsureClientIsValid;

class EnsureClientIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (($request->header('38948f839e704e8dbd4ea2650378a388')) != null && in_array($request->header("38948f839e704e8dbd4ea2650378a388"), ["0b5e7030aa4a4ee3b1ccdd4341ca3867", "97dc0fefc57d4098abd1e6144e0a46d3"]))
            return $next($request);
        else {
            return response()->json(["error" => "Unauthorized client"], 403);
        }
    }
}
