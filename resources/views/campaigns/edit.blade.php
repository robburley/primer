@extends('layouts.master')

@section('page-title')
    Edit Lead
@endsection

@section('content')
    <div class="c-toolbar border-bottom">
        <button class="c-sidebar-toggle u-mr-small">
            <span class="c-sidebar-toggle__bar"></span>
            <span class="c-sidebar-toggle__bar"></span>
            <span class="c-sidebar-toggle__bar"></span>
        </button>

        <h3 class="c-toolbar__title">{{ $campaign->name }} | Edit Lead</h3>
    </div>

    <div class="c-navbar u-border-top">
        <nav class="c-nav collapse" id="main-nav">
            <ul class="c-nav__list">
                <li class="c-nav__item">
                        <span class="c-nav__link u-color-warning">

                            <i class="fa fa-exclamation-triangle u-pr-xsmall"></i>

                            Accessed: {{ auth()->user()->leadAccessCount($leadAssignment) }}
                        </span>
                </li>

                {{--<li class="c-nav__item">--}}
                {{--<a class="c-nav__link" href="#!">--}}
                {{--Request Information--}}
                {{--</a>--}}
                {{--</li>--}}
            </ul>
        </nav>

        <span class="c-navbar__brand"></span>

        <!-- // Navigation items  -->
        <button class="c-nav-toggle" type="button" data-toggle="collapse" data-target="#main-nav">
            <span class="c-nav-toggle__bar"></span>
            <span class="c-nav-toggle__bar"></span>
            <span class="c-nav-toggle__bar"></span>
        </button>
    </div>

    <div class="container u-mt-medium">
        @if($lead->isComplete($campaign))
            @if($campaign->validate_leads)
                @if(!$lead->isValid($campaign) && !$lead->isSent($campaign) && !$lead->isInvalid($campaign))
                    <div class="c-alert c-alert--warning">
                        <i class="c-alert__icon fa fa-exclamation-circle"></i>

                        This lead has been completed and is awaiting validation!
                    </div>
                @elseif($lead->isInvalid($campaign))
                    <div class="c-alert c-alert--danger flex-column">
                        <div class="u-flex w-100">
                            <i class="c-alert__icon fa fa-exclamation-triangle"></i>

                            This lead has been sent back as invalid due to the following reasons:
                        </div>

                        <div class="w-100">
                            <ul>
                                @foreach($lead->getInvalidReasons($campaign) as $reason)
                                    <li class="u-color-white u-pl-medium">
                                        <i class="fa fa-close"></i>

                                        {{ $reason->invalidReason->title }}

                                        <small>- {{ $reason->invalidReason->description }}</small>
                                    </li>
                                @endforeach

                                <li class="u-color-white u-pl-medium">
                                    <i class="fa fa-close"></i>

                                    {{ $leadAssignment->invalid_comment }}
                                </li>
                            </ul>
                        </div>

                        <div class="w-100">
                        </div>
                    </div>
                @else
                    <div class="c-alert c-alert--success">
                        <i class="c-alert__icon fa fa-check-circle"></i> This lead has been completed!
                    </div>
                @endif
            @else
                <div class="c-alert c-alert--success">
                    <i class="c-alert__icon fa fa-check-circle"></i> This lead has been completed!
                </div>
            @endif
        @endif

        @if(!$lead->isComplete($campaign) && $lead->isCallback($campaign))
            <div class="c-alert c-alert--info">
                <i class="c-alert__icon fa fa-phone"></i>

                This lead is a callback
            </div>
        @endif

        @if($campaign->selectedCustomFieldGroups->count() > 0)
            {!! Form::open(['route' => ['campaigns.leads.update', $campaign, $lead], 'files' => true]) !!}

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
                                        {!! $field->customField->render(json_decode($lead->data)->{$field->customField->slug} ?? null, $leadAssignment->completed_at && !$leadAssignment->rejected_at) !!}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            @if(!$lead->isComplete($campaign) || $lead->isInvalid($campaign))
                <div class="row u-mt-small u-mb-small">
                    <div class="col-12">
                        <button type="button"
                                class="c-btn c-btn--success pull-right"
                                data-toggle="modal"
                                data-target="#saveLead"
                        >
                            Update Lead
                        </button>
                    </div>
                </div>
            @endif
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
                            Update Lead
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{ Form::close() }}

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
