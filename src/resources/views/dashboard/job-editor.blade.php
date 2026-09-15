@extends('layouts.app')

@section('title', trans('default.dashboard_title', [], app_get_locale()))

@section('contents')
    <job-editor :job="{{json_encode($job)}}" :preview-link="{{json_encode($previewLink)}}"></job-editor>
@endsection

