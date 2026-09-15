<?php


use App\Helpers\App\General\SettingParser;

if (!function_exists('settings')) {

    function settings(string $key = null, $alternate = null) {
        return SettingParser::new(true)
            ->parse($key, $alternate);
    }

}
