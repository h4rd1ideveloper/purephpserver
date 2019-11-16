<?php


namespace Psr\Http\Message;

use Closure;
use Exception;
use InvalidArgumentException;
use Iterator;
use Lib\Helpers;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use RuntimeException;
use Server\Router;

/**
 * Class HttpHelper
 * @package Psr\Http\Message
 */
class HttpHelper extends Helpers
{

    /**
     * Create a new stream based on the input type.
     *
     * Options is an associative array that can contain the following keys:
     * - metadata: Array of custom metadata.
     * - size: Size of the stream.
     *
     * @param resource|string|null|int|float|bool|StreamInterface|callable|Iterator $resource Entity body data
     * @param array $options Additional options
     *
     * @return Iterator|PumpStream|Stream|StreamInterface|bool|callable|float|int|StreamInterface|resource|string|null
     * @throws InvalidArgumentException if the $resource arg is not valid.
     * @throws Exception
     */
    public static function stream_for($resource = '', $options = array())
    {
        if (is_scalar($resource)) {
            $stream = fopen('php://temp', 'r+');
            if ($resource !== '') {
                fwrite($stream, $resource);
                fseek($stream, 0);
            }
            return new Stream($stream, $options);
        }

        switch (gettype($resource)) {
            case 'resource':
                return new Stream($resource, $options);
            case 'object':
                if ($resource instanceof StreamInterface) {
                    return $resource;
                } elseif ($resource instanceof Iterator) {
                    return new PumpStream(function () use ($resource) {
                        if (!$resource->valid()) {
                            return false;
                        }
                        $result = $resource->current();
                        $resource->next();
                        return $result;
                    }, $options);
                } elseif (method_exists($resource, '__toString')) {
                    try {
                        return self::stream_for((string)$resource, $options);
                    } catch (Exception $e) {

                    }
                }
                break;
            case 'NULL':
                return new Stream(fopen('php://temp', 'r+'), $options);
            default:
                throw new Exception('Unexpected value');
        }
        if (is_callable($resource)) {
            return new PumpStream($resource, $options);
        }
        throw new InvalidArgumentException('Invalid resource type: ' . gettype($resource));
    }

    /**
     * @param int $status
     * @param array $headers
     * @param null $body
     * @param string $version
     * @param null $reason
     * @return Response
     * @throws Exception
     */
    public static function responseFactory($status = 200, $headers = array(), $body = null, $version = '1.1', $reason = null)
    {
        return new Response($status, $headers, $body, $version, $reason);
    }

    /**
     * @param $BASE_ULR
     * @param $REQUEST_URI
     * @param bool $flag
     * @return mixed
     */
    public static function requestUriString($BASE_ULR, $REQUEST_URI, $flag = false)
    {
        return $flag ? parse_url(str_replace($BASE_ULR, "", $REQUEST_URI), PHP_URL_PATH)
            : str_replace($BASE_ULR, "", $REQUEST_URI);
    }

    /**
     * @param $pathEnv
     * @return array|bool|string
     * @license MIT
     * @author Yan Santos Policarpo <policarpo@ice.ufjf.br>
     * @see Helpers::stringIsOk()
     * @see HttpHelper::createCachingStreamOfLazyOpenStream()
     * @see Helpers::MagicMap()
     */
    public static function getEnvFrom($pathEnv)
    {
        return self::stringIsOk($pathEnv) ? self::parseEnvFile($pathEnv) : false;
    }

