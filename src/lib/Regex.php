<?php


namespace App\lib;


class Regex extends Helpers
{
    protected static string $numbers =  "/[^0-9]/";
    protected static string $digits = "/^\d+$/";
    protected static string $_toJson = '/\\\\u(\w{4})/';
    protected static string $equalCompare = "/d*s*=s*d*/";
    /**
     * @param string $str
     * @param string $to
     * @return string
     */
    public static function replaceOnlyNumbersTo(string $str,string $to = ''): string
    {
        self::$_toJson;
        return parent::tryCatch(fn()=>preg_replace(self::$numbers, $to, $str),'-1');
    }
}