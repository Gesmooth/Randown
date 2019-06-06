<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Tools;

class CountNewlines
{
    public static function call(String $string): Int{
        return preg_match_all("@\r\n|\n|\r|\f@", $string);
    }
}
