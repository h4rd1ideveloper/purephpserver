<?php

namespace App\infra\lib;

use App\controllers\handlers\ErrorHandler;
use App\infra\servicies\security\Logger;
use Exception;
use Firebase\JWT\JWT as JWT;
use Illuminate\Database\Capsule\Manager as Capsule;
use InvalidArgumentException;
use JsonException;
use Psr\Http\Message\StreamInterface;
use RuntimeException;
use Slim\Psr7\Factory\StreamFactory;

/**
 * Class Helpers
 * @author Yan Santos Policarpo
 * @version 1.1.0
 * @todo  Doc every methods and test
 */
class Helpers extends StringManipulation
{
    /**
     *
     */
    public const filters = [
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
    public const blackList = [
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
    public const connectionConfig = [
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
     * @return array|bool
     * @throws JsonException
     */
    public static function setupIlluminateConnectionAsGlobal(array $connectionConfig = self::connectionConfig, string $connectionName = 'default')
    {
        return self::tryCatch(fn() => Factory::illuminateDatabase($connectionConfig));
    }

    /**
     * @param callable $callback
     * @param array|string|int|bool|callable $defaultValue
     * @return mixed
     * @throws JsonException
     */
    public static function tryCatch(callable $callback, $defaultValue = false)
    {
        try {
            return $callback();
        } catch (Exception $exception) {
            Logger::errorLog(self::exceptionErrorMessage($exception), 'tryCatch');
            return is_callable($defaultValue) ? $defaultValue() : $defaultValue;
        }
    }

    /**
     * @param Exception $exception
     * @param $payload
     * @return string
     * @throws JsonException
     */
    public static function exceptionErrorMessage(Exception $exception, $payload = null): string
    {
        $json = isset($payload) && self::mayUseJsonEncode($payload) ? "payload: " . self::toJson($payload) : '';
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
     * @throws JsonException
     * @see JSON_UNESCAPED_UNICODE
     *
     * @see json_encode()
     */
    public static function toJson($toJson): string
    {
        if (class_exists('JWT')) {
            return JWT::jsonEncode($toJson);
        }
        return defined('JSON_UNESCAPED_UNICODE') ? json_encode($toJson, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE) : self::_toJson($toJson);
    }

    /**
     * Because old version of the php dont contain  JSON_UNESCAPED_UNICODE const = (int 256)
     * @deprecated old pattern  ///(?<!\\\\)\\\\u(\w{4})/
     * @param $toJson
     * @return string|string[]|null
     * @throws JsonException
     */
    public static function _toJson($toJson)
    {
        return preg_replace_callback(
            parent::$_toJson,
            static fn(array $matches) => html_entity_decode(
                "&#x{$matches[1]};",
                ENT_COMPAT,
                'UTF-8'
            ),
            json_encode($toJson, JSON_THROW_ON_ERROR)
        );
    }

    public static function XfsToken(array $payload = []): string
    {
        if (getenv('key') === '' || !self::orEmpty(getenv('key'))) {
            return Token::encode($payload, getenv('key') ?? '');
        }
        throw new RuntimeException('Invalid XfsToken', 403);
    }

    public static function orEmpty(?string $test, $default = 0): ?string
    {
        return self::isOk($test) ? $test : $default;
    }

    public static function isOk($string): bool
    {
        return isset($string) && !empty($string) && (is_string($string) || is_int($string) || is_double($string));
    }

    public static function isArrayOf(?string $type, array $array): bool
    {
        $ok = true;
        foreach ($array as $row) {
            $isValid = ($type === 'string') ? is_string($row) : is_array($row);
            $ok = $isValid ? $ok : false;
        }
        return $ok;
    }

    public static function setEnvByFile(string $filename): void
    {
        try {
            $env = self::createStreamFromFile($filename)->getContents();
            foreach (explode(PHP_EOL, $env) as $row) {
                $keyAndValue = explode('=', trim($row));
                [$key, $value] = [$keyAndValue[0] ?? '', $keyAndValue[1] ?? ''];
                if ($key !== '' && $value !== '' && (!getenv($key) || getenv($key) !== $value)) {
                    [$key, $value] = [strtolower(trim($key)), strtolower(trim($value))];
                    putenv("$key=$value");
                }
            }
        } catch (Exception $e) {
            $message = self::exceptionErrorMessage($e);
            Logger::ErrorLog($message, 'setEnvByFile');
            die(ErrorHandler::missingEnvironments($message));
        }
    }

    public static function createStreamFromFile($filename, string $mode = 'r+'): StreamInterface
    {
        return (new StreamFactory)->createStreamFromFile($filename, $mode);
    }

    public static function baseURL(string $to = ''): string
    {
        $host = $_SERVER['HTTP_HOST'];
        if (isset($_SERVER['REDIRECT_URL'])) {
            [$index, $path] = explode('/', str_replace('index', '', $_SERVER['REDIRECT_URL'] ?? ''));
            return sprintf('//%s%s/%s/%s', $host, $index, $path, $to);
        }
        return sprintf('//%s%s/%s', $host, getenv('path_root') ?? '', $to);
    }

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
     * @param array $arr
     * @param bool $strict
     */
    public static function insertIfNotExist($value, array &$arr, bool $strict = false): void
    {
        if (!in_array($value, $arr, $strict)) {
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


    public static function getRowsByKeys(array $ids, array $arr): array
    {
        $source = [];
        foreach ($ids as $id) {
            if (isset($arr[$id])) {
                $source[$id] = $arr[$id];
            }
        }
        return $source;
    }

    /**
     * @param string $data
     * @return array
     * @throws JsonException
     */
    public static function jsonToArray(string $data): array
    {
        return (array)(class_exists('JWT') ?
            JWT::jsonDecode($data) :
            json_decode($data, true, 512, JSON_THROW_ON_ERROR) ?? []
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
            if ($fn($value, $key) === true) {
                $data[$key] = $value;
            }
        }
        return $data;
    }

    /**
     * entriesFrom
     * @param array $anyIterable
     * @return array
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
     * @param string $value
     * @param string $type
     * @param  $options
     * @return bool
     */
    public static function isSQLInjection(string $value, string $type = 'string', $options = null): bool
    {
        $validate = self::filters[$type][0];
        $flag = $options ?: self::filters[$type][1][0];
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
        return count($flag) > 1 || preg_match(parent::$equalCompare, $value);
    }

    public static function containSubString(string $target, string $toSearch, int $offset = 0): bool
    {
        return strpos(strtoupper($target), strtoupper($toSearch), $offset) !== false;
    }

    /**
     * array Reducer like a javascript
     * @param array $array
     * @param callable $callback
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

    public static function isOnlyNumbers(string $number): bool
    {
        return (bool)preg_match(parent::$digits, $number);
    }

    public static function valueAndKeyMap(array $array, callable $closureKey, ?callable $callbackValue = null): array
    {
        $returned = [];
        foreach ($array as $key => $value) {
            $returned[$closureKey($value, $key) ?? $key] = $callbackValue === null ? $value : $callbackValue($value, $key);
        }
        return $returned;
    }
}
