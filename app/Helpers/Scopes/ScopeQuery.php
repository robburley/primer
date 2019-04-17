<?php

namespace App\Helpers\Scopes;

use App\Models\Leads\Lead;

class ScopeQuery
{
    protected $scopes;
    protected $campaign;
    protected $includeAssigned;

    public function __construct($scopes, $campaign, $includeAssigned = true, $query = null)
    {
        $this->scopes = $scopes;

        $this->campaign = $campaign;

        $this->includeAssigned = $includeAssigned;

        $this->query = $query ?? Lead::whereNotNull('leads.created_at')
                                     ->with([
                                         'campaigns',
                                         'users',
                                     ]);
    }

    public function handle()
    {
        $this->scopes->each(function ($scope) {
            (new ScopeQueryBuilder($scope, $this->query))->handle();
        });

        $this->includeAssigned
            ? $this->query->orWhere(function ($qry) {
                return $qry->whereHas('campaigns', function ($q) {
                    return $q->where('campaigns.id', $this->campaign->id);
                });
            })
            : $this->query->where(function ($qry) {
                return $qry->whereDoesntHave('campaigns', function ($q) {
                    return $q->where('campaigns.id', $this->campaign->id);
                });
            });

        return $this->query->get();
    }

    public function buildQuery($query)
    {
    }
}
