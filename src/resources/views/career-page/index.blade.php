@extends('layouts.app')

@section('title', trans('default.career_page', [], app_get_locale()))

@section('contents')

    <career-page
            :career-page="{{json_encode($careerPage)}}"
            :job-posts="{{json_encode($jobPosts)}}">
    </career-page>
@endsection

