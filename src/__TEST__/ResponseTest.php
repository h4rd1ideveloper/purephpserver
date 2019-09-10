<?php

namespace App\http;

use App\assets\lib\Helpers;
use App\http\Response as Response;
use Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ResponseTest
 * @package App\http
 */
class ResponseTest extends TestCase
{
    /**
     * @var \App\http\Response|ResponseTest|ResponseInterface
     */
    private $res;

    /**
     * ResponseTest constructor.
     * @param null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->res = new Response(
            200,
            array('Content-Type' => 'text/html'),
            HttpHelper::stream_for('this is a data to body response')
        );
    }

    /**
     *
     * @throws Exception
     */
    public function testGetStatusCode()
    {
        self::assertEquals(200, $this->res->getStatusCode());
    }

    /**
     *
     * @throws Exception
     */
    public function testGetReasonPhrase()
    {
        self::assertEquals("OK", $this->res->getReasonPhrase());
    }

    /**
     * @throws Exception
     * @see Response::withHeader()
     * @see ResponseTest::__construct
     */
    public function testGetHeaders()
    {
        self::assertArrayHasKey("content-type", $this->res->getHeaders());
    }

    /**
     * @throws Exception
     * @see Response::getBody()
     */
    public function testGetBody()
    {
        $content = (string)$this->res->getBody();
        self::assertEquals('this is a data to body response', $content);
    }

    /**
     * @throws Exception
     * @see Response::hasHeader('NameHeader')
     */
    public function testHasHeader()
    {
        self::assertEquals(true, $this->res->hasHeader('Content-Type'));
    }

    /**
     * @throws Exception
     * @see Response::getProtocolVersion()
     * @see Response::withProtocolVersion()
     */
    public function testWithProtocolVersion()
    {
        $this->res = $this->res->withProtocolVersion('1.1');
        self::assertEquals('1.1', $this->res->getProtocolVersion());
    }

    /**
     * @throws Exception
     * @see Response::__construct()
     * @see Response::getHeaderLine('nameHeader')
     * @see ResponseTest::__construct()
     */
    public function testGetHeaderLine()
    {
        self::assertEquals('text/html', $this->res->getHeaderLine('Content-Type'));
    }

    /**
     *
     * @throws Exception
     */
    public function testWithBody()
    {
        $this->res = $this->res->withBody(HttpHelper::stream_for('A new content to body'));
        self::assertEquals('A new content to body', (string)$this->res->getBody());
    }

    /**
     *
     * @throws Exception
     */
    public function testGetHeader()
    {
        self::assertEquals('text/html', $this->res->getHeader('Content-Type')[0]);
    }

    /**
     *
     * @throws Exception
     */
    public function testGetProtocolVersion()
    {
        self::assertEquals('1.1', $this->res->getProtocolVersion());
    }

}
