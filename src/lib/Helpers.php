<?php

namespace App\lib;

use App\controllers\handlers\ErrorHandler;
use App\lib\Logger as MyLogger;
use Exception;
use Firebase\JWT\JWT as JWT;
use Illuminate\Database\Capsule\Manager as Capsule;
use InvalidArgumentException;
use Psr\Http\Message\StreamInterface;
use Slim\Psr7\Factory\StreamFactory;

/**
 * Class Helpers
 * @author Yan Santos Policarpo
 * @version 1.1.0
 * @todo  Doc every methods and test
 */
class Helpers extends Regex
{
    /**
     *
     */
    const filters = [
        'bool' => [FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE],
        'email' => [FILTER_VALIDATE_EMAIL, FILTER_FLAG_EMAIL_UNICODE],
        'float' => [FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND],
        'int' => [FILTER_VALIDATE_INT, [FILTER_FLAG_ALLOW_OCTAL, FILTER_FLAG_ALLOW_HEX]],
        'ip' => [FILTER_VALIDATE_IP, [FILTER_FLAG_IPV4, FILTER_FLAG_IPV6, FILTER_FLAG_NO_PRIV_RANGE, FILTER_FLAG_NO_RES_RANGE]],
        'domain' => [FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME],
        'url' => [FILTER_VALIDATE_URL, [FILTER_FLAG_PATH_REQUIRED, FILTER_FLAG_QUERY_REQUIRED]],
        'string' => [
            FILTER_SANITIZE_STRING, [
                FILTER_FLAG_STRIP_LOW, FILTER_FLAG_NO_ENCODE_QUOTES, FILTER_FLAG_STRIP_HIGH, FILTER_FLAG_ENCODE_LOW, FILTER_FLAG_ENCODE_HIGH, FILTER_FLAG_ENCODE_AMP
            ]
        ]
    ];
    /**
     * @Description  $blackList  used to check if match more that 1 times
     * @example "ALTER FIELDS" or "DROP TABLE" and other combinations
     */
    const blackList = [
        "ALTER", "ANALYZE", "CREATE",
        "DELETE", "DESCRIBE", "DROP", "EXISTS",
        "FIELDS", "FLOAT", "GRANT", "INSERT",
        "KILL", "PRIVILEGES", "PROCEDURE", "PURGE",
        "REPLACE", "SELECT", "SET", "SHOW",
        "TABLE", "TABLES", "TRUE", "UPDATE",
        "VALUES", "XOR", "DATABASE"
    ];
    /**
     *
     */
    const connectionConfig = [
        'driver' => 'mysql',
        'host' => 'localhost',
        'port' => '3306',
        'database' => 'webapp',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
    ];

    /**
     * @param callable $map
     * @param int $length
     * @param array|null $ref
     * @return void
     */
    public static function forMany(callable $map, int $length = 0, ?array &$ref = null): void
    {
        for ($i = 0; $i < $length; $i++) {
            if ($ref) {
                $ref[] = $map();
            } else {
                $map();
            }
        }
    }

    /**
     * @param array $connectionConfig
     * @param string $connectionName
     * @return Capsule|bool
     */
    public static function setupIlluminateConnectionAsGlobal(array $connectionConfig = self::connectionConfig, string $connectionName = 'default')
    {
        return self::tryCatch(fn() => Factory::illuminateDatabase($connectionConfig));
    }

    /**
     * @param callable $callback
     * @param array|string|int|bool|callable $defaultValue
     * @return mixed
     */
    public static function tryCatch(callable $callback, $defaultValue = false)
    {
        try {
            return $callback();
        } catch (Exception $exception) {
            MyLogger::errorLog(self::exceptionErrorMessage($exception), 'tryCatch');
            return is_callable($defaultValue) ? $defaultValue($exception) : $defaultValue;
        }
    }

    /**
     * @param Exception $exception
     * @param $payload
     * @return string
     */
    public static function exceptionErrorMessage(Exception $exception, $payload = null): string
    {
        $json = (bool)(isset($payload) && self::mayUseJsonEncode($payload)) ? "payload: " . self::toJson($payload) : '';
        return <<<ERROR
            message: {$exception->getMessage()}
            trace: {$exception->getTraceAsString()}
            $json
ERROR;
    }

    /**
     * @param $value
     * @return bool
     */
    public static function mayUseJsonEncode($value): bool
    {
        return (bool)(is_string($value) || is_array($value) || is_int($value) || is_bool($value));
    }

