<?php

namespace App\Providers;

use App\Models\Campaigns\Campaign;
use App\Models\Leads\CustomFieldGroup;
use App\Models\Leads\FileUpload;
use App\Models\Leads\Lead;
use App\Models\Users\User;
use App\Policies\CampaignPolicy;
use App\Policies\CustomFieldGroupPolicy;
use App\Policies\FileUploadPolicy;
use App\Policies\LeadPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        FileUpload::class       => FileUploadPolicy::class,
        CustomFieldGroup::class => CustomFieldGroupPolicy::class,
        User::class             => UserPolicy::class,
        Campaign::class         => CampaignPolicy::class,
        Lead::class             => LeadPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
    }
}
