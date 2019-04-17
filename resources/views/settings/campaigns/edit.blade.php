@extends('layouts.master')

@section('page-title')
    Campaigns | Edit
@endsection

@section('content')
    <div class="c-toolbar u-mb-medium">
        <button class="c-sidebar-toggle u-mr-small">
            <span class="c-sidebar-toggle__bar"></span>
            <span class="c-sidebar-toggle__bar"></span>
            <span class="c-sidebar-toggle__bar"></span>
        </button>

        <h3 class="c-toolbar__title">Campaigns</h3>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="c-tabs">
                    <ul class="c-tabs__list nav nav-tabs" id="myTab" role="tablist">
                        <li class="c-tabs__item">
                            <a class="c-tabs__link active show"
                               href="{{ route('settings.campaigns.edit', $campaign) }}"
                            >
                                Setup
                            </a>
                        </li>

                        <li class="c-tabs__item">
                            <a class="c-tabs__link"
                               id="campaigns-tab"
                               data-toggle="tab"
                               href="#campaigns-custom-fields"
                               role="tab"
                               aria-controls="campaigns-custom-fields"
                               aria-selected="false"
                            >
                                Custom Fields
                            </a>
                        </li>

                        <li class="c-tabs__item">
                            <a class="c-tabs__link"
                               id="campaigns-tab"
                               data-toggle="tab"
                               href="#campaigns-data-scopes"
                               role="tab"
                               aria-controls="campaigns-data-scopes"
                               aria-selected="false"
                            >
                                Data Scope
                            </a>
                        </li>

                        <li class="c-tabs__item">
                            <a class="c-tabs__link"
                               id="campaigns-tab"
                               data-toggle="tab"
                               href="#campaigns-users"
                               role="tab"
                               aria-controls="campaigns-users"
                               aria-selected="false"
                            >
                                Users
                            </a>
                        </li>


                        @if($campaign->validate_leads)
                            <li class="c-tabs__item">
                                <a class="c-tabs__link"
                                   id="campaigns-tab"
                                   data-toggle="tab"
                                   href="#campaigns-invalid-reasons"
                                   role="tab"
                                   aria-controls="campaigns-invalid-reasons"
                                   aria-selected="false"
                                >
                                    Rejection Reasons
                                </a>
                            </li>

                            <li class="c-tabs__item">
                                <a class="c-tabs__link"
                                   id="campaigns-tab"
                                   data-toggle="tab"
                                   href="#campaigns-confirmation-scopes"
                                   role="tab"
                                   aria-controls="campaigns-confirmation-scopes"
                                   aria-selected="false"
                                >
                                    Confirmation Scopes
                                </a>
                            </li>
                        @endif
                    </ul>

                    <div class="c-tabs__content tab-content" id="nav-tabContent">
                        <div class="c-tabs__pane active show"
                             id="details-content"
                             role="tabpanel"
                             aria-labelledby="details-tab"
                        >
                            {!! Form::model($campaign, ['route' => ['settings.campaigns.update', $campaign], 'method' => 'patch']) !!}

                            @include('settings.campaigns._forms.details')

                            <div class="row">
                                <div class="col-12">
                                    <h3 class="u-h4">
                                        Primary Fields
                                    </h3>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <p>
                                        Select which fields will be identified as primary fields for name, phone and
                                        email. These will be used to display the appropriate information at different
                                        stages of the process.
                                    </p>
                                </div>

                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="c-field u-mb-small col-12">
                                            <label class="c-field__label" for="active">
                                                Primary Name
                                            </label>

                                            {!! Form::select('primary_name_field_id', FormPopulator::selectedCustomFields($campaign), null , ['class' => 'c-select select2-hidden-accessible']) !!}
                                        </div>

                                        <div class="c-field u-mb-small col-12">
                                            <label class="c-field__label" for="first_name">
                                                Primary Phone Number
                                            </label>

                                            {!! Form::select('primary_telephone_field_id', FormPopulator::selectedCustomFields($campaign), null , ['class' => 'c-select select2-hidden-accessible']) !!}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="c-field u-mb-small col-12">
                                            <label class="c-field__label" for="active">
                                                Primary Email Address
                                            </label>

                                            {!! Form::select('primary_email_field_id', FormPopulator::selectedCustomFields($campaign), null , ['class' => 'c-select select2-hidden-accessible']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-sm-12">
                                    <button type="submit" class="c-btn c-btn--blue pull-right">
                                        Save
                                    </button>
                                </div>
                            </div>

                            {!! Form::close() !!}

                            @if ($errors->any())
                                <div class="c-alert c-alert--danger alert fade show u-mt-small">
                                    <i class="c-alert__icon fa fa-times-circle"></i>
                                    Error. There was a problem with the input <br>

                                    @foreach ($errors->all() as $error)
                                        {{ $error }} <br>
                                    @endforeach

                                    <button class="c-close" data-dismiss="alert" type="button">×</button>
                                </div>
                            @endif
                        </div>

                        <div class="c-tabs__pane"
                             id="campaigns-custom-fields"
                             role="tabpanel"
                             aria-labelledby="campaigns-tab"
                        >
                            <campaign-custom-fields
                                    :campaign="{{ $campaign }}"
                                    :groups="{{ $customFieldGroups }}"
                            ></campaign-custom-fields>
                        </div>

                        <div class="c-tabs__pane"
                             id="campaigns-data-scopes"
                             role="tabpanel"
                             aria-labelledby="campaigns-tab"
                        >
                            <campaign-data-scope
                                    :campaign="{{ $campaign }}"
                                    :groups="{{ $customFieldGroups }}"
                            ></campaign-data-scope>
                        </div>

                        <div class="c-tabs__pane"
                             id="campaigns-users"
                             role="tabpanel"
                             aria-labelledby="campaigns-tab"
                        >
                            <campaign-users
                                    :campaign="{{ $campaign }}"
                                    :users="{{ FormPopulator::activeUsers() }}"
                                    :selected="{{ $campaign->users }}"
                            ></campaign-users>
                        </div>

                        @if($campaign->validate_leads)
                            <div class="c-tabs__pane"
                                 id="campaigns-invalid-reasons"
                                 role="tabpanel"
                                 aria-labelledby="campaigns-tab"
                            >
                                <campaign-invalid-reasons
                                        :campaign="{{ $campaign }}"
                                        :initial_reasons="{{ $campaign->activeInvalidReasons->toJson() }}"
                                ></campaign-invalid-reasons>
                            </div>

                            <div class="c-tabs__pane"
                                 id="campaigns-confirmation-scopes"
                                 role="tabpanel"
                                 aria-labelledby="campaigns-tab"
                            >
                                <campaign-confirmation-scope
                                        :campaign="{{ $campaign }}"
                                        :groups="{{ $customFieldGroups }}"
                                ></campaign-confirmation-scope>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
