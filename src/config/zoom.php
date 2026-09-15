<?php

return [
    'api_key' => env('ZOOM_API_KEY'),
    'api_secret' => env('ZOOM_API_SECRET'),
    'base_url' => 'https://api.zoom.us/v2/',
    'token_life' => 60 * 60 * 24 * 7, // In seconds, default 1 week
    'authentication_method' => 'jwt', // Only jwt compatible at present but will add OAuth2
    'max_api_calls_per_request' => '5', // how many times can we hit the api to return results for an all() request
    'join_before_host' => true,
    'approval_type' => 1,
    'registration_type' => 2,
    'enforce_login' => false,
    'waiting_room' => false,
    'mute_upon_entry' => true,
    'host_video' => false,
    'participant_video' => false,
];
