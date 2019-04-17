@extends('layouts.master')

@section('page-title')
    No Leads
@endsection

@section('content')
    <div class="c-toolbar u-mb-medium">
        <button class="c-sidebar-toggle u-mr-small">
            <span class="c-sidebar-toggle__bar"></span>
            <span class="c-sidebar-toggle__bar"></span>
            <span class="c-sidebar-toggle__bar"></span>
        </button>

        <h3 class="c-toolbar__title">{{ $campaign->name }}</h3>
    </div>

    <div class="container">
        <div class="o-page__card u-mt-large">
            <div class="c-card u-mb-xsmall">
                <header class="c-card__header u-pt-medium">
                    <h1 class="u-h3 u-text-center u-mb-zero">
                        No available leads
                    </h1>
                </header>

                <div class="c-card__body">
                    <p>
                        Sorry, there are currently no leads available for this campaign.
                    </p>
                </div>
            </div>

            <div class="o-line">
                <a class="u-text-mute u-text-small" href="{{ route('dashboard') }}" title="Login">
                    <i class="fa fa-long-arrow-left u-mr-xsmall"></i> Back to Dashboard
                </a>
            </div>

        </div>
    </div>
@endsection
