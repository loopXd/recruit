@extends('layouts.app')

@section('title', trans('default.profile', [], app_get_locale()))

@section('contents')
    <my-profile></my-profile>
@endsection
