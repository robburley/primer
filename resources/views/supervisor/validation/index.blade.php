@extends('layouts.master')

@section('page-title')
    Validate Lead
@endsection

@section('content')
    <div class="c-toolbar u-mb-medium">
        <button class="c-sidebar-toggle u-mr-small">
            <span class="c-sidebar-toggle__bar"></span>
            <span class="c-sidebar-toggle__bar"></span>
            <span class="c-sidebar-toggle__bar"></span>
        </button>

        <h3 class="c-toolbar__title">Validate Lead</h3>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="c-table-responsive@desktop">
                    <table class="c-table c-table--zebra u-mb-small">
                        <thead class="c-table__head">
                            <tr class="c-table__row">
                                <th class="c-table__cell c-table__cell--head">Name</th>
                                <th class="c-table__cell c-table__cell--head">Assigned To</th>
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
                                            {{ json_decode($lead->lead->data)->{$lead->campaign->primaryNameField->slug} ?? 'No ' . $lead->campaign->primaryNameField->name . 'Set' }}
                                        @else
                                            No Primary Name Field Set
                                        @endif
                                    </td>

                                    <td class="c-table__cell">
                                        {{ $lead->assigned->full_name }}
                                    </td>

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
                                           href="{{ route('campaigns.leads.validate.edit', [$lead->campaign, $lead->lead]) }}"
                                        >
                                            Validate
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr class="c-table__row">
                                    <td class="c-table__cell" colspan="6">
                                        There are no leads for validation
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
