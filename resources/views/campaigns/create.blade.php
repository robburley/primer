@extends('layouts.master')

@section('page-title')
    Create New Lead
@endsection

@section('content')
    <div class="c-toolbar u-mb-medium">
        <button class="c-sidebar-toggle u-mr-small">
            <span class="c-sidebar-toggle__bar"></span>
            <span class="c-sidebar-toggle__bar"></span>
            <span class="c-sidebar-toggle__bar"></span>
        </button>

        <h3 class="c-toolbar__title">{{ $campaign->name }} | Create New Lead</h3>
    </div>

    <div class="container">

        @if($campaign->selectedCustomFieldGroups->count() > 0)
            {{ Form::open(['route' => ['campaigns.leads.store', $campaign], 'files' => true]) }}

            @foreach($campaign->selectedCustomFieldGroups as $group)
                <div class="row u-mb-small">
                    <div class="col-lg-12">
                        <div class="u-flex u-justify-between">
                            <h4>
                                {{ $group->customFieldGroup->name }}
                            </h4>
                        </div>

                        <div class="c-card u-p-medium">
                            <div class="row">
                                @foreach($group->selectedCustomFieldsToShowToAgent as $field)
                                    <div class="col-sm-6 u-pb-small">
                                        {!! $field->customField->render() !!}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <button type="button"
                    class="c-btn c-btn--success pull-right"
                    data-toggle="modal"
                    data-target="#saveLead"
            >
                Create Lead
            </button>

        @else
            <h4>
                No Custom Fields Selected for this campaign.
            </h4>
        @endif

        <div class="c-modal c-modal--xlarge modal fade" id="saveLead" tabindex="-1" role="dialog"
             aria-labelledby="onBoardModal" data-backdrop="static">
            <div class="c-modal__dialog modal-dialog" role="document">
                <div class="modal-content">

                    <header class="c-modal__header">
                        <h1 class="c-modal__title">Add Callback</h1>

                        <span class="c-modal__close" data-dismiss="modal" aria-label="Close">
                                    <i class="fa fa-close"></i>
                                </span>
                    </header>

                    <div class="c-modal__body u-pb-small">
                        <div class="row">
                            <div class="col-sm-6 u-pb-small">
                                <div class="c-field has-addon-right">
                                    <label class="c-field__label">
                                        Call Back Date
                                    </label>
                                </div>

                                <div class="c-field has-addon-right">
                                    {!! Form::text('callback_date', \Carbon\Carbon::now()->format('d/m/Y'), ['class' => 'c-input', 'data-toggle' => 'datepicker', 'required']) !!}

                                    <span class="c-field__addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>

                            <div class="col-sm-6 u-pb-small">
                                <div class="c-field has-addon-right">
                                    <label class="c-field__label">
                                        Call Back Time
                                    </label>
                                </div>

                                <div class="c-field has-addon-right">
                                    {!! Form::text('callback_time', \Carbon\Carbon::now()->startOfHour()->addHours(4)->format('h:iA'), ['class' => 'c-input', 'data-toggle' => 'timepicker', 'required']) !!}

                                    <span class="c-field__addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>

                        <button type="submit"
                                class="c-btn c-btn--success pull-right"
                        >
                            Create Lead
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{ Form::close() }}
    </div>
@endsection
