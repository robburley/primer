@extends('layouts.master')

@section('page-title')
    Campaigns
@endsection

@section('content')
    <div class="c-toolbar u-mb-medium">
        <button class="c-sidebar-toggle u-mr-small">
            <span class="c-sidebar-toggle__bar"></span>
            <span class="c-sidebar-toggle__bar"></span>
            <span class="c-sidebar-toggle__bar"></span>
        </button>

        <h3 class="c-toolbar__title">Campaigns</h3>

        <a class="c-btn c-btn--blue u-ml-auto"
           href="{{ route('settings.campaigns.create') }}">
            New Campaign
        </a>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="c-table-responsive@desktop">
                    <table class="c-table c-table--zebra u-mb-small">
                        <thead class="c-table__head">
                            <tr class="c-table__row">
                                <th class="c-table__cell c-table__cell--head">Name</th>
                                <th class="c-table__cell c-table__cell--head">Total Leads</th>
                                <th class="c-table__cell c-table__cell--head">Created</th>
                                <th class="c-table__cell c-table__cell--head"></th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($campaigns as $campaign)
                                <tr class="c-table__row @if(!$campaign->active) c-table__row--danger @endif">
                                    <td class="c-table__cell">
                                        {{ $campaign->name }}
                                    </td>

                                    <td class="c-table__cell">
                                        {{ $campaign->total_leads }}
                                    </td>

                                    <td class="c-table__cell">
                                        {{ $campaign->created_at->format('d/m/Y') }}
                                    </td>

                                    <td class="c-table__cell">
                                        <a class="c-btn c-btn--blue pull-right"
                                           href="{{ route('settings.campaigns.edit', $campaign) }}">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr class="c-table__row">
                                    <td class="c-table__cell" colspan="5">
                                        There are no campaigns
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $campaigns->links('vendor.pagination.default') }}
                </div>
            </div>
        </div>
    </div>
@endsection
