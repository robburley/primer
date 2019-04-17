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

        <h3 class="c-toolbar__title">{{ $campaign->name }} | Confirm Lead</h3>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-12">
                @include('errors.list')
            </div>
        </div>

        {!! Form::open(['route' => ['campaigns.leads.confirmation.update', $campaign, $lead], 'files' => true]) !!}
        @foreach($campaign->selectedCustomFieldGroups as $group)
            <div class="row u-mb-small">
                <div class="col-lg-12">
                    <div class="u-flex u-justify-between">
                        <h3 class="u-mb-small">
                            {{ $group->customFieldGroup->name }}
                        </h3>
                    </div>

                    <div class="c-card u-p-medium">
                        <div class="row">
                            @foreach($group->selectedCustomFields as $field)
                                <div class="col-sm-6 u-pb-small">
                                    {!! $field->customField->render(json_decode($lead->data)->{$field->customField->slug} ?? null) !!}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="row u-mt-small u-mb-small">
            <div class="col-12">
                <button type="submit"
                        class="c-btn c-btn--success pull-right"
                        name="primer_confirm_button"
                >
                    Confirm Lead
                </button>

                <button type="button"
                        class="c-btn c-btn--warning pull-right u-mr-small"
                        data-toggle="modal"
                        data-target="#saveLead"
                >
                    Save Lead
                </button>

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
                                        name="primer_save_button"
                                >
                                    Update Lead
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{ Form::close() }}

                {!! Form::open(['route' => ['campaigns.leads.confirmation.destroy', $campaign, $lead], 'method' => 'DELETE',]) !!}
                <button type="button"
                        class="c-btn c-btn--danger pull-right u-mr-small"
                        data-toggle="modal"
                        data-target="#invalidLead"
                >
                    Reject Lead
                </button>

                <div class="c-modal c-modal--xlarge modal fade" id="invalidLead" tabindex="-1" role="dialog"
                     aria-labelledby="onBoardModal" data-backdrop="static">
                    <div class="c-modal__dialog modal-dialog" role="document">
                        <div class="modal-content">
                            <header class="c-modal__header">
                                <h1 class="c-modal__title">Rejection Reason</h1>

                                <span class="c-modal__close" data-dismiss="modal" aria-label="Close">
                                    <i class="fa fa-close"></i>
                                </span>
                            </header>

                            <div class="c-modal__body u-pb-small">

                                <div class="c-field has-addon-right">
                                    <label class="c-field__label">
                                        Invalid Reason
                                    </label>
                                </div>

                                <div class="row">
                                    @foreach(\App\Helpers\FormPopulator::invalidReasons($campaign) as $reason)
                                        <div class="col-md-4">
                                            <div class="c-choice c-choice--checkbox">
                                                <input class="c-choice__input"
                                                       id="invalid_reasons{{ $reason->id }}"
                                                       name="invalid_reasons[]"
                                                       type="checkbox"
                                                       value="{{ $reason->id }}"
                                                >

                                                <label class="c-choice__label" for="invalid_reasons{{ $reason->id }}">
                                                    {{ $reason->title }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="c-field">
                                            <label class="c-field__label">
                                                Comment
                                            </label>

                                            <textarea class="c-input"
                                                      type="text"
                                                      placeholder="Comments on why the lead is invalid"
                                                      name="invalid_comment"
                                            ></textarea>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit"
                                        class="c-btn c-btn--danger pull-right u-mt-small"
                                >
                                    Invalidate Lead
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{ Form::close() }}
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <h4>Lead Notes</h4>

                {!! $errors->first('note', '<p class="u-text-danger"><i class="fa fa-fw fa-warning"></i> :message</p>') !!}

                {!! Form::open(['route' => ['campaigns.leads.notes.store', $campaign, $lead]]) !!}
                <div class="c-post">
                    <textarea name="note"
                              class="c-post__content"
                              placeholder="Add a note"
                    >

                    </textarea>

                    <div class="c-post__toolbar">
                        <button class="c-btn c-btn--info u-float-right">
                            Post
                        </button>
                    </div>
                </div>

                {!! Form::close() !!}

                <ol class="c-stream" style="max-height: 500px; overflow-y: scroll;">
                    @forelse($leadAssignment->notes as $note)
                        <li class="c-stream-item o-media">
                            <div class="c-stream-item__content o-media__body">
                                <div class="c-stream-item__header">
                                    <a class="c-stream-item__name">
                                        {{ $note->user->full_name }}
                                    </a>

                                    <span class="c-stream-item__time">
                                        {{ $note->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                <p class="u-mb-small">
                                    {!! $note->note !!}
                                </p>
                            </div>
                        </li>
                    @empty
                        <li class="c-stream-item o-media">
                            <div class="c-stream-item__content o-media__body">
                                <p class="u-mb-small">
                                    There are currently no notes.
                                </p>
                            </div>
                        </li>
                    @endforelse
                </ol>
            </div>
        </div>
    </div>
@endsection
