@extends('auth-layouts.auth')

@section('title', trans('default.forget_password', [], app_get_locale()))

@section('contents')
    <div id="app">
        <password-reset
                user-id="{{ auth()->id() }}"
                :config-data="{{ json_encode(config('settings.application')) }}">
        </password-reset>
    </div>
@endsection
