<?php

//declare(strict_types=1);

namespace App\http;

use Exception;
use Psr\Http\Message\StreamInterface;
use Throwable;
use UnexpectedValueException;

/**
 * Lazily reads or writes to a file that is opened only after an IO operation
 * take place on the stream.
 */
class LazyOpenStream implements StreamInterface
{

    /** @var string File to open */
    private $filename;

    /** @var string $mode */
    private $mode;
    /**
     * @var StreamInterface
     */
    private $stream;

    /**
     * @param string $filename File to lazily open
     * @param string $mode fopen mode to use when opening the stream
     * @throws Exception
     */
    public function __construct($filename, $mode)
    {
        $this->filename = $filename;
        $this->mode = $mode;
        $this->stream = HttpHelper::stream_for(HttpHelper::try_fopen($this->filename, $this->mode));
    }

    /**
     * Magic method used to create a new stream if streams are not added in
     * the constructor of a decorator (e.g., LazyOpenStream).
     *
     * @param string $name Name of the property (allows "stream" only).
     *
     * @return StreamInterface
     * @throws Exception
     */
    public function __get($name)
    {
        if ($name == 'stream') {
            $this->stream = $this->createStream();
            return $this->stream;
        }

        throw new UnexpectedValueException("$name not found on class");
    }

    /**
     * Creates the underlying stream lazily when required.
     *
     * @return StreamInterface
     * @throws Exception
     */
    protected function createStream()
    {
        return HttpHelper::stream_for(HttpHelper::try_fopen($this->filename, $this->mode));
    }

    public function __toString()
    {
        try {
            if ($this->isSeekable()) {
                $this->seek(0);
            }
            return $this->getContents();
        } catch (Throwable $e) {
            // Really, PHP? https://bugs.php.net/bug.php?id=53648
            trigger_error('StreamDecorator::__toString exception: ' . (string)$e, E_USER_ERROR);
            return '';
        }
    }

    public function isSeekable()
    {
        return $this->stream->isSeekable();
    }

    public function seek($offset, $whence = SEEK_SET)
    {
        $this->stream->seek($offset, $whence);
    }

    public function getContents()
    {
        return HttpHelper::copy_to_string($this);
    }

    /**
     * Allow decorators to implement custom methods
     *
     * @param string $method Missing method name
     * @param array $args Method arguments
     *
     * @return mixed
     */
    public function __call($method, array $args)
    {
        $result = call_user_func_array(array($this->stream, $method), $args);

        // Always return the wrapped object if the result is a return $this
        return $result === $this->stream ? $this : $result;
    }

    public function close()
    {
        $this->stream->close();
    }

    public function getMetadata($key = null)
    {
        return $this->stream->getMetadata($key);
    }

    public function detach()
    {
        return $this->stream->detach();
    }

    public function getSize()
    {
        return $this->stream->getSize();
    }

    public function eof()
    {
        return $this->stream->eof();
    }

    public function tell()
    {
        return $this->stream->tell();
    }

    public function isReadable()
    {
        return $this->stream->isReadable();
    }

    public function isWritable()
    {
        return $this->stream->isWritable();
    }

    public function rewind()
    {
        $this->seek(0);
    }

    public function read($length)
    {
        return $this->stream->read($length);
    }

    public function write($string)
    {
        return $this->stream->write($string);
    }
}
