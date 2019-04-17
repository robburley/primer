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

        <h3 class="c-toolbar__title">Callbacks</h3>
    </div>

    <div class="container">
        {!! Form::open(['method' => 'get', 'id' => 'searchForm']) !!}

        <div class="row u-mb-small">
            <div class="col-md-8">
                @if(request()->get('search_term'))
                    <h4>All callbacks containing the name or phone number: {{ request()->get('search_term') }}</h4>
                @else
                    <h4>Today's Callbacks</h4>
                @endif
            </div>

            @if(auth()->user()->campaignsSupervisor->count() > 0)
                <div class="col-md-4">
                    <div class="c-field">
                        {!! Form::select('user_id', FormPopulator::campaignUsers(), request()->get('user_id', auth()->user()->id), ['class' => 'c-select', 'id' => 'userSearch']) !!}
                    </div>
                </div>
            @endif
        </div>

        <div class="row u-mb-small">
            <div class="col-sm-12">
                <div class="u-flex">
                    <div class="u-mr-small" style="flex-grow: 1">
                        {!!
                            Form::text('search_term', request()->get('search_term'), [
                                'placeholder' => 'Search Name or Phone Number',
                                'class' => 'u-width-100 u-p-small',
                            ])
                        !!}
                    </div>

                    <button class="c-btn c-btn--info">
                        Search
                    </button>

                    @if(request()->get('search_term'))
                        <a class="c-btn c-btn--danger u-ml-small"
                           href="{{ route('agent.callbacks.index') }}"
                        >
                            Clear
                        </a>
                    @endif
                </div>

                @if ($errors->any())
                    <div class="u-mt-small">
                        @include('errors.list')
                    </div>
                @endif
            </div>
        </div>

        {!! Form::close() !!}

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
                                <th class="c-table__cell c-table__cell--head">Callback</th>
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
                                        {{ $lead->callback ? $lead->callback->format('d/m/Y H:i') : 'No callback set' }}
                                    </td>

                                    <td class="c-table__cell">
                                        {{ $lead->campaign->name }}
                                    </td>

                                    <td class="c-table__cell">
                                        @if($lead->completed_at)
                                            <a class="c-btn c-btn--warning pull-right"
                                               href="{{ route('campaigns.leads.confirmation.edit', [$lead->campaign, $lead->lead]) }}"
                                            >
                                                Confirm
                                            </a>
                                        @else
                                            <a class="c-btn c-btn--blue pull-right"
                                               href="{{ route('campaigns.leads.edit', [$lead->campaign, $lead->lead]) }}"
                                            >
                                                Update
                                            </a>
                                        @endif
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

                    {{--{{ $leads->links('vendor.pagination.default') }}--}}
                </div>

                <div class="c-toolbar u-justify-center u-mb-medium">
                    <div class="col-12 col-lg-10 col-xl-6">
                        <div class="row">
                            <div class="col-6 col-md-6 c-toolbar__state">
                                <h4 class="c-toolbar__state-number">{{ $leads->count() }}</h4>
                                <span class="c-toolbar__state-title">
                                    @if(request()->get('search_term'))
                                        Callbacks Searched
                                    @else
                                        Callbacks Today
                                    @endif
                                </span>
                            </div>

                            <div class="col-6 col-md-6 c-toolbar__state">
                                <h4 class="c-toolbar__state-number">{{ $tomorrowsCallbacks }}</h4>
                                <span class="c-toolbar__state-title">Callbacks Tomorrow</span>
                            </div>
                        </div><!-- // .row -->
                    </div><!-- // -->
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
