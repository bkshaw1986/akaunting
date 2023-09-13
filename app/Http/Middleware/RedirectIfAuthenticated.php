<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
		// Added by Rajasekar from OLD version Starts
		$safe_accounts = [
          'auth/oxygen',       
          'auth/oxygen/simple',       
        ];

        $route = $request->route();

        if (($request->method() === 'GET')) {
          if (in_array($route->uri, $safe_accounts))
          {
            return $next($request);
          }
          
        }
		// Added by Rajasekar from OLD version ends
		
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (!auth()->guard($guard)->check()) {
                continue;
            }

            return redirect(user()->getLandingPageOfUser());
        }

        return $next($request);
    }
}
