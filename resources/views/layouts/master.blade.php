@extends('layouts.app')

@section('app-page-title')
    @yield('page-title')
@endsection

@section('app')
    @include('layouts.nav')

    <main class="o-page__content">
        @yield('content')
    </main>

    @include('vendor.flash.message')
@endsection

@section('scripts')
    <script>
        $('div.primer-notificiation').not('.alert-important').delay(3000).fadeOut(350)
    </script>
@endsection
