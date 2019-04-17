<?php

namespace App\Http\Middleware;

use Closure;

class IpRestriction
{
    protected $restrictionType;
    protected $currentIp;
    protected $blacklistedIps;
    protected $whitelistedIps;

    public function __construct()
    {
        if (auth()->user()) {
            $this->restrictionType = auth()->user()->tenant->restriction_type
                ? 'blacklist'
                : 'whitelist';
        }

        $this->currentIp = request()->server->get('REMOTE_ADDR');
    }

    public function handle($request, Closure $next)
    {
        if (!auth()->user()) {
            return abort(403);
        }

        if (!auth()->user()->isAdministrator()) {
            $this->{$this->restrictionType}();
        }

        return $next($request);
    }

    public function whitelist()
    {
        $ips = auth()->user()->tenant->whitelistedIps;

        if (!$ips->pluck('address')->contains($this->currentIp)) {
            $this->deny();
        }
    }

    public function blacklist()
    {
        $ips = auth()->user()->tenant->blacklistedIps;

        if ($ips->pluck('address')->contains($this->currentIp)) {
            $this->deny();
        }
    }

    public function deny()
    {
        auth()->logout();

        abort(403);
    }
}
