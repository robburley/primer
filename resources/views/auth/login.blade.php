@extends('layouts.app')

@section('app-page-title')
    Login
@endsection

@section('app')
    <div class="container-fluid ">
        <div class="o-page__card o-page__card--horizontal">
            <div class="c-card c-login-horizontal">
                <div class="c-login__content-wrapper">
                    <header class="c-login__header">
                        <a class="c-login__icon c-login__icon--rounded c-login__icon--left" href="/">
                            <img src="{{ asset('img/logo-login.svg') }}" alt="{{ config('app.name') }} Logo">
                        </a>

                        <h2 class="c-login__title u-mt-small">Sign In</h2>
                    </header>

                    <form class="c-login__content" method="POST" action="{{ url('login') }}">
                        @csrf

                        <div class="c-field u-mb-small{{ $errors->has('username') ? ' has-icon-right' : '' }}">
                            <label class="c-field__label" for="username">Username</label>
                            <input class="c-input{{ $errors->has('username') ? ' c-input__danger' : '' }}" type="text"
                                   id="username" name="username" value="{{ old('username') }}" required autofocus>

                            @if ($errors->has('username'))
                                <small class="c-field__message u-color-danger">
                                    <i class="fa fa-times-circle"></i> {{ $errors->first('username') }}
                                </small>
                            @endif
                        </div>

                        <div class="c-field u-mb-small{{ $errors->has('password') ? ' has-icon-right' : '' }}">
                            <label class="c-field__label" for="password">Password</label>
                            <input class="c-input{{ $errors->has('password') ? ' c-input__danger' : '' }}"
                                   type="password"
                                   id="password" name="password" required>

                            @if ($errors->has('password'))
                                <small class="c-field__message u-color-danger">
                                    <i class="fa fa-times-circle"></i> {{ $errors->first('password') }}
                                </small>
                            @endif
                        </div>

                        @if ($errors->has('active'))
                            <small class="c-field__message u-color-danger">
                                <i class="fa fa-times-circle"></i> {{ $errors->first('active') }}
                            </small>
                        @endif

                        <button class="c-btn c-btn--blue c-btn--fullwidth" type="submit">Sign In</button>
                    </form>
                </div>

                <div class="c-login__content-image">
                    <img src="{{ asset('img/login2.jpg') }}" alt="Welcome to {{ config('app.name') }}">

                    <h3>Welcome to {{ config('app.name') }}</h3>

                    <p class="u-text-large">
                        An application designed for businesses to enhance their customer engagement process
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
