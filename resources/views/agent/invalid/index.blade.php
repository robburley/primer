@extends('layouts.master')

@section('page-title')
    Invalid Leads
@endsection

@section('content')
    <div class="c-toolbar u-mb-medium">
        <button class="c-sidebar-toggle u-mr-small">
            <span class="c-sidebar-toggle__bar"></span>
            <span class="c-sidebar-toggle__bar"></span>
            <span class="c-sidebar-toggle__bar"></span>
        </button>

        <h3 class="c-toolbar__title">Invalid Leads</h3>
    </div>

    <div class="container">
        {!! Form::open(['method' => 'get', 'id' => 'searchForm']) !!}

        <div class="row u-mb-small">
            <div class="col-md-8">
            </div>

            @if(auth()->user()->campaignsSupervisor->count() > 0)
                <div class="col-md-4">
                    <div class="c-field">
                        {!! Form::select('user_id', FormPopulator::campaignUsers(), request()->get('user_id', auth()->user()->id), ['class' => 'c-select', 'id' => 'userSearch']) !!}
                    </div>
                </div>
            @endif
        </div>
        {!! Form::close() !!}

        <div class="row">
            <div class="col-sm-12">
                <div class="c-table-responsive@desktop">
                    <table class="c-table c-table--zebra u-mb-small">
                        <thead class="c-table__head">
                            <tr class="c-table__row">
                                <th class="c-table__cell c-table__cell--head">Name</th>
                                @if(auth()->user()->campaignsSupervisor->count() > 0)
                                    <th class="c-table__cell c-table__cell--head">Assigned To</th>
                                @endif
                                <th class="c-table__cell c-table__cell--head">Created</th>
                                <th class="c-table__cell c-table__cell--head">Completed</th>
                                <th class="c-table__cell c-table__cell--head">Campaign</th>
                                <th class="c-table__cell c-table__cell--head"></th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($leads as $lead)
                                <tr class="c-table__row">
                                    <td class="c-table__cell">
                                        @if($lead->campaign->primaryNameField)
                                            {{ json_decode($lead->lead->data)->{$lead->campaign->primaryNameField->slug} }}
                                        @else
                                            No {{ $lead->campaign->primaryNameField->name}} Set
                                        @endif
                                    </td>

                                    @if(auth()->user()->campaignsSupervisor->count() > 0)
                                        <td class="c-table__cell">
                                            {{ $lead->assigned->full_name }}
                                        </td>
                                    @endif

                                    <td class="c-table__cell">
                                        {{ $lead->created_at ? $lead->created_at->format('d/m/Y H:i') : 'Not set' }}
                                    </td>

                                    <td class="c-table__cell">
                                        {{ $lead->completed_at ? $lead->completed_at->format('d/m/Y H:i') : 'Not set' }}
                                    </td>

                                    <td class="c-table__cell">
                                        {{ $lead->campaign->name }}
                                    </td>

                                    <td class="c-table__cell">
                                        <a class="c-btn c-btn--blue pull-right"
                                           href="{{ route('campaigns.leads.edit', [$lead->campaign, $lead->lead]) }}"
                                        >
                                            Update
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr class="c-table__row">
                                    <td class="c-table__cell" colspan="6">
                                        No invalid leads. Good Job!
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
