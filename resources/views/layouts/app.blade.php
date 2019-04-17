<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('app-page-title') | {{ config('app.name') }}</title>
    <meta name=“robots” content=“none”>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="apple-touch-icon" href="{{ mix('apple-touch-icon.png') }}">
    <link rel="shortcut icon" href="{{ mix('favicon.ico') }}" type="image/x-icon">

    <link rel="stylesheet" href="https://use.typekit.net/mom8pyd.css">
    <link rel="stylesheet" href="{{ mix('css/main.css') }}">

    @yield('styles')
</head>

<body class="@if(request()->routeIs('login')) o-page o-page--center @endif">
    <div id="app">
        @yield('app')
    </div>

    <script src="{{ mix('js/main.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>

    @yield('scripts')
</body>
</html>
