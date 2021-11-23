<?php
function environments(string $key, $default = '')
{
    if (getenv($key)) {
        return getenv($key);
    }
    ['user' => $constants] = get_defined_constants(true);
    if (array_key_exists($key, $constants) && $constanteValue = $constants[$key]) {
        return $constanteValue;
    }
    return $default;
}