    /**
     * Format any Object or Array to JSON string
     * @param string|array|bool|int $toJson
     * @return string
     * @see json_encode()
     * @see JSON_UNESCAPED_UNICODE
     *
     */
    public static function toJson($toJson): string
    {
        return class_exists('JWT') ?
            JWT::jsonEncode($toJson) : (
            defined('JSON_UNESCAPED_UNICODE') ?
                json_encode($toJson, JSON_UNESCAPED_UNICODE) : self::_toJson($toJson)
            );
    }

    /**
     * Because old version of the php dont contain  JSON_UNESCAPED_UNICODE const = (int 256)
     * @deprecated old pattern  ///(?<!\\\\)\\\\u(\w{4})/
     * @param $toJson
     * @return string|string[]|null
     */
    public static function _toJson($toJson)
    {
        return preg_replace_callback(
            parent::$_toJson,
            fn(array $matches) => html_entity_decode(
                "&#x{$matches[1]};",
                ENT_COMPAT,
                'UTF-8'
            ),
            json_encode($toJson)
        );
    }

    /**
     * @param array $payload
     * @return string
     * @throws Exception
     */
    public static function XfsToken(array $payload = []): string
    {
        if (getenv('key') === '' || !self::orEmpty(getenv('key')))
            return Token::encode($payload, getenv('key') ?? '');
        throw new Exception('Invalid XfsToken', 403);
    }

    /**
     * @param null|string $test
     * @param mixed $default
     * @return mixed
     */
    public static function orEmpty(?string $test, $default = 0): ?string
    {
        return self::isOk($test) ? $test : $default;
    }

    /**
     * @param string|int|double $string
     * @return bool
     */
    public static function isOk($string): bool
    {
        return isset($string) && !empty($string) && (is_string($string) || is_int($string) || is_double($string));
    }

    /**
     * @param string|null $type
     * @param array $array
     * @return bool
     */
    public static function isArrayOf(?string $type, array $array): bool
    {
        $ok = true;
        foreach ($array as $row) {
            switch ($type) {
                case 'string':
                {
                    $ok = is_string($row) ? $ok : false;
                    break;
                }
                default:
                {
                    $ok = is_array($row) ? $ok : false;
                    break;
                }
            }
        }
        return $ok;
    }

    /**
     * @param string $filename
     */
    public static function setEnvByFile(string $filename): void
    {
        try {
            $env = self::createStreamFromFile($filename)->getContents();
            foreach (explode(PHP_EOL, $env) as $row) {
                $keyAndValue = explode('=', trim($row));
                [$key, $value] = [$keyAndValue[0] ?? '', $keyAndValue[1] ?? ''];
                if ($key !== '' && $value !== '') {
                    [$key, $value] = [strtolower(trim($key)), strtolower(trim($value))];
                    putenv("$key=$value");
                }
            }
        } catch (Exception $e) {
            $message = self::exceptionErrorMessage($e);
            MyLogger::ErrorLog($message, 'setEnvByFile');
            die(ErrorHandler::missingEnvironments($message));
        }
    }

    /**
     * @param $filename
     * @param string $mode
     * @return StreamInterface
     */
    public static function createStreamFromFile($filename, $mode = 'r+'): StreamInterface
    {
        return (new StreamFactory)->createStreamFromFile($filename, $mode);
    }

    /**
     * @param string $to
     * @return string
     */
    public static function baseURL(string $to = ''): string
    {
        $host = $_SERVER['HTTP_HOST'];
        if (isset($_SERVER['REDIRECT_URL'])) {
            [$index, $path] = explode('/', str_replace('index', '', isset($_SERVER['REDIRECT_URL']) ? $_SERVER['REDIRECT_URL'] : ''));
            return sprintf('//%s%s/%s/%s', $host, $index, $path, $to);
        }
        return sprintf('//%s%s/%s', $host, getenv('path_root') ?? '', $to);
    }

    /**
     * @param $string string
     * @return false|int
     */
    public static function isMySQLFunction(string $string): bool
    {
        return (bool)preg_match_all('/\(.*\)/', (string)$string);
    }