    /**
     * @param $pathEnv
     * @return array|string
     */
    protected static function parseEnvFile($pathEnv)
    {

        try {
            $envAsString = Request::createCachingStreamOfLazyOpenStream($pathEnv, 'r+')->getContents();
            //exit(print_r(explode(PHP_EOL, trim($envAsString))));
            return self::MagicMap(
                explode(PHP_EOL, trim($envAsString)),
                //keys
                function ($value, $key) {
                    $entries_becauseIsOldVersionOfThePHP = explode('=', $value);
                    return trim($entries_becauseIsOldVersionOfThePHP[0]);
                },
                //Values
                function ($value, $key) {
                    $entries_becauseIsOldVersionOfThePHP = explode('=', $value);
                    return $entries_becauseIsOldVersionOfThePHP[1] === 'true' || $entries_becauseIsOldVersionOfThePHP[0] === 'false' ?
                        (bool)$entries_becauseIsOldVersionOfThePHP[1] :
                        trim($entries_becauseIsOldVersionOfThePHP[1]);
                }
            );
        } catch (Exception $e) {
            return $e->getMessage() . PHP_EOL . $e->getLine() . PHP_EOL . $e->getTraceAsString() . PHP_EOL;
        }
    }

    /**
     * @param $filename
     * @param $mode
     * @return CachingStream
     * @throws Exception
     */
    public static function createCachingStreamOfLazyOpenStream($filename, $mode)
    {
        return new CachingStream(new LazyOpenStream($filename, $mode));
    }

    /**
     * @param $method
     * @param $version
     * @return Request
     * @throws Exception
     */
    public static function requestFromGlobalsFactory($method, $version)
    {
        if (!is_string($method) || !is_string($version)) {
            throw new InvalidArgumentException("Parameters must be a string");
        }
        $request = new Request(
            $method,
            self::getUriFromGlobals(),
            self::getHeaderList(),
            new CachingStream(new LazyOpenStream('php://input', 'r+')),
            $version
        );
        return $request
            ->withCookieParams($_COOKIE)
            ->withQueryParams($_GET)
            ->withParsedBody($_POST)
            ->withUploadedFiles($request::normalizeFiles($_FILES));
    }

    /**
     * Get a Uri populated with values from $_SERVER.
     *
     * @return Uri|UriInterface
     */
    public static function getUriFromGlobals()
    {
        $uri = new Uri('');
        $uri = $uri->withScheme(self::httpsOrHttp());//!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http'
        $hasPort = false;
        if (isset($_SERVER['HTTP_HOST'])) {
            list($host, $port) = self::extractHostAndPortFromAuthority($_SERVER['HTTP_HOST']);
            if ($host !== null) {
                $uri = $uri->withHost($host);
            }

            if ($port !== null) {
                $hasPort = true;
                $uri = $uri->withPort($port);
            }
        } elseif (isset($_SERVER['SERVER_NAME'])) {
            $uri = $uri->withHost($_SERVER['SERVER_NAME']);
        } elseif (isset($_SERVER['SERVER_ADDR'])) {
            $uri = $uri->withHost($_SERVER['SERVER_ADDR']);
        }
        if (!$hasPort && isset($_SERVER['SERVER_PORT'])) {
            $uri = $uri->withPort($_SERVER['SERVER_PORT']);
        }
        $hasQuery = false;
        if (isset($_SERVER['REQUEST_URI'])) {
            $requestUriParts = explode('?', str_replace("", "", $_SERVER['REQUEST_URI']), 2);
            if (isset($requestUriParts[1])) {
                $hasQuery = true;
                $uri = $uri->withPath($requestUriParts[0])->withQuery($requestUriParts[1]);
            } else {
                $uri = $uri->withPath($requestUriParts[0])->withQuery('');
            }
        }
        if (!$hasQuery && isset($_SERVER['QUERY_STRING'])) {
            $uri = $uri->withQuery($_SERVER['QUERY_STRING']);
        }

        return $uri;
    }

    /**
     * @return string
     */
    protected static function httpsOrHttp()
    {
        return !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
    }

    /**
     * @param $authority
     * @return array
     */
    private static function extractHostAndPortFromAuthority($authority)
    {
        $uri = 'http://' . $authority;
        $parts = parse_url($uri);
        if (false === $parts) {
            return array(null, null);
        }

        $host = isset($parts['host']) ? $parts['host'] : null;
        $port = isset($parts['port']) ? $parts['port'] : null;

        return array($host, $port);
    }

