<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>404 Page Not Found</title>
    <meta name=“robots” content=“none”>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="apple-touch-icon" href="{{ mix('apple-touch-icon.png') }}">
    <link rel="shortcut icon" href="{{ mix('favicon.ico') }}" type="image/x-icon">

    <link rel="stylesheet" href="https://use.typekit.net/mom8pyd.css">
    <link rel="stylesheet" href="{{ mix('css/main.css') }}">

</head>

<body class="o-page o-page--center">
    <div id="app">
        <div class="o-page__card">
            <div class="c-card u-mb-small">
                <header class="c-card__header u-text-center u-pt-large">
                    <span class="c-card__icon">
                        <i class="fa fa-chain-broken"></i>
                    </span>
                    <h1 class="u-text-big u-mb-zero">
                        404 <em class="u-block u-text-mute u-text-large">Page Not Found</em>
                    </h1>
                </header>

                <div class="c-card__body">
                    <h2 class="u-h5 u-text-center u-mb-medium">
                        Sorry, this page doesn't exist.
                    </h2>
                </div>
            </div>

            <div class="o-line u-justify-center">
                <a class="u-text-mute" href="{{ route('dashboard') }}">
                    <i class="fa fa-long-arrow-left u-mr-xsmall"></i> Back to Dashboard
                </a>
            </div>

        </div>
    </div>

    <script src="{{ mix('js/main.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>

    @yield('scripts')
</body>
</html>