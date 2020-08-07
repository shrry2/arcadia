<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

use \App\Setting;

class IntranetOrAuthenticated
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
        // pass if user is allowed to access from outside
        $user = $request->user();
        if ($user && $user->can('outside access')) {
            return $next($request);
        }

        // pass if accessing from intranet
        if ($request->fromIntranet) {
            return $next($request);
        }

        if ($user && !$user->can('outside access')) {
            Auth::logout();
            return redirect(route('login'))
                ->with('message', ['status' => 'danger', 'body' => 'あなたのアカウントは社外ネットワークからのアクセスを許可されていません']);
        }

        return redirect(route('login'))
            ->with('message', ['status'=>'danger', 'body'=>'該当のリソースは社内ネットワークのみ利用可能です。社外からのアクセス許可がなされている場合は、以下で個人認証を行ってください。']);
    }
}
