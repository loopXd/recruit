@extends('layouts.app-candidate')

@section('title', trans('default.career_page', [], app_get_locale()))

@section('sharable-content')

    <meta property="og:url" content="{{request()->root().'career'}}">
    <meta property="og:title" content="{{config('app.name')}}">
    <meta property="og:description" content="{{config('app.name').' - '.__t('job_point_description')}}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{request()->root().config('settings.application.company_logo')}}">
    <meta property="og:image:width" content="1000">
    <meta property="og:image:height" content="500">
@endsection

@section('contents')

    <candidate-career-page
            :logged-in="{{ isset(auth()->user()->id) ? 'true' : 'false'}}"
            :career-page="{{json_encode($careerPage)}}"
            :is-candidate="{{ optional(auth()->user())->hasRole('Candidate') ? 'true' : 'false' }}"
    >
    </candidate-career-page>
@endsection