    /**
     * getHeaderList
     * @return array
     */
    public static function getHeaderList()
    {
        if (function_exists('getallheaders')) {
            return getallheaders();
        }
        $headerList = array();
        foreach ($_SERVER as $name => $value) {
            if (preg_match('/^HTTP_/', $name)) {
                $name = strtr(substr($name, 5), '_', ' ');
                $name = ucwords(strtolower($name));
                $name = strtr($name, ' ', '-');
                $headerList[$name] = $value;
            }
        }
        return $headerList;
    }

    /**
     * Returns the string representation of an HTTP message.
     *
     * @param MessageInterface $message Message to convert to a string.
     *
     * @return string
     */
    public static function str(MessageInterface $message)
    {
        if ($message instanceof RequestInterface) {
            $msg = sprintf("%s HTTP/%s", trim(sprintf("%s %s", $message->getMethod(), $message->getRequestTarget())), $message->getProtocolVersion());
            if (!$message->hasHeader('host')) {
                $msg .= sprintf("\r\nHost: %s", $message->getUri()->getHost());
            }
        } elseif ($message instanceof ResponseInterface) {
            $msg = sprintf("HTTP/%s %s %s", $message->getProtocolVersion(), $message->getStatusCode(), $message->getReasonPhrase());
        } else {
            throw new InvalidArgumentException('Unknown message type');
        }
        foreach ($message->getHeaders() as $name => $values) {
            $msg .= sprintf("\r\n{$name}: %s", implode(', ', $values));
        }
        return sprintf("{$msg}\r\n\r\n%s", $message->getBody());
    }

    /**
     * Returns a UriInterface for the given value.
     *
     * This function accepts a string or {@see Psr\Http\Message\UriInterface} and
     * returns a UriInterface for the given value. If the value is already a
     * `UriInterface`, it is returned as-is.
     *
     * @param string|UriInterface $uri
     *
     * @return UriInterface
     * @throws InvalidArgumentException
     */
    public static function uri_for($uri)
    {
        if ($uri instanceof UriInterface) {
            return $uri;
        } elseif (is_string($uri)) {
            return new Uri($uri);
        }

        throw new InvalidArgumentException('URI must be a string or UriInterface');
    }

    /**
     * Parse an array of header values containing ";" separated data into an
     * array of associative arrays representing the header key value pair
     * data of the header. When a parameter does not contain a value, but just
     * contains a key, this function will inject a key with a '' string value.
     *
     * @param string|array $header Header to parse into components.
     *
     * @return array Returns the parsed header values.
     */
    public static function parse_header($header)
    {
        static $trimmed = "\"'  \n\t\r";
        $params = $matches = array();

        foreach (self::normalize_header($header) as $val) {
            $part = array();
            foreach (preg_split('/;(?=([^"]*"[^"]*")*[^"]*$)/', $val) as $kvp) {
                if (preg_match_all('/<[^>]+>|[^=]+/', $kvp, $matches)) {
                    $m = $matches[0];
                    if (isset($m[1])) {
                        $part[trim($m[0], $trimmed)] = trim($m[1], $trimmed);
                    } else {
                        $part[] = trim($m[0], $trimmed);
                    }
                }
            }
            if ($part) {
                $params[] = $part;
            }
        }

        return $params;
    }

    /**
     * Converts an array of header values that may contain comma separated
     * headers into an array of headers with no comma separated values.
     *
     * @param string|array $header Header to normalize.
     *
     * @return array Returns the normalized header field values.
     */
    public static function normalize_header($header)
    {
        if (!is_array($header)) {
            return array_map('trim', explode(',', $header));
        }

        $result = array();
        foreach ($header as $value) {
            foreach ((array)$value as $v) {
                if (strpos($v, ',') === false) {
                    $result[] = $v;
                    continue;
                }
                foreach (preg_split('/,(?=([^"]*"[^"]*")*[^"]*$)/', $v) as $vv) {
                    $result[] = trim($vv);
                }
            }
        }

        return $result;
    }

