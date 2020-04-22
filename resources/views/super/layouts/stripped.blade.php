<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('super.layouts.head')
    </head>

    <body class="hold-transition {{ $page }}">
        @include('super.partials.messages')
        @yield('content')
        @stack('scripts')
        <script type="text/javascript" src="{!! asset('assets/js/super_core_libs.js') !!}"></script>
        <script type="text/javascript" src="{!! asset('assets/js/super_main.js') !!}"></script>
    </body>
</html>
