<div class="o-page__sidebar js-page-sidebar">
    <div class="c-sidebar">
        <a class="c-sidebar__brand" href="{{ route('dashboard') }}">
            <img class="c-sidebar__brand-img" src="{{ url('img/sidebar-icon3.png') }}" alt="Logo"> Primer
        </a>

        @if(auth()->user()->hasCampaigns())
            <h4 class="c-sidebar__title">Campaigns</h4>

            <ul class="c-sidebar__list">
                @foreach(auth()->user()->campaigns as $campaign)
                    <li class="c-sidebar__item has-submenu {{ request()->is("campaigns/$campaign->id*") ? 'is-open' : '' }}">
                        <a class="c-sidebar__link {{ request()->is("campaigns/$campaign->id*") ? '' : 'collapsed' }}"
                           data-toggle="collapse"
                           href="#sidebar-submenu-{{$campaign->id}}"
                           aria-expanded="true" aria-controls="sidebar-submenu"
                        >
                            {{ ucwords($campaign->name) }}
                        </a>

                        <ul class="c-sidebar__submenu {{ request()->is("campaigns/$campaign->id*") ? 'show' : 'collapse collapsed' }}"
                            id="sidebar-submenu-{{$campaign->id}}"
                        >
                            @if($campaign->pivot->confirm_lead)
                                <li>
                                    <a class="c-sidebar__link"
                                       href="{{ route('campaigns.leads.confirmation.store', $campaign) }}">
                                        <i class="fa fa-fw fa-check-square-o u-mr-xsmall"></i> Confirm Lead
                                    </a>
                                </li>
                            @endif

                            @if($campaign->pivot->update_lead)
                                <li>
                                    <a class="c-sidebar__link" href="{{ route('campaigns.leads.request', $campaign) }}">
                                        <i class="fa fa-fw fa-edit u-mr-xsmall"></i> Request Lead
                                    </a>
                                </li>
                            @endif

                            @if($campaign->pivot->create_new_lead)
                                <li>
                                    <a class="c-sidebar__link"
                                       href="{{ route('campaigns.leads.create', $campaign) }}">
                                        <i class="fa fa-fw fa-file u-mr-xsmall"></i> New Lead
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endforeach
            </ul>
        @endif

        @if(auth()->user()->isCampaignSupervisor())
            <h4 class="c-sidebar__title">Supervisor</h4>

            <ul class="c-sidebar__list">
                <li>
                    <a class="c-sidebar__link {{ request()->is('supervisor/search/completed*') ? 'is-active' : '' }}"
                       data-toggle="modal"
                       data-target="#searchCompletedLeads"
                    >
                        <i class="fa fa-fw fa-search u-mr-xsmall"></i> Search Completed
                    </a>
                </li>

                <li>
                    <a class="c-sidebar__link {{ request()->is('reports*') ? 'is-active' : '' }}"
                       href="{{ route('supervisor.reports.index') }}"
                    >
                        <i class="fa fa-fw fa-file-text-o u-mr-xsmall"></i> Reports
                    </a>
                </li>
            </ul>
        @endif

        @if(auth()->user()->hasCampaigns())
            <h4 class="c-sidebar__title">Agent</h4>

            <ul class="c-sidebar__list">
                <li class="c-sidebar__item">
                    <a class="c-sidebar__link {{ request()->is('agent/callbacks*') ? 'is-active' : '' }}"
                       href="{{ route('agent.callbacks.index') }}"
                    >
                        <i class="fa fa-phone u-mr-xsmall"></i> Callbacks
                    </a>
                </li>

                <li>
                    <a class="c-sidebar__link {{ request()->is('agent/invalid*') ? 'is-active' : '' }}"
                       href="{{ route('agent.invalid.index') }}"
                    >
                        <i class="fa fa-fw fa-close u-mr-xsmall"></i> Invalid Leads
                    </a>
                </li>
            </ul>
        @endif

        @if(auth()->user()->isAdministrator())
            <h4 class="c-sidebar__title">Settings</h4>

            <ul class="c-sidebar__list">
                <li class="c-sidebar__item">
                    <a class="c-sidebar__link {{ request()->is('settings/setup*') ? 'is-active' : '' }}"
                       href="{{ route('settings.setup.edit') }}"
                    >
                        <i class="fa fa-cogs u-mr-xsmall"></i> Setup
                    </a>
                </li>

                <li class="c-sidebar__item">
                    <a class="c-sidebar__link {{ request()->is('settings/custom-fields*') ? 'is-active' : '' }}"
                       href="{{ route('settings.custom-fields.index') }}"
                    >
                        <i class="fa fa-cog u-mr-xsmall"></i> Custom Fields
                    </a>
                </li>

                <li class="c-sidebar__item">
                    <a class="c-sidebar__link {{ request()->is('settings/upload-leads*') ? 'is-active' : '' }}"
                       href="{{ route('settings.upload-leads.index') }}"
                    >
                        <i class="fa fa-upload u-mr-xsmall"></i> Upload Leads
                    </a>
                </li>

                <li class="c-sidebar__item">
                    <a class="c-sidebar__link {{ request()->is('settings/campaigns*') ? 'is-active' : '' }}"
                       href="{{ route('settings.campaigns.index') }}"
                    >
                        <i class="fa fa-bullhorn u-mr-xsmall"></i> Campaigns
                    </a>
                </li>

                <li class="c-sidebar__item ">
                    <a class="c-sidebar__link {{ request()->is('settings/users*') ? 'is-active' : '' }}"
                       href="{{ route('settings.users.index') }}"
                    >
                        <i class="fa fa-user u-mr-xsmall"></i> Users
                    </a>
                </li>
            </ul>
        @endif

        <h4 class="c-sidebar__title">User Account</h4>

        <ul class="c-sidebar__list">
            <li class="c-sidebar__item">
                <a class="c-sidebar__link" href="{{ route('logout') }}">
                    <i class="fa fa-sign-out u-mr-xsmall"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</div>

@include('layouts.modals.supervisor-search')