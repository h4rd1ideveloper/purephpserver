<?php

namespace App\http;

use App\assets\lib\Helpers;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Class RequestTest
 * @package App\http
 */
class RequestTest extends TestCase
{
    private $req;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->req = HttpHelper::requestFromGlobalsFactory('GET','1.1'); // new Request('GET', new Uri('/'), array('Content-Type' => 'text/html'), HttpHelper::stream_for(''));
    }

    /**
     *
     * @throws Exception
     */
    public function testToJson()
    {
        self::assertEquals(
            Helpers::toJson(
                array("_UMD" => 123, array("nome" => "yan", "idade" => 22))
            ),
            Request::toJson(
                array("_UMD" => 123, array("nome" => "yan", "idade" => 22))
            )
        );
    }

    /**
     * @throws Exception
     * @see Request::withMethod()
     * @see Request::getMethod()
     */
    public function testWithMethod()
    {
        self::assertEquals("POST", $this->req->withMethod("POST")->getMethod());
        self::assertEquals("GET", $this->req->getMethod());
    }

    /**
     *
     * @throws Exception
     */
    public function testWithQueryParams()
    {
        self::assertEquals(array("payload" => "value"), $this->req->withQueryParams(array("payload" => "value"))->getQueryParams());
    }

    /**
     *
     * @throws Exception
     */
    public function testGetQueryParams()
    {
        self::assertEquals(array("payload" => "value2"), $this->req->withQueryParams(array("payload" => "wrong"))->withQueryParams(array("payload" => "value2"))->getQueryParams());
    }


    /**
     *
     * @throws Exception
     */
    public function testGetMethod()
    {
        self::assertEquals("GET", $this->req->getMethod());
    }


    /**
     *
     * @throws Exception
     */
    public function testGetBody()
    {
        //Old Context self::assertInstanceOf(Stream::class, $this->req->getBody());
        self::assertInstanceOf(CachingStream::class, $this->req->getBody());
    }

    /**
     *
     * @throws Exception
     */
    public function testWithCookieParams()
    {
        self::assertEquals(/**@lang JSON */ '{"sameCookie":"value"}', Helpers::toJson($this->req->withCookieParams(array("sameCookie" => "value"))->getCookieParams()));
    }

    /**
     *
     * @throws Exception
     */
    public function test__construct()
    {
        // Default Values to param Construct Request $method, $uri, $headers = array(), $body = null, $version = '1.1'
        $tempRequest = new Request("GET", new Uri("/baseUrl"), array('Content-Type' => 'application/json'), HttpHelper::stream_for('Some data string to Stream'), '1.1');
        self::assertEquals("GET", $tempRequest->getMethod());
        self::assertInstanceOf(Uri::class, $tempRequest->getUri());
        self::assertEquals("/baseUrl", $tempRequest->getUri()->getPath());
        self::assertEquals('application/json', $tempRequest->getHeaderLine('Content-Type'));
        self::assertArrayHasKey('Content-Type', $tempRequest->getHeaders());
        self::assertInstanceOf(Stream::class, $tempRequest->getBody());
        self::assertEquals('Some data string to Stream', $tempRequest->getBody()->getContents());
        self::assertEquals("1.1", $tempRequest->getProtocolVersion());
    }

    /**
     *
     * @throws Exception
     */
    public function testGetHeader()
    {
        $tempRequest = $this->req->withHeader("Content-Type", "text/html");
        self::assertEquals('text/html', $tempRequest->getHeader("Content-Type")[0]);
    }

    /**
     *
     * @throws Exception
     */
    public function testWithUri()
    {
        $tempRequest = new Request("GET", new Uri("/baseUrl"), array('Content-Type' => 'application/json'), HttpHelper::stream_for('Some data string to Stream'), '1.1');
        $tempRequest = $tempRequest->withUri(new Uri('/NewPath?yan=123'));
        self::assertInstanceOf(Uri::class, $tempRequest->getUri());
        self::assertEquals("/NewPath", $tempRequest->getUri()->getPath());
        //self::assertEquals("getQueryString", $tempRequest->getQueryString());
        //self::assertEquals("getQueryParams", $tempRequest::toJson($tempRequest->getQueryParams() ));
    }

    /**
     *
     * @throws Exception
     */
    public function testGetHeaderLine()
    {
        $tempRequest = $this->req->withHeader('Content-Type', 'text/html');
        self::assertEquals('text/html', $tempRequest->getHeaderLine('Content-Type'));
    }

    /**
     *
     * @throws Exception
     */
    public function testGetCookieParams()
    {
        self::assertEquals(/**@lang JSON */ "[]", $this->req::toJson($this->req->getCookieParams()));
    }

    /**
     *
     * @throws Exception
     */
    public function testGetParsedBody()
    {
        self::assertEquals(/**@lang JSON */ "[]", $this->req::toJson($this->req->getParsedBody()));
    }

    /**
     *
     * @throws Exception
     */
    public function testGetProtocolVersion()
    {
        self::assertEquals("1.1", $this->req->getProtocolVersion());
    }

    /**
     *
     * @throws Exception
     */
    public function testWithProtocolVersion()
    {
        self::assertEquals("1.0", $this->req->withProtocolVersion("1.0")->getProtocolVersion());
    }

    /**
     *
     * @throws Exception
     */
    public function testWithoutHeader()
    {
        $tempRequest = $this->req->withHeader('Content-Type', 'text/html');
        self::assertEquals(/**@lang JSON*/'{"Host":["localhost"]}', $tempRequest::toJson($tempRequest->withoutHeader("Content-Type")->getHeaders()));
    }

    /**
     *
     * @throws Exception
     */
    public function testGetQueryString()
    {
        self::assertEquals("", $this->req->getQueryString());
    }

    /**
     *
     * @throws Exception
     */
    public function testWithParsedBody()
    {
        self::assertArrayHasKey(
            "field_2",
            $this->req->withParsedBody(array("field_2" => "value_2"))->getParsedBody()
        );
    }

    /**
     *
     * @throws Exception
     */
    public function testWithBody()
    {
        self::assertEquals(
            "new content to body",
            $this->req->withBody($this->req::stream_for("new content to body"))->getBody()->getContents()
        );
    }

    /**
     *
     * @throws Exception
     */
    public function testWithUploadedFiles()
    {
        self::assertArrayHasKey("temp_name", $this->req->withUploadedFiles(array("temp_name" => "fileName.xlsx"))->getUploadedFiles());
    }

    /**
     *
     * @throws Exception
     */
    public function testGetRequestTarget()
    {
        self::assertEquals("/", $this->req->getRequestTarget());
    }

    /**
     *
     * @throws Exception
     */
    public function testGetHeaders()
    {
        $tempRequest = $this->req->withHeader('Content-Type','text/html');
        self::assertArrayHasKey("Content-Type", $tempRequest->getHeaders());
    }

    /**
     *
     * @throws Exception
     */
    public function testWithAddedHeader()
    {
        $tempRequest = $this->req->withHeader('Content-Type','text/html')->withAddedHeader("Content-Type", "application/json");
        self::assertEquals('text/html, application/json', $tempRequest->getHeaderLine("Content-Type"));
    }

    /**
     *
     * @throws Exception
     */
    public function testNormalizeFiles()
    {
        self::assertEquals(
            '[]',
            $this->req::toJson(
                $this->req::normalizeFiles(
                    $_FILES
                )
            )
        );
    }

    /**
     *
     * @throws Exception
     */
    public function testWithRequestTarget()
    {
        self::assertEquals("/test", $this->req->withRequestTarget("/test")->getRequestTarget());
    }

    /**
     *
     * @throws Exception
     */
    public function testGetUri()
    {
        self::assertInstanceOf(Uri::class, $this->req->getUri());
    }
}
