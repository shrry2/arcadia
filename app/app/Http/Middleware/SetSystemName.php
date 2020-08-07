<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\View;

use App\Setting;

class SetSystemName
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
        $systemName = 'ARCADIA';
        $systemNameSetting = Setting::find('system_name');
        if ($systemNameSetting) {
            $systemName = $systemNameSetting->value;
        }
        View::share('systemName', $systemName);

        return $next($request);
    }
}
