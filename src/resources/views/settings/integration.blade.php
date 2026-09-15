@extends('layouts.app')

@section('title', trans('default.integrations', [], app_get_locale()))

@section('contents')
    <integrations></integrations>
@endsection

