<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Illuminate\Http\Request;

class SessionChecker
{
    const NEED_LOGIN = 'You need to login first';
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (
            !session()->get('user')
            || !session()->get('role')
        ) {
            // set message
            Session::flash('message', __(self::NEED_LOGIN));
            Session::flash('message_type', 'warning');
            return redirect('/login');
        }

        // if (!in_array(session()->get('role')->role, $roles)) {
        //     // set message
        //     Session::flash('message', 'You dont have permission');
        //     Session::flash('message_type', 'warning');

        //     if (isset(session()->get('role')->home_url)) {
        //         return redirect(session()->get('role')->home_url);
        //     } else {
        //         return redirect()->back();
        //     }
        // }
        
        return $next($request);
    }
}
