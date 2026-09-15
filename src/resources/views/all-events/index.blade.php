@extends('layouts.app')

@section('title', trans('default.all_events', [], app_get_locale()))

@section('contents')
    <all-events></all-events>
@endsection

