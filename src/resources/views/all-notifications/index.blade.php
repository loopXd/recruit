@extends('layouts.app')

@section('title', trans('default.all_notifications', [], app_get_locale()))

@section('contents')
    <all-notification></all-notification>
@endsection

