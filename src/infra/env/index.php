<?php
function _env(string $key, $default = ''){
    return getenv($key) ?? $default;
}