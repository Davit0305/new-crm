<?php

namespace App\Services\MiniServiceBioSmartHttp;


class CSettings
{
    use TDBitem;

    const table = 'settings';

    public static function getValue($settingCode)
    {
        return self::get(['value'], ['code' => $settingCode], false, [0, 1])[0]->value;
    }
}
