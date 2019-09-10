<?php

//declare(strict_types=1); only php 7

namespace App\http;

use InvalidArgumentException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\UriInterface;

include_once 'functions.php';

/**
 * PSR-7 request implementation.
 * @author Yan Santos Policar <policarpo@ice.ufjf.br>
 * @version 1.1.0
 */
class Request extends HttpHelper implements RequestInterface
{
    /** @var array Map of all registered headers, as original name => array of values */
    private $headers = array();

    /** @var array Map of lowercase header name => original name at registration */
    private $headerNames = array();

    /** @var string */
    private $protocol = '1.1';

    /** @var StreamInterface */
    private $stream;
    /** @var string */
    private $method;
    /** @var null|string */
    private $requestTarget;
    /** @var UriInterface */
    private $uri;
    /**
     * @var
     */
    private $queryString;
    /**
     * @var
     */
    private $queryParams;
    /**
     * @var array
     */
    private $cookieParams = array();
    /**
     * @var
     */
    private $parsedBody;
    /**
     * @var array
     */
    private $uploadedFiles;

    /**
     * __construct(
     * $method,
     * $uri,
     * $headers = array(),
     * $body = null,
     * $version = '1.1'
     * )
     * @param string $method HTTP method
     * @param string|UriInterface $uri URI
     * @param array $headers Request headers
     * @param string|null|resource|StreamInterface $body Request body
     * @param string $version Protocol version
     */
    public function __construct($method, $uri, $headers = array(), $body = null, $version = '1.1')
    {
        $this->assertMethod($method);
        if (!($uri instanceof UriInterface)) {
            $uri = new Uri($uri);
        }
        $this->method = strtoupper($method);
        $this->uri = $uri;
        //var_dump($this->uri->getQuery());
        $this->setHeaders($headers);
        $this->protocol = $version;
        if (!isset($this->headerNames['host'])) {
            $this->updateHostFromUri();
        }
        if ($body !== '' && $body !== null) {
            $this->stream = HttpHelper::stream_for($body);
        }
    }

    /**
     * assertMethod($method)
     * @param $method
     */
    private function assertMethod($method)
    {
        if (!is_string($method) || $method === '') {
            throw new InvalidArgumentException('Method must be a non-empty string.');
        }
    }

