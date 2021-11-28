<?php

use App\infra\lib\Helpers;

function environments(string $key, $default = '')
{
    $getValueByKeyCallback = static function () use ($default, $key) {
        if (getenv($key)) {
            return getenv($key);
        }
        ['user' => $constants] = get_defined_constants(true);
        if (array_key_exists($key, $constants) && $constanteValue = $constants[$key]) {
            return $constanteValue;
        }
        return $default;
    };
    return Helpers::tryCatch($getValueByKeyCallback, $default);
}