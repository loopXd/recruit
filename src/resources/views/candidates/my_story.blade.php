@extends('layouts.app')

@section('title', trans('default.my_story', [], app_get_locale()))

@section('contents')
    <my-story
            :user="{{auth()->user()}}"
            :is-candidate="{{ auth()->user()->hasRole('Candidate') ? 'true' : 'false' }}"
    ></my-story>
@endsection

