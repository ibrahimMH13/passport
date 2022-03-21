<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RefreshToken
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
        if (!$request->user() || !$request->user()->tokens){
            return $next($request);
        }
        if ($request->user()->tokens->hasExpired()){
         return   redirect()->route('sso.refresh');
        }
        return $next($request);
    }
}
