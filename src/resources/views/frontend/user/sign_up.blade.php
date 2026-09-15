@extends('auth-layouts.auth')

@section('title', trans('default.sign_up', [], app_get_locale()))

@section('contents')
    <div id="app">
        <sign-up
                user-id="{{ auth()->id() }}"
                :config-data="{{ json_encode(config('settings.application')) }}">
        </sign-up>
    </div>
@endsection
