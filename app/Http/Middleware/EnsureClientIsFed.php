<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use App\Models\Psetting;

class EnsureClientIsFed
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
        if (($request->header('cYknyb99NOY9VwsAbQS9')) != null && in_array($request->header("cYknyb99NOY9VwsAbQS9"), [
        "gpJ745poNFKkZK44irpB",
        "1eA7pK4ZEOx174NPRwqT",
        "uwtdCE3qjOYCA5jiDNYo",
        "MxmGdkc8tDxR40CvnPgL",
        "weNpWdl2UmAONFwbCeHp",
        "rbZZd8jreHHJEkJpSznq",
        "UqZ2t1CaATRRBZfHUsCh",
        "POfIk16d0VlmvaDUzwB6",
        "OkAAgZ7rHThyYCObrRxO",]))
            return $next($request);
        else {
            return response()->json(["error" => "Unauthorized client"], 403);
        }
    }

    public function terminate($request, $response)
    {
        // info($request->server());
    }
}
