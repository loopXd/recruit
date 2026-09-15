@extends('layouts.app')

@section('title', trans('default.user_and_roles', [], app_get_locale()))

@section('contents')
    <user-roles></user-roles>
@endsection
