<?php

//declare(strict_types=1);

namespace App\http;

use Psr\Http\Message\StreamInterface;

/**
 * Stream decorator that can cache previously read bytes from a sequentially
 * read stream.
 */
class CachingStream implements StreamInterface
{

    /** @var StreamInterface Stream being wrapped */
    private $remoteStream;

    /** @var int Number of bytes to skip reading due to a write on the buffer */
    private $skipReadBytes = 0;
    /**
     * @var Stream
     */
    private $stream;

    /**
     * We will treat the buffer object as the body of the stream
     *
     * @param StreamInterface $stream Stream to cache
     * @param StreamInterface $target Optionally specify where data is cached
     */
    public function __construct(
        StreamInterface $stream,
        StreamInterface $target = null
    ) {
        $this->remoteStream = $stream;
        $this->stream = $target ?: new Stream(fopen('php://temp', 'r+'));
    }

    /**
     * @return int|mixed|null
     */
    public function getSize()
    {
        return max($this->stream->getSize(), $this->remoteStream->getSize());
    }

    /**
     *
     */
    public function rewind()
    {
        $this->seek(0);
    }

    /**
     * @param int $offset
     * @param int $whence
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        if ($whence == SEEK_SET) {
            $byte = $offset;
        } elseif ($whence == SEEK_CUR) {
            $byte = $offset + $this->tell();
        } elseif ($whence == SEEK_END) {
            $size = $this->remoteStream->getSize();
            if ($size === null) {
                $size = $this->cacheEntireStream();
            }
            $byte = $size + $offset;
        } else {
            throw new \InvalidArgumentException('Invalid whence');
        }

        $diff = $byte - $this->stream->getSize();

        if ($diff > 0) {
            // Read the remoteStream until we have read in at least the amount
            // of bytes requested, or we reach the end of the file.
            while ($diff > 0 && !$this->remoteStream->eof()) {
                $this->read($diff);
                $diff = $byte - $this->stream->getSize();
            }
        } else {
            // We can just do a normal seek since we've already seen this byte.
            $this->stream->seek($byte);
        }
    }

    /**
     * @param int $length
     * @return bool|string
     */
    public function read($length)
    {
        // Perform a regular read on any previously read data from the buffer
        $data = $this->stream->read($length);
        $remaining = $length - strlen($data);

        // More data was requested so read from the remote stream
        if ($remaining) {
            // If data was written to the buffer in a position that would have
            // been filled from the remote stream, then we must skip bytes on
            // the remote stream to emulate overwriting bytes from that
            // position. This mimics the behavior of other PHP stream wrappers.
            $remoteData = $this->remoteStream->read(
                $remaining + $this->skipReadBytes
            );

            if ($this->skipReadBytes) {
                $len = strlen($remoteData);
                $remoteData = substr($remoteData, $this->skipReadBytes);
                $this->skipReadBytes = max(0, $this->skipReadBytes - $len);
            }

            $data .= $remoteData;
            $this->stream->write($remoteData);
        }

        return $data;
    }

    /**
     * @param string $string
     * @return bool|int
     */
    public function write($string)
    {
        // When appending to the end of the currently read stream, you'll want
        // to skip bytes from being read from the remote stream to emulate
        // other stream wrappers. Basically replacing bytes of data of a fixed
        // length.
        $overflow = (strlen($string) + $this->tell()) - $this->remoteStream->tell();
        if ($overflow > 0) {
            $this->skipReadBytes += $overflow;
        }

        return $this->stream->write($string);
    }

    /**
     * @return bool
     */
    public function eof()
    {
        return $this->stream->eof() && $this->remoteStream->eof();
    }

    /**
     * Close both the remote stream and buffer stream
     */
    public function close()
    {
        $this->remoteStream->close() && $this->stream->close();
    }

    /**
     * @return int
     */
    private function cacheEntireStream()
    {
        $target = new FnStream(array('write' => 'strlen'));
        HttpHelper::copy_to_stream($this, $target);

        return $this->tell();
    }

    /**
     * Magic method used to create a new stream if streams are not added in
     * the constructor of a decorator (e.g., LazyOpenStream).
     *
     * @param string $name Name of the property (allows "stream" only).
     *
     * @return StreamInterface
     */
    public function __get($name)
    {
        if ($name == 'stream') {
            //$this->stream = $this->createStream();
            return $this->stream;
        }

        throw new \UnexpectedValueException("$name not found on class");
    }

    public function __toString()
    {
        try {
            if ($this->isSeekable()) {
                $this->seek(0);
            }
            return $this->getContents();
        } catch (\Throwable $e) {
            // Really, PHP? https://bugs.php.net/bug.php?id=53648
            trigger_error('StreamDecorator::__toString exception: '.(string) $e, E_USER_ERROR);
            return '';
        }
    }

    public function getContents()
    {
        return HttpHelper::copy_to_string($this);
    }

    /**
     * Allow decorators to implement custom methods
     *
     * @param string $method Missing method name
     * @param array  $args   Method arguments
     *
     * @return mixed
     */
    public function __call($method, array $args)
    {
        $result = call_user_func_array(array($this->stream, $method), $args);

        // Always return the wrapped object if the result is a return $this
        return $result === $this->stream ? $this : $result;
    }


    public function getMetadata($key = null)
    {
        return $this->stream->getMetadata($key);
    }

    public function detach()
    {
        return $this->stream->detach();
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

    public function isSeekable()
    {
        return $this->stream->isSeekable();
    }

    /**
     * Implement in subclasses to dynamically create streams when requested.
     *
     * @return void
     */
    protected function createStream()
    {
        throw new \BadMethodCallException('Not implemented');
    }
}
