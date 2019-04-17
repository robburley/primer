@extends('layouts.master')

@section('page-title')
    Reports
@endsection

@section('content')
    <div class="c-toolbar u-mb-medium">
        <button class="c-sidebar-toggle u-mr-small">
            <span class="c-sidebar-toggle__bar"></span>
            <span class="c-sidebar-toggle__bar"></span>
            <span class="c-sidebar-toggle__bar"></span>
        </button>

        <h3 class="c-toolbar__title">Reports</h3>
    </div>

    <div class="container u-mb-medium">
        <div class="row">
            <div class="col-sm-6 col-lg-3">
                <div class="c-project">
                    <h4 class="u-text-center">
                        Agents Report
                    </h4>

                    <div class="u-text-center">
                        <a class="c-btn c-btn--info"
                           aria-label="Run Report"
                           href="{{ route('supervisor.reports.agents.index') }}"
                        >
                            Run
                        </a>
                    </div>

                </div><!-- // .c-project -->
            </div>
        </div>
    </div>
@endsection
