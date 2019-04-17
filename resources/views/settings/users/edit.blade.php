@extends('layouts.master')

@section('page-title')
    Users | Edit
@endsection

@section('content')
    <div class="c-toolbar u-mb-medium">
        <button class="c-sidebar-toggle u-mr-small">
            <span class="c-sidebar-toggle__bar"></span>
            <span class="c-sidebar-toggle__bar"></span>
            <span class="c-sidebar-toggle__bar"></span>
        </button>

        <h3 class="c-toolbar__title">Users</h3>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="c-tabs">
                    <ul class="c-tabs__list nav nav-tabs" id="myTab" role="tablist">
                        <li class="c-tabs__item">
                            <a class="c-tabs__link active show"
                               id="details-tab"
                               data-toggle="tab"
                               href="#details-content"
                               role="tab"
                               aria-controls="details-content"
                               aria-selected="true"
                            >
                                User Details
                            </a>
                        </li>
                    </ul>

                    <div class="c-tabs__content tab-content" id="nav-tabContent">
                        <div class="c-tabs__pane active show"
                             id="details-content"
                             role="tabpanel"
                             aria-labelledby="details-tab"
                        >
                            {!! Form::model($user, ['route' => ['settings.users.update', $user], 'method' => 'patch']) !!}

                            @include('settings.users._forms.user-details')

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
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
