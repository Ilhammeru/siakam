<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
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
        $roles = explode(':', $role);

        foreach($roles as $role) {
            if($request->user()->role == $role) {
                return $next($request);
            }
        }
        
        $notify[] = ['error', 'Error Code : 404 Not Found'];
        return redirect('dashboard')->withNotify($notify);
    }
}
