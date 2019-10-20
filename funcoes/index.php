<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
/**
 * Map From
 * array Map like a javascript
 * @param $array
 * @param $callback
 * @return array
 */
function Map($array, $callback)
{
    if (!function_exists('is_iterable')) {

        function is_iterable($obj)
        {
            return is_array($obj) || (is_object($obj) && ($obj instanceof Traversable));
        }

    }
    if (!is_callable($callback) || !is_iterable($array)) {
        throw new InvalidArgumentException('$callback must be a callable (function)');
    }
    $returned = array();
    foreach ($array as $key => $value) {
        $returned[] = $callback($value, $key);
    }
    return $returned;
}

;

/**
 * array Reducer like a javascript
 * @param $array
 * @param $callback
 * @param int $initialValue
 * @return int
 */
function Reducer($array, $callback, $initialValue = 0)
{
    if (!function_exists('is_iterable')) {

        function is_iterable($isIt)
        {
            return is_array($isIt) || (is_object($isIt) && ($isIt instanceof Traversable));
        }

    }
    if (!is_callable($callback) || !is_iterable($array)) {
        throw new InvalidArgumentException('$callback must be a callable (function)');
    }
    foreach ($array as $key => $value) {
        $initialValue = $callback($initialValue, $value, $key);
    }
    return $initialValue;
}


/**
 * entriesFrom
 * @param $anyIterable
 * @return array
 * @throws InvalidArgumentException
 */
function entries($anyIterable)
{
    if (!function_exists('is_iterable')) {

        function is_iterable($obj)
        {
            return is_array($obj) || (is_object($obj) && ($obj instanceof Traversable));
        }

    }
    if (!is_iterable($anyIterable)) {
        throw new InvalidArgumentException("");
    }
    $entries = array();
    foreach ($anyIterable as $key => $value) {
        $entries[] = array($key, $value);
    }
    return $entries;
}

;

// Your code here!
/**
 *  isSQLInjection check if contain sql injection on string param $value and return true or false
 * @param $value
 * @param string $type
 * @param bool $options
 * @return bool|mixed
 */
function isSQLInjection($value, $type = 'string', $options = false)
{
    $filters = array(
        'bool' => array(FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
        'email' => array(FILTER_VALIDATE_EMAIL, FILTER_FLAG_EMAIL_UNICODE),
        'float' => array(FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND),
        'int' => array(FILTER_VALIDATE_INT, array(FILTER_FLAG_ALLOW_OCTAL, FILTER_FLAG_ALLOW_HEX)),
        'ip' => array(FILTER_VALIDATE_IP, array(FILTER_FLAG_IPV4, FILTER_FLAG_IPV6, FILTER_FLAG_NO_PRIV_RANGE, FILTER_FLAG_NO_RES_RANGE)),
        'domain' => array(FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME),
        'url' => array(FILTER_VALIDATE_URL, array(FILTER_FLAG_PATH_REQUIRED, FILTER_FLAG_QUERY_REQUIRED)),
        'string' => array(
            FILTER_SANITIZE_STRING, array(
                FILTER_FLAG_STRIP_LOW, FILTER_FLAG_NO_ENCODE_QUOTES, FILTER_FLAG_STRIP_HIGH, FILTER_FLAG_ENCODE_LOW, FILTER_FLAG_ENCODE_HIGH, FILTER_FLAG_ENCODE_AMP)
        )
    );
    $validate = $filters[$type][0];
    $flag = $options ? $options : $filters[$type][1][0];
    $checked = filter_var($value, $validate, $flag);
    if (strlen($value) !== strlen($checked)) {
        return true;
    }
    $checked = strip_tags($value);
    if (strlen($value) !== strlen($checked)) {
        return true;
    }
    /**
     * @Description  $blackList  used to check if match more that 1 times
     * @example "ALTER FIELDS" or "DROP TABLE" and other combinations
     */
    $blackList = array(
        "ALTER", "ANALYZE", "CREATE",
        "DELETE", "DESCRIBE", "DROP", "EXISTS",
        "FIELDS", "FLOAT", "GRANT", "INSERT",
        "KILL", "PRIVILEGES", "PROCEDURE", "PURGE",
        "REPLACE", "SELECT", "SET", "SHOW",
        "TABLE", "TABLES", "TRUE", "UPDATE",
        "VALUES", "XOR"
    );
    $flag = array();
    foreach ($blackList as $blackWord) {
        if ((bool)(strpos(strtoupper($value), strtoupper($blackWord)) !== false)) {
            $flag[] = true;
        }
    }
    if (count($flag) > 1 || preg_match("/d*s*=s*d*/", $value)) {
        return true;
    }
    return false;
}
$toCheck = array(
    'localpart.ending.with.dot.@example.com',
    '(comment)localpart@example.com',
    '"this is v@lid!"',
    'h4rd1i@gmail.com',
    '"much.more unusual"@example.com',
    'postbox@com',
    'admin@mailserver1',
    '"()<>[]:,;@\"\!#$%&*+-/=?^_`{}| ~.a"@example.org',
    '" "@example.org ',
    '1=1',
    "'1=1' or true"
);
foreach ($toCheck as $value) {
    echo "<h1>" . ((bool)(isSQLInjection($value)) ? 'true<h1/>' : 'false<h1/>');
    echo "<h3>" . $value . "<br/>" . filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW) . "<h3/>";
}
exit;
