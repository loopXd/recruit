@extends('layouts.app')

@section('title', trans('default.candidates', [], app_get_locale()))

@section('contents')
    <div>
            <candidate-import></candidate-import>
    </div>
@endsection