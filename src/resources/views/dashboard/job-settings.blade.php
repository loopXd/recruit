@extends('layouts.app')

@section('title', trans('default.job_settings', [], app_get_locale()))

@section('contents')
    <job-settings :job-post-id="{{$job_post_id}}"></job-settings>
@endsection
