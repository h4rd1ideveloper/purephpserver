<?php

namespace App\assets\lib;

use Closure;
use InvalidArgumentException;

/**
 * Class Helpers
 * @author Yan Santos Policarpo
 * @version 1.1.0
 * @todo  Doc every methods and test
 */
class Helpers
{

    public static function baseURL(string $to = '')
    {
        $host = $_SERVER['HTTP_HOST'];
        $redirectUrl = explode('/', str_replace('index', '', $_SERVER['REDIRECT_URL']));
        return sprintf('//%s%s/%s/%s', $host, $redirectUrl[0], $redirectUrl[1], $to);
    }

    /**
     * @param $str
     * @return string|string[]|null
     */
    public static function soNumero($str)
    {
        if (!self::stringIsOk($str)) {
            throw new InvalidArgumentException('Parameter must be a valid string');
        }
        return preg_replace("/[^0-9]/", "", $str);

    }

    /**
     * @param $string
     * @return bool
     */
    public static function stringIsOk($string)
    {
        return is_string($string) && $string !== null && !empty($string) && isset($string) && $string !== '';
    }

    /**
     * @param $string string
     * @return false|int
     */
    public static function isMySQLFunction($string)
    {
        if (empty($string)) {
            throw new InvalidArgumentException('Parameter $string must be a valid string not and empty');
        }
        return preg_match_all('/\(|\)/', (string)$string);
    }

    /**
     * @param string $test
     * @param bool $int
     * @param bool|int|string $default
     * @return string
     */
    public static function orEmpty($test, $int = false, $default = false)
    {
        if ($default === false) {
            $default = $int ? 0 : '';
        }
        return self::stringIsOk((string)$test) ? $test : $default;
    }

    /**
     * Helpers constructor.
     */
    public static function showErrors()
    {
        error_reporting(E_ALL | E_STRICT);
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        //ini_set('max_execution_time', '600000');
    }

    /**
     * Define the constants that app will use
     */
    public static function defines()
    {
        define('PRODUCTION_DB_NAME', getenv('PRODUCTION_DB_NAME'));
        define('PRODUCTION_DB_USER', getenv('PRODUCTION_DB_USER'));
        define('PRODUCTION_DB_PASS', getenv('PRODUCTION_DB_PASS'));
        define('PRODUCTION_DB_TYPE', getenv('PRODUCTION_DB_TYPE'));
        define('PRODUCTION_DB_HOST', getenv('PRODUCTION_DB_HOST'));
        define('ENEL_FIELDS', getenv('ENEL_FIELDS'));
        define('ENEL_TABLE', getenv('ENEL_TABLE'));
        //Dev defines
    }

    /**
     * Format any Object or Array to JSON string
     * @param $toJson
     * @return string
     */
    public static function toJson($toJson)
    {
        /**
         * Because old version of the php dont contain  JSON_UNESCAPED_UNICODE const = (int 256)
         * @see json_encode()
         * @see JSON_UNESCAPED_UNICODE
         * @deprecated old pattern  ///(?<!\\\\)\\\\u(\w{4})/
         */
        return preg_replace_callback('/\\\\u(\w{4})/', function ($matches) {
            return html_entity_decode('&#x' . $matches[1] . ';', ENT_COMPAT, 'UTF-8');
        }, json_encode($toJson));
    }

    /**
     * Get Keys of the array
     * @param array $OBJ
     * @param bool $noReapt
     * @return array
     */
    public static function objectKeys($OBJ, $noReapt = true)
    {
        $arr = array();
        foreach ($OBJ as $key => $valueNotUsedHer) {
            if ($noReapt) {
                self::insertIfNotExist((string)$key, $arr);
                continue;
            }
            $arr[] = $key;
        }
        return $arr;
    }

    /**
     * Insert a value if not exist in array only unique values is accept
     * @param $value mixed
     * @param $arr
     */
    public static function insertIfNotExist($value, &$arr)
    {
        if (!in_array($value, $arr)) {
            $arr[] = $value;
        }
    }

    /**
     * Get Values of the array
     * @param array $OBJ
     * @return array
     */
    public static function objectValues($OBJ)
    {
        $arr = array();
        foreach ($OBJ as $keyNotUsedHer => $value) {
            self::insertIfNotExist($value, $arr);
        }
        return $arr;
    }

