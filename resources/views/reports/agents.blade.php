@extends('layouts.master')

@section('page-title')
    Reports | Agents Report
@endsection

@section('content')
    <div class="c-toolbar u-mb-medium">
        <button class="c-sidebar-toggle u-mr-small">
            <span class="c-sidebar-toggle__bar"></span>
            <span class="c-sidebar-toggle__bar"></span>
            <span class="c-sidebar-toggle__bar"></span>
        </button>

        <h3 class="c-toolbar__title">Agents Report</h3>
    </div>

    <div class="container">
        {!! Form::open(['method' => 'get']) !!}
        <div class="row">
            <div class="col-md-3 u-mb-small">
                <div class="c-field has-addon-right">
                    {!! Form::text('start_date', $start->format('d/m/Y'), ['class' => 'c-input', 'data-toggle' => 'datepicker', 'placeholder' => 'Start Date']) !!}
                    <span class="c-field__addon"><i class="fa fa-calendar"></i></span>
                </div>
            </div>

            <div class="col-md-3 u-mb-small">
                <div class="c-field has-addon-right">
                    {!! Form::text('end_date', $start->format('d/m/Y'), ['class' => 'c-input', 'data-toggle' => 'datepicker', 'placeholder' => 'End Date']) !!}
                    <span class="c-field__addon"><i class="fa fa-calendar"></i></span>
                </div>
            </div>

            <div class="col-md-3 u-mb-small">
                <div class="c-field">
                    {!! Form::select('campaign_id', auth()->user()->campaignsSupervisor->pluck('name', 'id'), $campaign, ['class'=> 'c-select']) !!}
                </div>
            </div>

            <div class="col-md-3 u-mb-small">
                <button class="c-btn c-btn--info c-btn--fullwidth">
                    Filter
                </button>
            </div>
        </div>
        {!! Form::close() !!}

        <div class="row">
            <div class="col-sm-12">
                <table class="c-table c-table--zebra u-mb-small">
                    <thead class="c-table__head">
                        <tr class="c-table__row">
                            <th class="c-table__cell c-table__cell--head">Name</th>
                            <th class="c-table__cell c-table__cell--head">Completed</th>
                            <th class="c-table__cell c-table__cell--head">Validated</th>
                            <th class="c-table__cell c-table__cell--head">Invalidated</th>
                            <th class="c-table__cell c-table__cell--head">Qualified</th>
                            <th class="c-table__cell c-table__cell--head">Dealt</th>
                            <th class="c-table__cell c-table__cell--head">GP</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($agents as $agent => $data)
                            <tr class="c-table__row">
                                <td class="c-table__cell">
                                    {{ $agent }}
                                </td>

                                <td class="c-table__cell">
                                    {{  $data['total'] }}
                                </td>

                                <td class="c-table__cell">
                                    {{  $data['validated'] }}
                                </td>

                                <td class="c-table__cell">
                                    {{  $data['invalidated'] }}
                                </td>

                                <td class="c-table__cell">
                                    {{  $data['qualified'] }}
                                </td>

                                <td class="c-table__cell">
                                    {{  $data['dealt'] }}
                                </td>

                                <td class="c-table__cell">
                                    £{{  number_format($data['gp'], 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr class="c-table__row">
                                <td class="c-table__cell" colspan="7">No leads created in this date range</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
