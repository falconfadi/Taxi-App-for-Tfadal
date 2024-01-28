<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FrameHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    // X-Frame-Options header missing
    public function handle(Request $request, Closure $next)
    {
       // return $next($request);
        $response = $next($request);
        $response->header('X-Frame-Options', 'ALLOW FROM https://tikram-group.com/');
        return $response;
    }
}