    /**
     * Get Keys of the array
     * @param array $OBJ
     * @param bool $noRepeat
     * @return array
     */
    public static function objectKeys(array $OBJ, bool $noRepeat = true): array
    {
        $arr = [];
        foreach ($OBJ as $key => $valueNotUsedHer) {
            if ($noRepeat) {
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
    public static function insertIfNotExist($value, array &$arr): void
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
    public static function objectValues(array $OBJ): array
    {
        $arr = [];
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
    public static function getRowsByKeys(array $ids, array $arr): array
    {
        $source = [];
        foreach ($ids as $id) {
            if (isset($arr[$id])) $source[$id] = $arr[$id];
        }
        return $source;
    }

    /**
     * @param $data
     * @return array
     */
    public static function jsonToArray(string $data): array
    {
        return (array)(class_exists('JWT') ?
            JWT::jsonDecode($data) :
            json_decode($data, true) ?? []
        );
    }

    /**
     * Map From
     * array Map like a javascript
     * @param array $array
     * @param callable $callback
     * @return array
     */
    public static function Map(array $array, callable $callback): array
    {
        $returned = [];
        foreach ($array as $key => $value) {
            $returned[$key] = $callback($value, $key);
        }
        return $returned;
    }

    /**
     * @param array $array
     * @param callable $fn
     * @return array
     */
    public static function Filter(array $array, callable $fn): array
    {
        $data = [];
        foreach ($array as $key => $value) {
            if ((bool)$fn($value, $key) === true) {
                $data[$key] = $value;
            }
        }
        return $data;
    }

    /**
     * entriesFrom
     * @param $anyIterable
     * @return array
     * @throws InvalidArgumentException
     */
    public static function Entries(array $anyIterable): array
    {
        $entries = [];
        foreach ($anyIterable as $key => $value) {
            $entries[] = [$key, $value];
        }
        return $entries;
    }

    /**
     *  isSQLInjection check if contain sql injection on string param $value and return true or false
     * @param $value
     * @param string $type
     * @param bool $options
     * @return bool
     */
    public static function isSQLInjection(string $value, string $type = 'string', $options = false): bool
    {
        $validate = self::filters[$type][0];
        $flag = $options ? $options : self::filters[$type][1][0];
        $checked = filter_var($value, $validate, $flag);
        if (strlen($value) !== strlen($checked)) {
            return true;
        }
        $checked = strip_tags($value);
        if (strlen($value) !== strlen($checked)) {
            return true;
        }
        $flag = [];
        foreach (self::blackList as $blackWord) {
            if (self::containSubString($value, $blackWord)) {
                $flag[] = true;
            }
        }
        return (bool)(count($flag) > 1 || preg_match(parent::$equalCompare, $value));
    }

    /**
     * @param $target
     * @param $toSearch
     * @param int $offset
     * @return bool
     */
    public static function containSubString(string $target, string $toSearch, int $offset = 0): bool
    {
        return (bool)(strpos(strtoupper($target), strtoupper($toSearch), $offset) !== false);
    }

    /**
     * array Reducer like a javascript
     * @param $array
     * @param $callback
     * @param mixed $initialValue
     * @return mixed
     */
    public static function Reducer(array $array, callable $callback, $initialValue = []): array
    {
        foreach ($array as $key => $value) {
            $initialValue = $callback($initialValue, $value, $key);
        }
        return $initialValue;
    }

    /**
     * @param $strDate
     * @return false|string
     */
    public static function ymdToDmy(string $strDate)
    {
        self::orEmpty($strDate, '0000-00-00');
        if (
            !self::isOnlyNumbers(substr($strDate, 0, 4)) ||
            !self::isOnlyNumbers(substr($strDate, 5, 2)) ||
            !self::isOnlyNumbers(substr($strDate, 8, 2))
        ) {
            throw new InvalidArgumentException('Parameter must be a valid datetime with format y-m-d');
        }
        return date('d/m/Y', strtotime($strDate));
    }

    /**
     * @param $number
     * @return bool
     */
    public static function isOnlyNumbers(string $number): bool
    {
        return preg_match(parent::$digits, $number) ? true : false;
    }

    /**
     * @param array $array
     * @param callable $closureKey
     * @param callable|null $callbackValue
     * @return array
     */
    public static function valueAndKeyMap(array $array, callable $closureKey, ?callable $callbackValue = null): array
    {
        $returned = [];
        foreach ($array as $key => $value) {
            $returned[$closureKey($value, $key) ?? $key] = $callbackValue === null ? $value : $callbackValue($value, $key);
        }
        return $returned;
    }
}