    /**
     * Clone and modify a request with the given changes.
     *
     * The changes can be one of:
     * - method: (string) Changes the HTTP method.
     * - set_headers: (array) Sets the given headers.
     * - remove_headers: (array) Remove the given headers.
     * - body: (mixed) Sets the given body.
     * - uri: (UriInterface) Set the URI.
     * - query: (string) Set the query string value of the URI.
     * - version: (string) Set the protocol version.
     *
     * @param RequestInterface $request Request to clone and modify.
     * @param array $changes Changes to apply.
     *
     * @return RequestInterface
     * @throws Exception
     */
    public static function modify_request(RequestInterface $request, array $changes)
    {
        if (!$changes) {
            return $request;
        }

        $headers = $request->getHeaders();

        if (!isset($changes['uri'])) {
            $uri = $request->getUri();
        } else {
            // Remove the host header if one is on the URI
            if ($host = $changes['uri']->getHost()) {
                $changes['set_headers']['Host'] = $host;

                if ($port = $changes['uri']->getPort()) {
                    $standardPorts = array('http' => 80, 'https' => 443);
                    $scheme = $changes['uri']->getScheme();
                    if (isset($standardPorts[$scheme]) && $port != $standardPorts[$scheme]) {
                        $changes['set_headers']['Host'] .= ':' . $port;
                    }
                }
            }
            $uri = $changes['uri'];
        }

        if (!empty($changes['remove_headers'])) {
            $headers = self::_caseless_remove($changes['remove_headers'], $headers);
        }

        if (!empty($changes['set_headers'])) {
            $headers = self::_caseless_remove(array_keys($changes['set_headers']), $headers);
            $headers = $changes['set_headers'] + $headers;
        }

        if (isset($changes['query'])) {
            $uri = $uri->withQuery($changes['query']);
        }

        if ($request instanceof ServerRequestInterface) {
            $serverR = new ServerRequest(
                isset($changes['method']) ? $changes['method'] : $request->getMethod(),
                $uri,
                $headers,
                isset($changes['body']) ? $changes['body'] : $request->getBody(),
                isset($changes['version'])
                    ? $changes['version']
                    : $request->getProtocolVersion(),
                $request->getServerParams()
            );
            return $serverR
                ->withParsedBody($request->getParsedBody())
                ->withQueryParams($request->getQueryParams())
                ->withCookieParams($request->getCookieParams())
                ->withUploadedFiles($request->getUploadedFiles());
        }

        return new Request(
            isset($changes['method']) ? $changes['method'] : $request->getMethod(),
            $uri,
            $headers,
            isset($changes['body']) ? $changes['body'] : $request->getBody(),
            isset($changes['version'])
                ? $changes['version']
                : $request->getProtocolVersion()
        );
    }

    /**
     * Insert if not exist by $Keys
     * @param $keys
     * @param array $data
     * @return array
     * @internal
     */
    public static function _caseless_remove($keys, array $data)
    {
        $result = array();

        foreach ($keys as &$key) {
            $key = strtolower($key);
        }

        foreach ($data as $k => $v) {
            if (!in_array(strtolower($k), $keys)) {
                $result[$k] = $v;
            }
        }

        return $result;
    }

    /**
     * Attempts to rewind a message body and throws an exception on failure.
     *
     * The body of the message will only be rewound if a call to `tell()` returns a
     * value other than `0`.
     *
     * @param MessageInterface $message Message to rewind
     *
     * @throws RuntimeException
     */
    public static function rewind_body(MessageInterface $message)
    {
        $body = $message->getBody();

        if ($body->tell()) {
            $body->rewind();
        }
    }

