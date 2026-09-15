<html lang="<?=app()->getLocale()?>">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    <link rel="shortcut icon" href="/images/icon.png" />
    <link rel="apple-touch-icon" href="/images/icon.png" />
    <link rel="apple-touch-icon-precomposed" href="/images/icon.png" />

    <title>{{ __t('install') }} - {{ config('app.name') }} - {{ request()->root() }} </title>

    @include('layouts.includes.header')
</head>

<body>
    <div id="app">
        <div class="container">
            @yield('contents')
        </div>
    </div>

    @include('layouts.includes.footer')
</body>

</html>