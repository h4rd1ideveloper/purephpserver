<?php

//declare(strict_types=1);

namespace Psr\Http\Message;

use App\controller\AppController;
use Exception;
use InvalidArgumentException;


/**
 * PSR-7 response implementation.
 */
class Response extends HttpHelper implements ResponseInterface
{
    /** Map of standard HTTP status code/reason phrases */
    private static $PHRASES = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-status',
        208 => 'Already Reported',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Large',
        415 => 'Unsupported Media Type',
        416 => 'Requested range not satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        451 => 'Unavailable For Legal Reasons',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        511 => 'Network Authentication Required',
    );

    /** @var string */
    private $reasonPhrase = '';

    /** @var int */
    private $statusCode = 200;
    /** @var array Map of all registered headers, as original name => array of values */
    private $headers = array();
    /** @var array Map of lowercase header name => original name at registration */
    private $headerNames = array();
    /** @var string */
    private $protocol = '1.1';
    /** @var StreamInterface */
    private $stream;


    /**
     * @param int $status Status code
     * @param array $headers Response headers
     * @param string|null|resource|StreamInterface $body Response body
     * @param string $version Protocol version
     * @param string|null $reason Reason phrase (when empty a default will be used based on the status code)
     * @throws Exception
     */
    public function __construct($status = 200, $headers = array(), $body = null, $version = '1.1', $reason = null)
    {
        $this->assertStatusCodeRange($status);

        $this->statusCode = $status;

        if ($body !== '' && $body !== null) {
            $this->stream = HttpHelper::stream_for(is_array($body) ? self::toJson($body) : $body);
        }

        $this->setHeaders($headers);
        if ($reason == '' && isset(self::$PHRASES[$this->statusCode])) {
            $this->reasonPhrase = self::$PHRASES[$this->statusCode];
        } else {
            $this->reasonPhrase = (string)$reason;
        }
        $this->protocol = $version;

    }

    /**
     * @param $statusCode
     */
    private function assertStatusCodeRange($statusCode)
    {
        if ($statusCode < 100 || $statusCode >= 600) {
            throw new InvalidArgumentException('Status code must be an integer value between 1xx and 5xx.');
        }
    }

    /**
     * @param $headers
     */
    private function setHeaders($headers)
    {
        $this->headerNames = array();
        $this->headers = array();
        foreach ($headers as $header => $value) {
            if (is_int($header)) {
                $header = (string)$header;
            }
            $this->assertHeader($header);
            $value = $this->normalizeHeaderValue($value);
            $normalized = strtolower($header);
            if (isset($this->headerNames[$normalized])) {
                $header = $this->headerNames[$normalized];
                $this->headers[$header] = array_merge($this->headers[$header], $value);
            } else {
                $this->headerNames[$normalized] = $header;
                $this->headers[$header] = $value;
            }
            $aux = implode(', ', $this->headers[$header]);
            HttpHelper::setHeader("$header: $aux");
        }
    }

    /**
     * @param $header
     */
    private function assertHeader($header)
    {
        if (!is_string($header)) {
            throw new InvalidArgumentException(sprintf(
                'Header name must be a string but %s provided.',
                is_object($header) ? get_class($header) : gettype($header)
            ));
        }

        if ($header === '') {
            throw new InvalidArgumentException('Header name can not be empty.');
        }
    }

    /**
     * @param $value
     * @return string[]
     */
    private function normalizeHeaderValue($value)
    {
        if (!is_array($value)) {
            return $this->trimHeaderValues(array($value));
        }

        if (count($value) === 0) {
            throw new InvalidArgumentException('Header value can not be an empty array.');
        }

        return $this->trimHeaderValues($value);
    }

    /**
     * Trims whitespace from the header values.
     *
     * Spaces and tabs ought to be excluded by parsers when extracting the field value from a header field.
     *
     * header-field = field-name ":" OWS field-value OWS
     * OWS          = *( SP / HTAB )
     *
     * @param string[] $values Header values
     *
     * @return string[] Trimmed header values
     *
     * @see https://tools.ietf.org/html/rfc7230#section-3.2.4
     */
    private function trimHeaderValues($values)
    {
        return array_map(function ($value) {
            if (!is_string($value) && !is_numeric($value)) {
                throw new InvalidArgumentException(sprintf(
                    'Header value must be a string or numeric but %s provided.',
                    is_object($value) ? get_class($value) : gettype($value)
                ));
            }

            return trim((string)$value, " \t");
        }, $values);
    }

    public function __toString(): string
    {
        foreach ($this->headers as $key => $value) {
            if (!is_string($key)) {
                self::setHeader(explode(':', $value)[0] . ':' . explode(':', $value)[1]);
            } else {
                self::setHeader(
                    sprintf(
                        '%s: %s',
                        $key,
                        implode(' ,', is_string($value) ? [$value] : $value)
                    )
                );
            }
        }
        $s = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        self::setHeader(sprintf('%s/%s %s %s', strtoupper($s), $this->getProtocolVersion(), $this->getStatusCode(), $this->getReasonPhrase()));
        return $this->getBody()->getContents();
    }

    /**
     * @return string
     */
    public function getProtocolVersion()
    {
        return $this->protocol;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getReasonPhrase()
    {
        return $this->reasonPhrase;
    }

    /**
     * @return StreamInterface
     * @throws Exception
     */
    public function getBody()
    {
        if (!$this->stream) {
            $this->stream = HttpHelper::stream_for('');
        }
        return $this->stream;
    }

    /**
     * @param $args
     * @param bool $from
     */
    public function send($args, $from = false)
    {
        if ($from !== false) {
            AppController::view($from, $args);
        } else {
            echo $args;
        }
    }

    /**
     * @param int $code
     * @param string $reasonPhrase
     * @return Response|ResponseInterface
     */
    public function withStatus($code, $reasonPhrase = '')
    {
        $this->assertStatusCodeIsInteger($code);
        $code = (int)$code;
        $this->assertStatusCodeRange($code);
        $new = clone $this;
        $new->statusCode = $code;
        if ($reasonPhrase === '' && isset(self::$PHRASES[$new->statusCode])) {
            $reasonPhrase = self::$PHRASES[$new->statusCode];
        }
        $new->reasonPhrase = $reasonPhrase;
        return $new;
    }

    /**
     * @param $statusCode
     */
    private function assertStatusCodeIsInteger($statusCode)
    {
        if (filter_var($statusCode, FILTER_VALIDATE_INT) === false) {
            throw new InvalidArgumentException('Status code must be an integer value.');
        }
    }

    /**
     * @param string $version
     * @return $this|Response|ResponseInterface
     */
    public function withProtocolVersion($version)
    {
        if ($this->protocol === $version) {
            return $this;
        }

        $new = clone $this;
        $new->protocol = $version;
        return $new;
    }

    /**
     * @return array|string[][]
     */
    public function getHeaders()
    {
        return $this->headerNames; //$this->headers;
    }

    /**
     * @param string $header
     * @return bool
     */
    public function hasHeader($header)
    {
        return isset($this->headerNames[strtolower($header)]);
    }

    /**
     * @param string $header
     * @param string|string[] $value
     * @return Response|ResponseInterface
     */
    public function withHeader($header, $value)
    {
        $this->assertHeader($header);
        $value = $this->normalizeHeaderValue($value);
        // parent::setHeader(sprintf("%s: %s", $header, implode(',', $value)));
        $normalized = strtolower($header);
        $new = clone $this;
        if (isset($new->headerNames[$normalized])) {
            unset($new->headers[$new->headerNames[$normalized]]);
        }
        $new->headerNames[$normalized] = $header;
        $new->headers[$header] = $value;
        return $new;
    }

    /**
     * @param string $header
     * @param string|string[] $value
     * @return Response|ResponseInterface
     */
    public function withAddedHeader($header, $value)
    {
        $this->assertHeader($header);
        $value = $this->normalizeHeaderValue($value);
        $normalized = strtolower($header);
        $new = clone $this;
        if (isset($new->headerNames[$normalized])) {
            $header = $this->headerNames[$normalized];
            $new->headers[$header] = array_merge($this->headers[$header], $value);
        } else {
            $new->headerNames[$normalized] = $header;
            $new->headers[$header] = $value;
        }
        return $new;
    }

    /**
     * @param string $header
     * @return $this|Response|ResponseInterface
     */
    public function withoutHeader($header)
    {
        $normalized = strtolower($header);
        if (!isset($this->headerNames[$normalized])) {
            return $this;
        }
        header_remove($header);
        $header = $this->headerNames[$normalized];
        $new = clone $this;
        unset($new->headers[$header], $new->headerNames[$normalized]);
        return $new;
    }

    /**
     * @param string $header
     * @return string
     */
    public function getHeaderLine($header)
    {
        return implode(', ', $this->getHeader($header));
    }

    /**
     * @param string $header
     * @return array|mixed|string[]
     */
    public function getHeader($header)
    {
        $header = strtolower($header);
        if (!isset($this->headerNames[$header])) {
            return array();
        }

        $header = $this->headerNames[$header];

        return $this->headers[$header];
    }

    /**
     * @param StreamInterface $body
     * @return $this|Response|ResponseInterface
     */
    public function withBody(StreamInterface $body)
    {
        if ($body === $this->stream) {
            return $this;
        }
        $new = clone $this;
        $new->stream = $body;
        return $new;
    }
}