    /**
     * Copy the contents of a stream into a string until the given number of
     * bytes have been read.
     *
     * @param StreamInterface $stream Stream to read
     * @param int $maxLen Maximum number of bytes to read. Pass -1
     *                                to read the entire stream.
     * @return string
     * @throws RuntimeException on error.
     */
    public static function copy_to_string(StreamInterface $stream, $maxLen = -1)
    {
        $buffer = '';

        if ($maxLen === -1) {
            while (!$stream->eof()) {
                $buf = $stream->read(1048576);
                // Using a loose equality here to match on '' and false.
                if ($buf == null) {
                    break;
                }
                $buffer .= $buf;
            }
            return $buffer;
        }

        $len = 0;
        while (!$stream->eof() && $len < $maxLen) {
            $buf = $stream->read($maxLen - $len);
            // Using a loose equality here to match on '' and false.
            if ($buf == null) {
                break;
            }
            $buffer .= $buf;
            $len = strlen($buffer);
        }

        return $buffer;
    }

    /**
     * Copy the contents of a stream into another stream until the given number
     * of bytes have been read.
     *
     * @param StreamInterface $source Stream to read from
     * @param StreamInterface $dest Stream to write to
     * @param int $maxLen Maximum number of bytes to read. Pass -1
     *                                to read the entire stream.
     *
     * @throws RuntimeException on error.
     */
    public static function copy_to_stream(StreamInterface $source, StreamInterface $dest, $maxLen = -1)
    {
        $bufferSize = 8192;

        if ($maxLen === -1) {
            while (!$source->eof()) {
                if (!$dest->write($source->read($bufferSize))) {
                    break;
                }
            }
        } else {
            $remaining = $maxLen;
            while ($remaining > 0 && !$source->eof()) {
                $buf = $source->read(min($bufferSize, $remaining));
                $len = strlen($buf);
                if (!$len) {
                    break;
                }
                $remaining -= $len;
                $dest->write($buf);
            }
        }
    }

    /**
     * Calculate a hash of a Stream
     *
     * @param StreamInterface $stream Stream to calculate the hash for
     * @param string $algo Hash algorithm (e.g. md5, crc32, etc)
     * @param bool $rawOutput Whether or not to use raw output
     *
     * @return string Returns the hash of the stream
     * @throws RuntimeException on error.
     */
    public static function hash(StreamInterface $stream, $algo, $rawOutput = false)
    {
        $pos = $stream->tell();
        if ($stream->tell() > 0) {
            $stream->rewind();
        }

        $ctx = hash_init($algo);
        while (!$stream->eof()) {
            hash_update($ctx, $stream->read(1048576));
        }
        $out = hash_final($ctx, (bool)$rawOutput);
        $stream->seek($pos);
        return $out;
    }

    /**
     * Read a line from the stream up to the maximum allowed buffer length
     *
     * @param StreamInterface $stream Stream to read from
     * @param int $maxLength Maximum buffer length
     *
     * @return string
     */
    public static function readline(StreamInterface $stream, $maxLength = null)
    {
        $buffer = '';
        $size = 0;

        while (!$stream->eof()) {
            // Using a loose equality here to match on '' and false.
            if (null == ($byte = $stream->read(1))) {
                return $buffer;
            }
            $buffer .= $byte;
            // Break when a new line is found or the max length - 1 is reached
            if ($byte === "\n" || ++$size === $maxLength - 1) {
                break;
            }
        }

        return $buffer;
    }

    /**
     * Parses a response message string into a response object.
     *
     * @param string $message Response message string.
     *
     * @return Response
     * @throws Exception
     */
    public static function parse_response($message)
    {
        $data = _parse_message($message);
        // According to https://tools.ietf.org/html/rfc7230#section-3.1.2 the space
        // between status-code and reason-phrase is required. But browsers accept
        // responses without space and reason as well.
        if (!preg_match('/^HTTP\/.* [0-9]{3}( .*|$)/', $data['start-line'])) {
            throw new InvalidArgumentException('Invalid response string: ' . $data['start-line']);
        }
        $parts = explode(' ', $data['start-line'], 3);
        $becauseIsOldVersionOfThePHP = explode('/', $parts[0]);
        return new Response(
            $parts[1],
            $data['headers'],
            $data['body'],
            $becauseIsOldVersionOfThePHP[1],
            isset($parts[2]) ? $parts[2] : null
        );
    }

