<html dir="{{ config('settings.application.layout') }}" lang="<?php  app()->getLocale(); ?>">

    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>
        <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
        <title>@yield('title') - {{ config('app.name') }}</title>

        <link rel="shortcut icon" href="{{ asset(config('settings.application.company_icon')) }}"/>
        <link rel="apple-touch-icon" href="{{ asset(config('settings.application.company_icon')) }}"/>
        <link rel="apple-touch-icon-precomposed" href="{{ asset(config('settings.application.company_icon')) }}"/>

        <!-- Sharable Content -->
        @yield('sharable-content')

        @include('layouts.client_includes.header')
    </head>
    <body class="w-100 h-100">
        <div id="app" class="w-100 h-100">
            @yield('contents')
        </div>
        @include('layouts.client_includes.footer')
    </body>
</html>
