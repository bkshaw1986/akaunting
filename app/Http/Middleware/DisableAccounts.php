<?php

namespace App\Http\Middleware;
use Closure;

class DisableAccounts
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $safe_accounts = [
          '/',
          'sales/invoices/{invoice}', 
          'purchases/bills/{bill}',
          'api/wallet/transaction/create',
          'api/wallet/transaction/accept',
          'api/wallet/transaction/grant'
        ];

        $route = $request->route();

        if (($request->method() === 'GET')) {
          if (in_array($route->uri, $safe_accounts))
          {
            session([ 'show_accounts' => true ]);
          }
          else
          {
            session([ 'show_accounts' => false ]);          
          }
        }

        if (($request->method() == 'POST') || ($request->method() == 'PATCH') || ($request->method() == 'DELETE')) {
            
        }

        return $next($request);
    }
}
