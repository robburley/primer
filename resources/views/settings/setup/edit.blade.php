@extends('layouts.master')

@section('page-title')
    Setup
@endsection

@section('content')
    <div class="c-toolbar u-mb-medium">
        <button class="c-sidebar-toggle u-mr-small">
            <span class="c-sidebar-toggle__bar"></span>
            <span class="c-sidebar-toggle__bar"></span>
            <span class="c-sidebar-toggle__bar"></span>
        </button>

        <h3 class="c-toolbar__title">Setup</h3>
    </div>


    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h4>
                    Company Details
                </h4>
            </div>

            <div class="col-sm-12  c-card u-pv-small u-mb-small">
                <div class="row">
                    <div class="c-field u-mb-small col-lg-6">
                        <label class="c-field__label" for="name">Company Name</label>

                        {!! Form::text('name', $tenant->name, ['class' => 'c-input', 'disabled']) !!}
                    </div>

                    <div class="c-field u-mb-small col-lg-6">
                        <label class="c-field__label" for="domain">Domain</label>

                        {!! Form::text('domain', $tenant->domain . '.getprimer.com', ['class' => 'c-input', 'disabled']) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <h4>
                    IP Restriction
                </h4>
            </div>

            <div class="col-sm-12  c-card u-pv-small u-mb-small">
                <div class="col-sm-12">
                    <ip-black-white-list
                            :type="{{ auth()->user()->tenant->restriction_type }}"
                            :blacklist="{{ auth()->user()->tenant->blacklistedIps }}"
                            :whitelist="{{ auth()->user()->tenant->whitelistedIps }}"
                    ></ip-black-white-list>
                </div>
            </div>
        </div>
    </div>
@endsection
