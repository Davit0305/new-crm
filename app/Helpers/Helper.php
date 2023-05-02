<?php

namespace App\Helpers;

class Helper
{
    public static function shout(string $string): string
    {
        return strtoupper($string);
    }
}