    /**
     * setHeaders(array $headers)
     * @param array $headers
     */
    private function setHeaders(array $headers)
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
        }
    }

    /**
     * assertHeader($header)
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
     * normalizeHeaderValue($value)
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
     * trimHeaderValues(array $values)
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
    private function trimHeaderValues(array $values)
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

    /**
     *updateHostFromUri()
     */
    private function updateHostFromUri()
    {
        $host = $this->uri->getHost();

        if ($host == '') {
            return;
        }

        if (($port = $this->uri->getPort()) !== null) {
            $host .= ':' . $port;
        }

        if (isset($this->headerNames['host'])) {
            $header = $this->headerNames['host'];
        } else {
            $header = 'Host';
            $this->headerNames['host'] = 'Host';
        }
        // Ensure Host is the first header.
        // See: http://tools.ietf.org/html/rfc7230#section-5.4
        $this->headers = array($header => array($host)) + $this->headers;
    }

    /**
     * @param array $content
     * @return string
     */
    public static final function toJson($content = array())
    {
        return parent::toJson($content);
    }

    /**
     * Return an UploadedFile instance array.
     *
     * @param array $files A array which respect $_FILES structure
     * @return array
     * @throws InvalidArgumentException for unrecognized values
     */
    public static function normalizeFiles(array $files)
    {
        $normalized = array();
        foreach ($files as $key => $value) {
            if ($value instanceof UploadedFileInterface) {
                $normalized[$key] = $value;
            } elseif (is_array($value) && isset($value['tmp_name'])) {
                $normalized[$key] = self::createUploadedFileFromSpec($value);
            } elseif (is_array($value)) {
                $normalized[$key] = self::normalizeFiles($value);
                continue;
            } else {
                throw new InvalidArgumentException('Invalid value in files specification');
            }
        }
        return $normalized;
    }

    /**
     * Create and return an UploadedFile instance from a $_FILES specification.
     *
     * If the specification represents an array of values, this method will
     * delegate to normalizeNestedFileSpec() and return that return value.
     *
     * @param array $value $_FILES struct
     * @return array|UploadedFileInterface
     */
    private static function createUploadedFileFromSpec(array $value)
    {
        if (is_array($value['tmp_name'])) {
            return self::normalizeNestedFileSpec($value);
        }
        return new UploadedFile(
            $value['tmp_name'],
            (int)$value['size'],
            (int)$value['error'],
            $value['name'],
            $value['type']
        );
    }

    /**
     * Normalize an array of file specifications.
     *
     * Loops through all nested files and returns a normalized array of
     * UploadedFileInterface instances.
     *
     * @param array $files
     * @return UploadedFileInterface[]
     */
    private static function normalizeNestedFileSpec($files = array())
    {
        $normalizedFiles = array();

        foreach (array_keys($files['tmp_name']) as $key) {
            $spec = array(
                'tmp_name' => $files['tmp_name'][$key],
                'size' => $files['size'][$key],
                'error' => $files['error'][$key],
                'name' => $files['name'][$key],
                'type' => $files['type'][$key],
            );
            $normalizedFiles[$key] = self::createUploadedFileFromSpec($spec);
        }
        return $normalizedFiles;
    }

    /**
     * @return array
     */
    public function getUploadedFiles()
    {
        return $this->uploadedFiles;
    }

    /**
     * {@inheritdoc}
     */
    public function withUploadedFiles(array $uploadedFiles)
    {
        $new = clone $this;
        $new->uploadedFiles = $uploadedFiles;
        return $new;
    }

    /**
     * {@inheritdoc}
     */
    public function withParsedBody($data)
    {
        $new = clone $this;
        $new->parsedBody = $data;
        //var_dump($data,"her");
        return $new;
    }

    /**
     * {@inheritdoc}
     */
    public function getParsedBody()
    {
        return count($this->parsedBody) ? $this->parsedBody : array();
    }

    /**
     * {@inheritdoc}
     */
    public function getCookieParams()
    {
        return $this->cookieParams;
    }

    /**
     * {@inheritdoc}
     */
    public function withCookieParams(array $cookies)
    {
        $new = clone $this;
        $new->cookieParams = $cookies;

        return $new;
    }

    /**
     * {@inheritdoc}
     */
    public function withQueryParams(array $query)
    {
        $new = clone $this;
        $new->queryParams = $query;

        return $new;
    }

    /**
     * @return mixed
     */
    public function getQueryParams()
    {
        return $this->queryParams;
    }

    /**
     * @return mixed
     */
    public function getQueryString()
    {
        return $this->queryString;
    }

    /**
     * @return Request
     */
    public function setQueryString()
    {
        $new = clone $this;
        $hasQuery = false;
        if (isset($_SERVER['REQUEST_URI'])) {
            $requestUriParts = explode('?', $_SERVER['REQUEST_URI'], 2);
            if (isset($requestUriParts[1])) {
                $hasQuery = true;
                $new->queryString = $requestUriParts[1];
            }
        }
        if (!$hasQuery && isset($_SERVER['QUERY_STRING'])) {
            $new->queryString = $_SERVER['QUERY_STRING'];
        }

        return $new;
    }

    /**
     * getProtocolVersion()
     * @return string
     */
    public function getProtocolVersion()
    {
        return $this->protocol;
    }

    /**
     * withProtocolVersion($version)
     * @param $version
     * @return $this|Request
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
     * getHeaders()
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * hasHeader($header)
     * @param $header
     * @return bool
     */
    public function hasHeader($header)
    {
        return isset($this->headerNames[strtolower($header)]);
    }

    /**
     * getHeaderLine($header)
     * @param $header
     * @return string
     */
    public function getHeaderLine($header)
    {
        return implode(', ', $this->getHeader($header));
    }

    /**
     * getHeader($header)
     * @param $header
     * @return array|mixed
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
     * withHeader($header, $value)
     * @param $header
     * @param $value
     * @return Request
     */
    public function withHeader($header, $value)
    {
        $this->assertHeader($header);
        $value = $this->normalizeHeaderValue($value);
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
     * withAddedHeader($header, $value)
     * @param $header
     * @param $value
     * @return Request
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
     * withoutHeader($header)
     * @param $header
     * @return $this|Request
     */
    public function withoutHeader($header)
    {
        $normalized = strtolower($header);

        if (!isset($this->headerNames[$normalized])) {
            return $this;
        }

        $header = $this->headerNames[$normalized];

        $new = clone $this;
        unset($new->headers[$header], $new->headerNames[$normalized]);

        return $new;
    }

    /**
     * getBody()
     * @return StreamInterface
     */
    public function getBody()
    {
        if (!$this->stream) {
            $this->stream = HttpHelper::stream_for('');
        }

        return $this->stream;
    }

    /**
     * withBody(StreamInterface $body
     * @param StreamInterface $body
     * @return $this|Request
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

    /**
     *  getRequestTarget()
     * @return string|null
     */
    public function getRequestTarget()
    {
        if ($this->requestTarget !== null) {
            return $this->requestTarget;
        }

        $target = $this->uri->getPath();
        if ($target == '') {
            $target = '/';
        }
        if ($this->uri->getQuery() != '') {
            $target .= '?' . $this->uri->getQuery();
        }

        return $target;
    }

    /**
     * withRequestTarget($requestTarget)
     * @param $requestTarget
     * @return Request
     */
    public function withRequestTarget($requestTarget)
    {
        if (preg_match('#\s#', $requestTarget)) {
            throw new InvalidArgumentException(
                'Invalid request target provided; cannot contain whitespace'
            );
        }

        $new = clone $this;
        $new->requestTarget = $requestTarget;
        return $new;
    }

    /**
     * getMethod()
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * withMethod($method)
     * @param $method
     * @return Request
     */
    public function withMethod($method)
    {
        $this->assertMethod($method);
        $new = clone $this;
        $new->method = strtoupper($method);
        return $new;
    }

    /**
     * getUri()
     * @return Uri|UriInterface|string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * withUri(UriInterface $uri, $preserveHost = false)
     * @param UriInterface $uri
     * @param bool $preserveHost
     * @return $this|Request
     */
    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        if ($uri === $this->uri) {
            return $this;
        }

        $new = clone $this;
        $new->uri = $uri;

        if (!$preserveHost || !isset($this->headerNames['host'])) {
            $new->updateHostFromUri();
        }

        return $new;
    }
}
