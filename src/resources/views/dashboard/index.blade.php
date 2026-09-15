@extends('layouts.app')

@section('title', trans('default.dashboard_title', [], app_get_locale()))

@section('contents')
    @php
        $workArrangements =  array_values(array_map(function ($format) {
                return ['id' => $format, 'value' => __t($format)];
            }, config('jobpoint.work_arrangements')));
    @endphp
    <dashboard
            :is-candidate="{{ auth()->user()->hasRole('Candidate') ? 'true' : 'false' }}"
            :work-arrangements="{{ json_encode($workArrangements) }}"
    ></dashboard>
@endsection
