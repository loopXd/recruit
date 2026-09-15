@extends('layouts.app')

@section('title', trans('default.job_settings', [], app_get_locale()))

@section('contents')
    <job-setting></job-setting>
@endsection

