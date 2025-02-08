<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        @include('partials.styles')

        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body>
        <div id="app" class="wrapper">
            <Loader></Loader>
            <Notification></Notification>
            @include('partials.header')
            @include('partials.sidebar')

            
            @yield('content')

            @include('partials.footer')

        </div>

        @include('partials.script-tags')
    </body>
</html>
