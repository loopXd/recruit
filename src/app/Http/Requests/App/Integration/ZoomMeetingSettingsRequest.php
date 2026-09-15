<?php


namespace App\Http\Requests\App\Integration;


class ZoomMeetingSettingsRequest
{
    public function rules()
    {
        return [
            'api_secret' => 'required',
            'api_key' => 'required',
        ];
    }
}