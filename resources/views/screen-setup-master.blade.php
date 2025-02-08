<!DOCTYPE html>
<html class="no-js" lang="{{ app()->getLocale() }}">
<head>

    @include('partials.styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
	
	<div id="app" class="wrapper">
        @yield('content')
    </div>
    @include('partials.script-tags')
</body>
</html>