<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\View\Factory;
use Symfony\Component\HttpFoundation\IpUtils;

use App\Intranet;

class IntranetChecker
{
    public function __construct(Factory $viewFactory)
    {
        $this->viewFactory = $viewFactory;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $clientIp = $request->ip();
        $intranets = Intranet::All();

        $fromIntranet = false;
        $intranet = null;
        $operator = null;

        foreach($intranets as $checkIntranet) {
            if (IpUtils::checkIp($clientIp, $checkIntranet->ip_address)) {
                $fromIntranet = true;
                $intranet = $checkIntranet;
            }
        }

        $this->viewFactory->share('fromIntranet', $fromIntranet);
        $this->viewFactory->share('intranetData', $intranet);

        // イントラネットからのアクセスで担当者IDが指定されていれば担当者をリクエストに乗せる
        if ($intranet && $request->header('X-Operator-Id')) {
            $operatorId = intval($request->header('X-Operator-Id'));
            $operator = $intranet->office->workingMembers->firstWhere('id', $operatorId);
        }

        $request->merge([
            'fromIntranet' => $fromIntranet,
            'intranet' => $intranet,
            'operator' => $operator,
        ]);

        return $next($request);
    }
}
