<html
        dir="{{ config('settings.application.layout') }}"
        lang="<?php
        app()->getLocale();
        ?>">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>@yield('title') - {{ config('app.name') }}</title>

    <link rel="shortcut icon" href="{{ url(config('settings.application.company_icon','public/image/icon.png')) }}"/>
    <link rel="apple-touch-icon" href="{{ url(config('settings.application.company_icon','public/image/icon.png')) }}"/>
    <link rel="apple-touch-icon-precomposed"
          href="{{ config('settings.application.company_icon','public/image/icon.png') }}"/>

    @include('layouts.includes.header')
</head>
<body>
<div id="app">
    <div class="container-scroller">
        <!--Top Navbar-->
    @section('nav-bar')
        @include('layouts.includes.navbar')
    @show

    <!--Sidebar-->
        @section('side-bar')
            @include('layouts.includes.sidebar')
        @show

        <div class="container-fluid page-body-wrapper">
            <div class="main-panel">
                <!--Contents-->
                @yield('contents')
            </div>
        </div>
        @if(env('IS_DEMO', false))
            <div class="" style="z-index:201; bottom:0; right:0; position:fixed; margin:50px;">
                <a href="https://codecanyon.net/item/jobpoint-recruitment-management-system/33173196" class="btn btn-warning rounded-pill shadow-lg">
                    <span class="mr-2"><app-icon name="shopping-cart"/></span>Buy now!
                </a>
            </div>
        @endif
    </div>
</div>

@include('layouts.includes.footer')
</body>
</html>
