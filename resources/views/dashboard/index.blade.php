@extends('layouts.master')

@section('page-title')
    Dashboard
@endsection

@section('content')
    <div class="u-flex" style="height:100vh; flex-direction: column;">
        <div class="c-toolbar u-mb-medium">
            <button class="c-sidebar-toggle u-mr-small">
                <span class="c-sidebar-toggle__bar"></span>
                <span class="c-sidebar-toggle__bar"></span>
                <span class="c-sidebar-toggle__bar"></span>
            </button>

            <h3 class="c-toolbar__title">Dashboard</h3>
        </div>

        <div class="u-flex u-justify-center"
             style="align-items: center; flex-direction: column; flex-grow: 1;"
        >

            <img src="/img/logo-login.svg">

            <h4 class="u-pt-medium">
                Dashboard coming soon!
            </h4>
        </div>
    </div>
@endsection