    /**
     * Parse a query string into an associative array.
     *
     * If multiple values are found for the same key, the value of that key
     * value pair will become an array. This function does not parse nested
     * PHP style arrays into an associative array (e.g., foo[a]=1&foo[b]=2 will
     * be parsed into ['foo[a]' => '1', 'foo[b]' => '2']).
     *
     * @param string $str Query string to parse
     * @param int|bool $urlEncoding How the query string is encoded
     *
     * @return array
     */
    public static function parse_query($str, $urlEncoding = true)
    {
        $result = array();

        if ($str === '') {
            return $result;
        }

        if ($urlEncoding === true) {
            $decoder = function ($value) {
                return rawurldecode(str_replace('+', ' ', $value));
            };
        } elseif ($urlEncoding === PHP_QUERY_RFC3986) {
            $decoder = 'rawurldecode';
        } elseif ($urlEncoding === PHP_QUERY_RFC1738) {
            $decoder = 'urldecode';
        } else {
            $decoder = function ($str) {
                return $str;
            };
        }

        foreach (explode('&', $str) as $kvp) {
            $parts = explode('=', $kvp, 2);
            $key = $decoder($parts[0]);
            $value = isset($parts[1]) ? $decoder($parts[1]) : null;
            if (!isset($result[$key])) {
                $result[$key] = $value;
            } else {
                if (!is_array($result[$key])) {
                    $result[$key] = array($result[$key]);
                }
                $result[$key][] = $value;
            }
        }

        return $result;
    }

    /**
     * Build a query string from an array of key value pairs.
     *
     * This function can use the return value of parse_query() to build a query
     * string. This function does not modify the provided keys when an array is
     * encountered (like http_build_query would).
     *
     * @param array $params Query string parameters.
     * @param int|false $encoding Set to false to not encode, PHP_QUERY_RFC3986
     *                            to encode using RFC3986, or PHP_QUERY_RFC1738
     *                            to encode using RFC1738.
     * @return string
     */
    public static function build_query(array $params, $encoding = PHP_QUERY_RFC3986)
    {
        if (!$params) {
            return '';
        }

        if ($encoding === false) {
            $encoder = function ($str) {
                return $str;
            };
        } elseif ($encoding === PHP_QUERY_RFC3986) {
            $encoder = 'rawurlencode';
        } elseif ($encoding === PHP_QUERY_RFC1738) {
            $encoder = 'urlencode';
        } else {
            throw new InvalidArgumentException('Invalid type');
        }

        $qs = '';
        foreach ($params as $k => $v) {
            $k = $encoder($k);
            if (!is_array($v)) {
                $qs .= $k;
                if ($v !== null) {
                    $qs .= '=' . $encoder($v);
                }
                $qs .= '&';
            } else {
                foreach ($v as $vv) {
                    $qs .= $k;
                    if ($vv !== null) {
                        $qs .= '=' . $encoder($vv);
                    }
                    $qs .= '&';
                }
            }
        }

        return $qs ? (string)substr($qs, 0, -1) : '';
    }

    /**
     * Determines the mimetype of a file by looking at its extension.
     *
     * @param $filename
     *
     * @return null|string
     */
    public static function mimetype_from_filename($filename)
    {
        return self::mimetype_from_extension(pathinfo($filename, PATHINFO_EXTENSION));
    }