    /**
     * Filter array by ID
     * @param array $ids
     * @param $arr
     * @return array
     */
    public static function getRowsById($ids, $arr)
    {
        $source = array();
        foreach ($ids as $id) {
            isset($arr[$id]) && $source[$id] = $arr[$id];
        }
        return $source;
    }

    /**
     * Init Headers
     */
    public static function cors()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
        header("Accept: application/json, application/x-www-form-urlencoded, multipart/form-data, application/xhtml+xml, application/xml;q=0.9, multipart/*, text/plain, text/html,  image/webp, */*;q=0.8");
        header("Accept-Encoding: compress, gzip"); //

    }

    /**
     * @param string|null $headerContent
     */
    public static function setHeader($headerContent = 'Content-Type: application/json')
    {
        header(sprintf("%s", $headerContent));
    }

    /**
     * @param $data
     * @return array
     */
    public static function jsonToArray($data)
    {
        return json_decode($data, true);
    }

    /**
     * Map From
     * array Map like a javascript
     * @param $array
     * @param $callback
     * @return array
     */
    public static function Map($array, $callback)
    {
        if (!is_callable($callback) || !is_array($array)) {
            throw new InvalidArgumentException('$callback must be a callable (function)');
        }
        $returned = array();
        foreach ($array as $key => $value) {
            $returned[$key] = $callback($value, $key);
        }
        return $returned;
    }

    /**
     * entriesFrom
     * @param $anyIterable
     * @return array
     * @throws InvalidArgumentException
     */
    public static function Entries($anyIterable)
    {
        if (!is_array($anyIterable)) {
            throw new InvalidArgumentException("");
        }
        $entries = array();
        foreach ($anyIterable as $key => $value) {
            $entries[] = array($key, $value);
        }
        return $entries;
    }

    /**
     *  isSQLInjection check if contain sql injection on string param $value and return true or false
     * @param $value
     * @param string $type
     * @param bool $options
     * @return bool|mixed
     */
    public static function isSQLInjection($value, $type = 'string', $options = false)
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
                    FILTER_FLAG_STRIP_LOW, FILTER_FLAG_NO_ENCODE_QUOTES, FILTER_FLAG_STRIP_HIGH, FILTER_FLAG_ENCODE_LOW, FILTER_FLAG_ENCODE_HIGH, FILTER_FLAG_ENCODE_AMP
                )
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
            "VALUES", "XOR", "DATABASE"
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

    /**
     * array Reducer like a javascript
     * @param $array
     * @param $callback
     * @param array $initialValue
     * @return int
     */
    public static function Reducer($array, $callback, $initialValue = array())
    {
        if (!is_callable($callback) || !is_array($array)) {
            throw new InvalidArgumentException('$callback must be a callable (function)');
        }
        foreach ($array as $key => $value) {
            $initialValue = $callback($initialValue, $value, $key);
        }
        return $initialValue;
    }

    /**
     * @param $strDate
     * @return false|string
     */
    public static function ymdToDmy($strDate)
    {
        if (!self::stringIsOk($strDate)) {
            throw new InvalidArgumentException('Parameter must be a valid string');
        }
        if (
            !self::isOnlyNumbers(substr($strDate, 0, 4)) ||
            !self::isOnlyNumbers(substr($strDate, 5, 2)) ||
            !self::isOnlyNumbers(substr($strDate, 8, 2))
        ) {
            throw new InvalidArgumentException('Parameter must be a valid datetime with format y-m-d');
        }
        return date('d/m/Y', strtotime('2019-12-01'));
    }

    /**
     * @param $number
     * @return bool
     */
    public static function isOnlyNumbers($number)
    {
        return preg_match("/^\d+$/", $number) ? true : false;
    }

    /**
     * @param array $array
     * @param Closure $closure
     * @param Closure $callback
     * @return array
     */
    public static function MagicMap(array $array, Closure $closure, Closure $callback)
    {
        if (!is_callable($callback) || !is_callable($closure) || !is_array($array)) {
            throw new InvalidArgumentException('$callback must be a callable (function)');
        }
        $returned = array();
        foreach ($array as $key => $value) {
            $returned[$closure($value, $key)] = $callback($value, $key);
        }
        return $returned;
    }
}
