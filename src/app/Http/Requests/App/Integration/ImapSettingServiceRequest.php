<?php


namespace App\Http\Requests\App\Integration;


class ImapSettingServiceRequest
{
    public function rules()
    {
        return [
            'api_secret' => 'required',
            'api_key' => 'required',
        ];
    }
}