    /**
     * Maps a file extensions to a mimetype.
     *
     * @param $extension string The file extension.
     *
     * @return string|null
     * @link http://svn.apache.org/repos/asf/httpd/httpd/branches/1.3.x/conf/mime.types
     */
    public static function mimetype_from_extension($extension)
    {
        static $mimetypes = array(
            '3gp' => 'video/3gpp',
            '7z' => 'application/x-7z-compressed',
            'aac' => 'audio/x-aac',
            'ai' => 'application/postscript',
            'aif' => 'audio/x-aiff',
            'asc' => 'text/plain',
            'asf' => 'video/x-ms-asf',
            'atom' => 'application/atom+xml',
            'avi' => 'video/x-msvideo',
            'bmp' => 'image/bmp',
            'bz2' => 'application/x-bzip2',
            'cer' => 'application/pkix-cert',
            'crl' => 'application/pkix-crl',
            'crt' => 'application/x-x509-ca-cert',
            'css' => 'text/css',
            'csv' => 'text/csv',
            'cu' => 'application/cu-seeme',
            'deb' => 'application/x-debian-package',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'dvi' => 'application/x-dvi',
            'eot' => 'application/vnd.ms-fontobject',
            'eps' => 'application/postscript',
            'epub' => 'application/epub+zip',
            'etx' => 'text/x-setext',
            'flac' => 'audio/flac',
            'flv' => 'video/x-flv',
            'gif' => 'image/gif',
            'gz' => 'application/gzip',
            'htm' => 'text/html',
            'html' => 'text/html',
            'ico' => 'image/x-icon',
            'ics' => 'text/calendar',
            'ini' => 'text/plain',
            'iso' => 'application/x-iso9660-image',
            'jar' => 'application/java-archive',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'js' => 'text/javascript',
            'json' => 'application/json',
            'latex' => 'application/x-latex',
            'log' => 'text/plain',
            'm4a' => 'audio/mp4',
            'm4v' => 'video/mp4',
            'mid' => 'audio/midi',
            'midi' => 'audio/midi',
            'mov' => 'video/quicktime',
            'mkv' => 'video/x-matroska',
            'mp3' => 'audio/mpeg',
            'mp4' => 'video/mp4',
            'mp4a' => 'audio/mp4',
            'mp4v' => 'video/mp4',
            'mpe' => 'video/mpeg',
            'mpeg' => 'video/mpeg',
            'mpg' => 'video/mpeg',
            'mpg4' => 'video/mp4',
            'oga' => 'audio/ogg',
            'ogg' => 'audio/ogg',
            'ogv' => 'video/ogg',
            'ogx' => 'application/ogg',
            'pbm' => 'image/x-portable-bitmap',
            'pdf' => 'application/pdf',
            'pgm' => 'image/x-portable-graymap',
            'png' => 'image/png',
            'pnm' => 'image/x-portable-anymap',
            'ppm' => 'image/x-portable-pixmap',
            'ppt' => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'ps' => 'application/postscript',
            'qt' => 'video/quicktime',
            'rar' => 'application/x-rar-compressed',
            'ras' => 'image/x-cmu-raster',
            'rss' => 'application/rss+xml',
            'rtf' => 'application/rtf',
            'sgm' => 'text/sgml',
            'sgml' => 'text/sgml',
            'svg' => 'image/svg+xml',
            'swf' => 'application/x-shockwave-flash',
            'tar' => 'application/x-tar',
            'tif' => 'image/tiff',
            'tiff' => 'image/tiff',
            'torrent' => 'application/x-bittorrent',
            'ttf' => 'application/x-font-ttf',
            'txt' => 'text/plain',
            'wav' => 'audio/x-wav',
            'webm' => 'video/webm',
            'webp' => 'image/webp',
            'wma' => 'audio/x-ms-wma',
            'wmv' => 'video/x-ms-wmv',
            'woff' => 'application/x-font-woff',
            'wsdl' => 'application/wsdl+xml',
            'xbm' => 'image/x-xbitmap',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'xml' => 'application/xml',
            'xpm' => 'image/x-xpixmap',
            'xwd' => 'image/x-xwindowdump',
            'yaml' => 'text/yaml',
            'yml' => 'text/yaml',
            'zip' => 'application/zip'
        );

        $extension = strtolower($extension);

        return isset($mimetypes[$extension])
            ? $mimetypes[$extension]
            : null;
    }

