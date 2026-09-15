@extends('auth-layouts.auth')

@section('title', trans('default.user_registration', [], app_get_locale()))

@section('contents')
    <div id="app">
        <sign-up
                recaptcha-enable = '{{$recaptcha_enable}}'
                :config-data="{{ json_encode(config('settings.application'))}}">
        </sign-up>
    </div>
@endsection
