<?php


namespace App\infra\lib;


use App\lib\Helpers;

class StringManipulation extends Helpers
{
    protected static string $numbers =  "/[^0-9]/";
    protected static string $digits = "/^\d+$/";
    protected static string $_toJson = '/\\\\u(\w{4})/';
    protected static string $equalCompare = "/d*s*=s*d*/";

    public static function replaceOnlyNumbersTo(string $str,string $to = ''): string
    {
        return parent::tryCatch(fn()=>preg_replace(self::$numbers, $to, $str),'-1');
    }
}