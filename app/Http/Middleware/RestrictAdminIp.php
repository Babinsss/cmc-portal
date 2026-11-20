<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpFoundation\Response;

class RestrictAdminIp
{
    public function handle(Request $request, Closure $next): Response
    {
        $allowedIps = config('cmc.admin_allowed_ips', []);

        if ($allowedIps === []) {
            return $next($request);
        }

        $clientIp = $request->ip();

        if ($clientIp !== null && IpUtils::checkIp($clientIp, $allowedIps)) {
            return $next($request);
        }

        abort(403, 'Access to the CMC Admin Panel is restricted to authorized hospital workstations.');
    }
}
