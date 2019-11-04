<?php

//declare(strict_types=1);

namespace Psr\Http\Message;

use BadMethodCallException;
use LogicException;
use Throwable;

/**
 * Compose stream implementations based on a hash of functions.
 *
 * Allows for easy testing and extension of a provided stream without needing
 * to create a concrete class for a simple extension point.
 */
class FnStream implements StreamInterface
{
    /**
     *
     */
    private static $SLOTS = array(
        '__toString', 'close', 'detach', 'rewind',
        'getSize', 'tell', 'eof', 'isSeekable', 'seek', 'isWritable', 'write',
        'isReadable', 'read', 'getContents', 'getMetadata'
    );

    /** @var array */
    private $methods;

    /**
     * @param array $methods Hash of method name to a callable.
     */
    public function __construct(array $methods)
    {
        $this->methods = $methods;

        // Create the functions on the class
        foreach ($methods as $name => $fn) {
            $this->{'_fn_' . $name} = $fn;
        }
    }

    /**
     * Adds custom functionality to an underlying stream by intercepting
     * specific method calls.
     *
     * @param StreamInterface $stream Stream to decorate
     * @param array $methods Hash of method name to a closure
     *
     * @return FnStream
     */
    public static function decorate(StreamInterface $stream, array $methods)
    {
        // If any of the required methods were not provided, then simply
        // proxy to the decorated stream.
        foreach (array_diff(self::$SLOTS, array_keys($methods)) as $diff) {
            $methods[$diff] = array($stream, $diff);
        }

        return new self($methods);
    }

    /**
     * Lazily determine which methods are not implemented.
     * @param $name
     */
    public function __get($name)
    {
        throw new BadMethodCallException(str_replace('_fn_', '', $name)
            . '() is not implemented in the FnStream');
    }

    /**
     * The close method is called on the underlying stream only if possible.
     */
    public function __destruct()
    {
        if (isset($this->_fn_close)) {
            call_user_func($this->_fn_close);
        }
    }

    /**
     * An unserialize would allow the __destruct to run when the unserialized value goes out of scope.
     * @throws LogicException
     */
    public function __wakeup()
    {
        throw new LogicException('FnStream should never be unserialized');
    }

    /**
     * @return mixed|string
     */
    public function __toString()
    {
        try {
            return call_user_func($this->_fn___toString);
        } catch (Throwable $e) {
            trigger_error(sprintf('%s::__toString exception: %s', __CLASS__, (string)$e), E_USER_ERROR);
            return '';
        }
    }

    /**
     * @return mixed|void
     */
    public function close()
    {
        return call_user_func($this->_fn_close);
    }

    /**
     * @return mixed|resource|null
     */
    public function detach()
    {
        return call_user_func($this->_fn_detach);
    }

    /**
     * @return int|mixed|null
     */
    public function getSize()
    {
        return call_user_func($this->_fn_getSize);
    }

    /**
     * @return int|mixed
     */
    public function tell()
    {
        return call_user_func($this->_fn_tell);
    }

    /**
     * @return bool|mixed
     */
    public function eof()
    {
        return call_user_func($this->_fn_eof);
    }

    /**
     * @return bool|mixed
     */
    public function isSeekable()
    {
        return call_user_func($this->_fn_isSeekable);
    }

    /**
     *
     */
    public function rewind()
    {
        call_user_func($this->_fn_rewind);
    }

    /**
     * @param int $offset
     * @param int $whence
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        call_user_func($this->_fn_seek, $offset, $whence);
    }

    /**
     * @return bool|mixed
     */
    public function isWritable()
    {
        return call_user_func($this->_fn_isWritable);
    }

    /**
     * @param string $string
     * @return int|mixed
     */
    public function write($string)
    {
        return call_user_func($this->_fn_write, $string);
    }

    /**
     * @return bool|mixed
     */
    public function isReadable()
    {
        return call_user_func($this->_fn_isReadable);
    }

    /**
     * @param int $length
     * @return mixed|string
     */
    public function read($length)
    {
        return call_user_func($this->_fn_read, $length);
    }

    /**
     * @return mixed|string
     */
    public function getContents()
    {
        return call_user_func($this->_fn_getContents);
    }

    /**
     * @param null $key
     * @return array|mixed|null
     */
    public function getMetadata($key = null)
    {
        return call_user_func($this->_fn_getMetadata, $key);
    }
}
