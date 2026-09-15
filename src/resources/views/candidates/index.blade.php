@extends('layouts.app')

@section('title', trans('default.candidates', [], app_get_locale()))

@section('contents')
    <candidates
            :user="{{auth()->user()}}"
            :is-candidate="{{ auth()->user()->hasRole('Candidate') ? 'true' : 'false' }}"
    ></candidates>
@endsection