    /**
     * Get a short summary of the message body
     *
     * Will return `null` if the response is not printable.
     *
     * @param MessageInterface $message The message to get the body summary
     * @param int $truncateAt The maximum allowed size of the summary
     *
     * @return null|string
     */
    public static function get_message_body_summary(MessageInterface $message, $truncateAt = 120)
    {
        if (!$message->getBody()->isSeekable() || !$message->getBody()->isReadable()) {
            return null;
        }
        $size = $message->getBody()->getSize();
        if ($size === 0) {
            return null;
        }
        $body = $message->getBody();
        $summary = $body->read($truncateAt);
        $body->rewind();
        if ($size > $truncateAt) {
            $summary .= ' (truncated...)';
        }
        /** Matches any printable character, including unicode characters:
         * letters, marks, numbers, punctuation, spacing, and separators.
         */
        if (preg_match('/[^\pL\pM\pN\pP\pS\pZ\n\r\t]/', $summary)) {
            return null;
        }
        return $summary;
    }

    /**
     * @return Closure
     */
    public static function applicationJson()
    {
        return function (Request $request, Closure $closure) {
            $request->withHeader('Content-Type', 'application/json');
            $closure($request);
        };

    }

    /**
     * @param Request $request
     * @return array|string
     */
    public static function getBodyByMethod(Request $request)
    {
        switch (strtolower($request->getMethod())) {
            case 'get':
            {
                return $request->getQueryParams();
            }
            case 'post':
            {
                return $request->getParsedBody();
            }
            default:
            {
                try {
                    return $request->getParsedBodyContent();
                } catch (Exception $e) {
                    return $e->getMessage() . $e->getTraceAsString();
                }
            }
        }
    }

    public static function AppFactory(string $string): Router
    {
        try {
            return new Router($string);
        } catch (Exception $e) {
            try {
                $logger = new Logger('AppFactory');
                $logger->pushHandler(new StreamHandler('HttpHelper_AppFactory.log', Logger::WARNING));
                $logger->critical($e->getMessage() . PHP_EOL . $e->getTraceAsString() . PHP_EOL . $e->getCode() . PHP_EOL . $e->getLine(), [$string]);
            } catch (Exception $exception) {
                $fp = HttpHelper::try_fopen('DB_CONNECTION.log', 'wb');
                fwrite($fp, sprintf('%s', $e->getMessage() . PHP_EOL . $e->getTraceAsString() . PHP_EOL . $e->getCode() . PHP_EOL . $e->getLine()));
                fclose($fp);
            }
            exit(
                new Response(500, ['Content-Type' => 'application/json'], ['error' => true, 'message' => $e->getMessage() . PHP_EOL . $e->getTraceAsString() . PHP_EOL . $e->getCode() . PHP_EOL . $e->getLine(), 'context' => [$string]])
            );
        }
    }

    /**
     * Safely opens a PHP stream resource using a filename.
     *
     * When fopen fails, PHP normally raises a warning. This function adds an
     * error handler that checks for errors and throws an exception instead.
     *
     * @param string $filename File to open
     * @param string $mode Mode used to open the file
     *
     * @return resource
     * @throws RuntimeException if the file cannot be opened
     */
    public static function try_fopen(string $filename, string $mode)
    {
        $ex = null;
        set_error_handler(function ($errno, $errstr) use ($filename, $mode, &$ex) {
            $ex = new RuntimeException(sprintf(
                'Unable to open %s using mode %s: %s',
                $filename,
                $mode,
                $errstr
            ));
        });

        $handle = fopen($filename, $mode);
        restore_error_handler();

        if ($ex) {
            /** @var $ex RuntimeException */
            throw $ex;
        }
        return $handle;
    }
}
