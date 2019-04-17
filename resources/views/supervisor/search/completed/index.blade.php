@extends('layouts.master')

@section('page-title')
    Callbacks
@endsection

@section('content')
    <div class="c-toolbar u-mb-medium">
        <button class="c-sidebar-toggle u-mr-small">
            <span class="c-sidebar-toggle__bar"></span>
            <span class="c-sidebar-toggle__bar"></span>
            <span class="c-sidebar-toggle__bar"></span>
        </button>

        <h3 class="c-toolbar__title">Search Completed Leads</h3>
    </div>

    <div class="container">
        <div class="row u-mb-small">
            <div class="col-md-8">
                <h4>All completed containing the name or phone number: {{ request()->get('search_term') }}</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="c-table-responsive@desktop">
                    <table class="c-table c-table--zebra">
                        <thead class="c-table__head">
                            <tr class="c-table__row">
                                <th class="c-table__cell c-table__cell--head">Name</th>
                                @if(auth()->user()->campaignsSupervisor->count() > 0)
                                    <th class="c-table__cell c-table__cell--head">Assigned To</th>
                                @endif
                                <th class="c-table__cell c-table__cell--head">Sent</th>
                                <th class="c-table__cell c-table__cell--head">Campaign</th>
                                <th class="c-table__cell c-table__cell--head"></th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($leads as $lead)
                                <tr class="c-table__row">
                                    <td class="c-table__cell">
                                        @if($lead->campaign->primaryNameField)
                                            {{
                                                json_decode($lead->lead->data)->{$lead->campaign->primaryNameField->slug}
                                                ?? $lead->campaign->primaryNameField->name
                                            }}
                                        @else
                                            No Primary Field Set Set
                                        @endif
                                    </td>

                                    @if(auth()->user()->campaignsSupervisor->count() > 0)
                                        <td class="c-table__cell">
                                            {{ $lead->assigned->full_name }}
                                        </td>
                                    @endif

                                    <td class="c-table__cell">
                                        {{ $lead->sent_at ? $lead->sent_at->format('d/m/Y H:i') : 'Not sent' }}
                                    </td>

                                    <td class="c-table__cell">
                                        {{ $lead->campaign->name }}
                                    </td>

                                    <td class="c-table__cell">
                                        {!! Form::open(['route' => ['supervisor.search.completed.store', $lead->campaign, $lead->lead]]) !!}
                                        {!! Form::hidden('access_reason', $accessReason) !!}
                                        <button class="c-btn c-btn--blue pull-right"
                                        >
                                            Update
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr class="c-table__row">
                                    <td class="c-table__cell" colspan="6">
                                        @if(request()->get('search_term'))
                                            No Callbacks for in this search.
                                        @else
                                            No Callbacks for today.
                                        @endif

                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('#userSearch').change(function () {
            $('#searchForm').submit()
        })
    </script>
@endsection
