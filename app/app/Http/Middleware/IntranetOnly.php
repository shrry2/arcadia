<?php

namespace App\Http\Middleware;

use Closure;

class IntranetOnly
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
        if (!$request->fromIntranet) {
            return abort(403, 'このリソースは社内ネットワークのみ利用可能です');
        }

        return $next($request);
    }
}
